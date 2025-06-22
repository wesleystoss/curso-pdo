<?php

namespace Alura\Pdo\Infrastructure\Web;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Service\CepService;

class StudentController
{
    private StudentRepository $repository;
    private CepService $cepService;
    private const ITEMS_PER_PAGE = 3;
    
    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
        $this->cepService = new CepService();
    }
    
    public function handleRequest(): array
    {
        $message = '';
        $error = '';
        $students = [];
        $searchResults = [];
        $isSearch = isset($_POST['action']) && $_POST['action'] === 'search';
        
        // Paginação
        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'insert':
                    $result = $this->insertStudent();
                    $message = $result['message'];
                    $error = $result['error'];
                    break;
                    
                case 'update':
                    $result = $this->updateStudent();
                    $message = $result['message'];
                    $error = $result['error'];
                    break;
                    
                case 'delete':
                    $result = $this->deleteStudent();
                    $message = $result['message'];
                    $error = $result['error'];
                    break;
                    
                case 'bulk_delete':
                    $result = $this->bulkDeleteStudents();
                    $message = $result['message'];
                    $error = $result['error'];
                    break;
                    
                case 'search':
                    $result = $this->searchStudents();
                    $searchResults = $result['results'];
                    $message = $result['message'];
                    $error = $result['error'];
                    break;
                    
                case 'buscar_cep':
                    $result = $this->buscarCep();
                    header('Content-Type: application/json');
                    echo json_encode($result);
                    exit;
            }
        }
        
        // Buscar alunos com paginação
        try {
            if ($isSearch && !empty($searchResults)) {
                // Para busca, usar todos os resultados sem paginação
                $students = $searchResults;
                $totalStudents = count($searchResults);
                $totalPages = 1;
                $currentPage = 1;
            } else {
                // Para lista completa, aplicar paginação
                $allStudents = $this->repository->allStudents();
                $totalStudents = count($allStudents);
                $totalPages = ceil($totalStudents / self::ITEMS_PER_PAGE);
                $currentPage = min($currentPage, $totalPages);
                
                // Calcular offset e limit
                $offset = ($currentPage - 1) * self::ITEMS_PER_PAGE;
                $students = array_slice($allStudents, $offset, self::ITEMS_PER_PAGE);
            }
        } catch (\Exception $e) {
            $error = 'Erro ao buscar alunos: ' . $e->getMessage();
            $totalStudents = 0;
            $totalPages = 1;
            $currentPage = 1;
        }
        
        return [
            'message' => $message,
            'error' => $error,
            'students' => $students,
            'searchResults' => $searchResults,
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'totalStudents' => $totalStudents,
                'itemsPerPage' => self::ITEMS_PER_PAGE,
                'hasNextPage' => $currentPage < $totalPages,
                'hasPreviousPage' => $currentPage > 1
            ]
        ];
    }
    
    /**
     * @return array{message: string, error: string}
     */
    private function insertStudent(): array
    {
        try {
            $name = $_POST['name'] ?? '';
            $birthDate = $_POST['birth_date'] ?? '';
            $cep = $_POST['cep'] ?? '';
            $address = $_POST['address'] ?? '';
            
            if (empty($name) || empty($birthDate)) {
                return ['message' => '', 'error' => 'Nome e data de nascimento são obrigatórios!'];
            }
            
            $student = new Student(null, $name, new \DateTimeImmutable($birthDate), $cep, $address);
            $this->repository->save($student);
            
            return ['message' => 'Aluno inserido com sucesso!', 'error' => ''];
        } catch (\Exception $e) {
            return ['message' => '', 'error' => 'Erro ao inserir aluno: ' . $e->getMessage()];
        }
    }
    
    /**
     * @return array{message: string, error: string}
     */
    private function updateStudent(): array
    {
        try {
            $id = (int)($_POST['id'] ?? 0);
            $name = $_POST['name'] ?? '';
            $birthDate = $_POST['birth_date'] ?? '';
            $cep = $_POST['cep'] ?? '';
            $address = $_POST['address'] ?? '';
            
            if ($id <= 0) {
                return ['message' => '', 'error' => 'ID inválido!'];
            }
            
            if (empty($name) || empty($birthDate)) {
                return ['message' => '', 'error' => 'Nome e data de nascimento são obrigatórios!'];
            }
            
            // Verificar se o aluno existe
            $existingStudent = $this->repository->findById($id);
            if (!$existingStudent) {
                return ['message' => '', 'error' => 'Aluno não encontrado!'];
            }
            
            $student = new Student($id, $name, new \DateTimeImmutable($birthDate), $cep, $address);
            $this->repository->save($student);
            
            return ['message' => 'Aluno atualizado com sucesso!', 'error' => ''];
        } catch (\Exception $e) {
            return ['message' => '', 'error' => 'Erro ao atualizar aluno: ' . $e->getMessage()];
        }
    }
    
    /**
     * @return array{message: string, error: string}
     */
    private function deleteStudent(): array
    {
        try {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $student = new Student($id, '', new \DateTimeImmutable());
                $this->repository->remove($student);
                return ['message' => 'Aluno excluído com sucesso!', 'error' => ''];
            }
            return ['message' => '', 'error' => 'ID inválido'];
        } catch (\Exception $e) {
            return ['message' => '', 'error' => 'Erro ao excluir aluno: ' . $e->getMessage()];
        }
    }
    
    /**
     * @return array{message: string, error: string}
     */
    private function bulkDeleteStudents(): array
    {
        try {
            $selectedIds = $_POST['selected_ids'] ?? [];
            
            if (empty($selectedIds)) {
                return ['message' => '', 'error' => 'Nenhum aluno selecionado para exclusão.'];
            }

            // Validar IDs
            $validIds = [];
            foreach ($selectedIds as $id) {
                $id = (int)$id;
                if ($id > 0) {
                    $validIds[] = $id;
                }
            }

            if (empty($validIds)) {
                return ['message' => '', 'error' => 'IDs inválidos fornecidos.'];
            }

            // Buscar alunos para verificar se existem
            $studentsToDelete = [];
            foreach ($validIds as $id) {
                $student = $this->repository->findById($id);
                if ($student) {
                    $studentsToDelete[] = $student;
                }
            }

            if (empty($studentsToDelete)) {
                return ['message' => '', 'error' => 'Nenhum aluno válido encontrado para exclusão.'];
            }

            // Excluir alunos
            $deletedCount = 0;
            foreach ($studentsToDelete as $student) {
                $this->repository->remove($student);
                $deletedCount++;
            }

            $message = $deletedCount === 1 
                ? '1 aluno excluído com sucesso!' 
                : "{$deletedCount} alunos excluídos com sucesso!";

            return ['message' => $message, 'error' => ''];
        } catch (\Exception $e) {
            return ['message' => '', 'error' => 'Erro ao excluir alunos: ' . $e->getMessage()];
        }
    }
    
    private function searchStudents(): array
    {
        try {
            $searchId = trim($_POST['search_id'] ?? '');
            $searchName = trim($_POST['search_name'] ?? '');
            $searchCep = trim($_POST['search_cep'] ?? '');
            
            // Converter ID para inteiro se fornecido
            $id = null;
            if (!empty($searchId)) {
                $id = (int)$searchId;
                if ($id <= 0) {
                    return [
                        'results' => [],
                        'message' => '',
                        'error' => 'ID deve ser um número positivo!'
                    ];
                }
            }
            
            // Verificar se pelo menos um critério foi fornecido
            if (empty($searchId) && empty($searchName) && empty($searchCep)) {
                return [
                    'results' => [],
                    'message' => '',
                    'error' => 'Informe pelo menos um critério de busca (ID, Nome ou CEP)!'
                ];
            }
            
            $results = $this->repository->findByCriteria($id, $searchName, $searchCep);
            
            $message = count($results) > 0 
                ? 'Busca realizada com sucesso! ' . count($results) . ' aluno(s) encontrado(s).'
                : 'Nenhum aluno encontrado com os critérios informados.';
            
            return [
                'results' => $results,
                'message' => $message,
                'error' => ''
            ];
            
        } catch (\Exception $e) {
            return [
                'results' => [],
                'message' => '',
                'error' => 'Erro ao buscar alunos: ' . $e->getMessage()
            ];
        }
    }
    
    private function buscarCep(): array
    {
        $cep = $_POST['cep'] ?? '';
        
        if (empty($cep)) {
            return ['success' => false, 'message' => 'CEP não informado'];
        }
        
        $endereco = $this->cepService->buscarEndereco($cep);
        
        if ($endereco) {
            return [
                'success' => true,
                'endereco' => $endereco['endereco_completo'],
                'dados' => $endereco
            ];
        } else {
            return ['success' => false, 'message' => 'CEP não encontrado'];
        }
    }
}
