<?php

namespace Alura\Pdo\Infrastructure\Web;

use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Service\StudentValidator;
use Alura\Pdo\Infrastructure\Service\AppLogger;
use Alura\Pdo\Infrastructure\Service\Cache;
use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;

class ApiController
{
    private StudentRepository $repository;
    private StudentValidator $validator;
    private AppLogger $logger;
    private Cache $cache;
    private EnvironmentConfig $config;

    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
        $this->validator = new StudentValidator();
        $this->logger = AppLogger::getInstance();
        $this->cache = Cache::getInstance();
        $this->config = EnvironmentConfig::getInstance();
    }

    public function handleRequest(): void
    {
        // Capturar qualquer saída de erro PHP
        ob_start();
        
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            ob_end_clean();
            return;
        }

        try {
            $path = $_GET['path'] ?? '/';
            
            $this->logger->info('API GET Request', [
                'path' => $path,
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
                'query_string' => $_SERVER['QUERY_STRING'] ?? 'unknown'
            ]);
            
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $this->handleGet($path);
                    break;
                case 'POST':
                    $this->handlePost($path);
                    break;
                case 'PUT':
                    $this->handlePut($path);
                    break;
                case 'DELETE':
                    $this->handleDelete($path);
                    break;
                default:
                    $this->sendError('Método não permitido', 405);
            }
        } catch (\Exception $e) {
            $this->logger->error('API Error: ' . $e->getMessage(), [
                'method' => $_SERVER['REQUEST_METHOD'],
                'path' => $path,
                'trace' => $e->getTraceAsString()
            ]);
            $this->sendError($e->getMessage(), 500);
        } finally {
            // Limpar qualquer saída de erro PHP
            $errorOutput = ob_get_clean();
            // Só tratar como erro se não for vazio e não for apenas whitespace
            if (!empty(trim($errorOutput))) {
                $this->logger->error('PHP Error Output: ' . $errorOutput);
                $msg = $this->config->isDebugEnabled() ? $errorOutput : 'Erro interno do PHP';
                $this->sendError($msg, 500);
            }
        }
    }

    private function handleGet(string $path): void
    {
        if ($path === '/students' || $path === '/students/') {
            $this->getAllStudents();
        } elseif (preg_match('/^\/students\/(\d+)$/', $path, $matches)) {
            $this->getStudentById((int) $matches[1]);
        } elseif ($path === '/stats') {
            $this->getStats();
        } else {
            $this->sendError('Endpoint não encontrado', 404);
        }
    }

    private function handlePost(string $path): void
    {
        $rawInput = file_get_contents('php://input');
        $this->logger->info('API POST Request', [
            'path' => $path,
            'raw_input' => $rawInput,
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD']
        ]);
        
        if ($path === '/students' || $path === '/students/') {
            $this->createStudent();
        } else {
            $this->sendError('Endpoint não encontrado', 404);
        }
    }

    private function handlePut(string $path): void
    {
        $rawInput = file_get_contents('php://input');
        $this->logger->info('API PUT Request', [
            'path' => $path,
            'raw_input' => $rawInput,
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD']
        ]);
        
        if (preg_match('/^\/students\/(\d+)$/', $path, $matches)) {
            $this->updateStudent((int) $matches[1]);
        } else {
            $this->sendError('Endpoint não encontrado', 404);
        }
    }

    private function handleDelete(string $path): void
    {
        if (preg_match('/^\/students\/(\d+)$/', $path, $matches)) {
            $this->deleteStudent((int) $matches[1]);
        } else {
            $this->sendError('Endpoint não encontrado', 404);
        }
    }

    private function getAllStudents(): void
    {
        $cacheKey = 'api_students_all';
        $cached = $this->cache->get($cacheKey);
        
        if ($cached !== null) {
            $this->sendResponse($cached);
            return;
        }

        $students = $this->repository->allStudents();
        $data = array_map(fn($student) => $this->studentToArray($student), $students);
        
        $this->cache->set($cacheKey, $data, 300); // 5 minutos
        $this->sendResponse($data);
    }

    private function getStudentById(int $id): void
    {
        $student = $this->repository->findById($id);
        
        if ($student === null) {
            $this->sendError('Aluno não encontrado', 404);
            return;
        }

        $this->sendResponse($this->studentToArray($student));
    }

    private function createStudent(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendError('Dados inválidos', 400);
            return;
        }

        try {
            $student = new Student(
                null,
                $input['name'],
                new \DateTimeImmutable($input['birth_date']),
                $input['cep'] ?? '',
                $input['address'] ?? ''
            );

            $errors = $this->validator->validateStudent($student);
            
            if (!empty($errors)) {
                $this->sendError('Erro de validação: ' . implode(', ', $errors), 400);
                return;
            }

            if ($this->repository->save($student)) {
                $this->cache->delete('api_students_all');
                $this->cache->delete('api_stats');
                
                $this->logger->info('Aluno criado via API', [
                    'name' => $student->name(),
                    'id' => $student->id()
                ]);

                $this->sendResponse($this->studentToArray($student), 201);
            } else {
                $this->sendError('Erro ao salvar aluno', 500);
            }
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 400);
        }
    }

    private function updateStudent(int $id): void
    {
        $student = $this->repository->findById($id);
        
        if ($student === null) {
            $this->sendError('Aluno não encontrado', 404);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendError('Dados inválidos', 400);
            return;
        }

        try {
            if (isset($input['name'])) {
                $student->changeName($input['name']);
            }

            if (isset($input['birth_date'])) {
                $student = new Student(
                    $student->id(),
                    $student->name(),
                    new \DateTimeImmutable($input['birth_date']),
                    $student->cep(),
                    $student->address()
                );
            }

            if (isset($input['cep'])) {
                $student->changeCep($input['cep']);
            }

            if (isset($input['address'])) {
                $student->changeAddress($input['address']);
            }

            $errors = $this->validator->validateStudent($student);
            
            if (!empty($errors)) {
                $this->sendError('Erro de validação: ' . implode(', ', $errors), 400);
                return;
            }

            if ($this->repository->save($student)) {
                $this->cache->delete('api_students_all');
                $this->cache->delete('api_stats');
                
                $this->logger->info('Aluno atualizado via API', [
                    'id' => $student->id(),
                    'name' => $student->name()
                ]);

                $this->sendResponse($this->studentToArray($student));
            } else {
                $this->sendError('Erro ao atualizar aluno', 500);
            }
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 400);
        }
    }

    private function deleteStudent(int $id): void
    {
        $student = $this->repository->findById($id);
        
        if ($student === null) {
            $this->sendError('Aluno não encontrado', 404);
            return;
        }

        if ($this->repository->remove($student)) {
            $this->cache->delete('api_students_all');
            $this->cache->delete('api_stats');
            
            $this->logger->info('Aluno excluído via API', [
                'id' => $student->id(),
                'name' => $student->name()
            ]);

            $this->sendResponse(['message' => 'Aluno excluído com sucesso']);
        } else {
            $this->sendError('Erro ao excluir aluno', 500);
        }
    }

    private function getStats(): void
    {
        $cacheKey = 'api_stats';
        $cached = $this->cache->get($cacheKey);
        
        if ($cached !== null) {
            $this->sendResponse($cached);
            return;
        }

        $students = $this->repository->allStudents();
        $total = count($students);
        $adults = array_filter($students, fn($s) => $s->age() >= 18);
        $minors = array_filter($students, fn($s) => $s->age() < 18);

        $stats = [
            'total_students' => $total,
            'adults' => count($adults),
            'minors' => count($minors),
            'average_age' => $total > 0 ? array_sum(array_map(fn($s) => $s->age(), $students)) / $total : 0,
            'cache_stats' => $this->cache->getStats()
        ];

        $this->cache->set($cacheKey, $stats, 600); // 10 minutos
        $this->sendResponse($stats);
    }

    private function studentToArray(Student $student): array
    {
        return [
            'id' => $student->id(),
            'name' => $student->name(),
            'birth_date' => $student->birthDate()->format('Y-m-d'),
            'age' => $student->age(),
            'cep' => $student->cep(),
            'address' => $student->address()
        ];
    }

    private function sendResponse($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function sendError(string $message, int $statusCode = 400): void
    {
        http_response_code($statusCode);
        echo json_encode([
            'error' => $message,
            'status_code' => $statusCode
        ], JSON_UNESCAPED_UNICODE);
    }
} 