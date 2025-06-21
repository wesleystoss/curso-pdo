<?php

namespace Alura\Pdo\Infrastructure\Web;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Service\CepService;

class StudentController
{
    private StudentRepository $repository;
    private CepService $cepService;
    
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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'insert':
                    $result = $this->insertStudent();
                    $message = $result['message'];
                    $error = $result['error'];
                    break;
                    
                case 'delete':
                    $result = $this->deleteStudent();
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
        
        // Buscar alunos
        try {
            $students = $this->repository->allStudents();
        } catch (\Exception $e) {
            $error = 'Erro ao buscar alunos: ' . $e->getMessage();
        }
        
        return [
            'message' => $message,
            'error' => $error,
            'students' => $students,
            'searchResults' => $searchResults
        ];
    }
    
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
    
    private function searchStudents(): array
    {
        try {
            $searchType = $_POST['search_type'] ?? '';
            $searchTerm = trim($_POST['search_term'] ?? '');
            
            if (empty($searchType) || empty($searchTerm)) {
                return [
                    'results' => [],
                    'message' => '',
                    'error' => 'Tipo de busca e termo são obrigatórios!'
                ];
            }
            
            $results = [];
            
            if ($searchType === 'id') {
                $id = (int)$searchTerm;
                if ($id > 0) {
                    $student = $this->repository->findById($id);
                    if ($student) {
                        $results[] = $student;
                    }
                }
            } elseif ($searchType === 'name') {
                $results = $this->repository->findByName($searchTerm);
            }
            
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
