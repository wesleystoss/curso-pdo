<?php

namespace Alura\Pdo\Infrastructure\Web;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;

class StudentController
{
    private StudentRepository $repository;
    
    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function handleRequest(): array
    {
        $message = '';
        $error = '';
        $students = [];
        
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
            'students' => $students
        ];
    }
    
    private function insertStudent(): array
    {
        try {
            $name = $_POST['name'] ?? '';
            $birthDate = $_POST['birth_date'] ?? '';
            
            if (empty($name) || empty($birthDate)) {
                return ['message' => '', 'error' => 'Nome e data de nascimento são obrigatórios!'];
            }
            
            $student = new Student(null, $name, new \DateTimeImmutable($birthDate));
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
}
