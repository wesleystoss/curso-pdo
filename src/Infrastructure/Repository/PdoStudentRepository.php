<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function allStudents(): array
    {
        $sql = 'SELECT * FROM students';
        $stmt = $this->connection->query($sql);
        $studentDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $studentList = [];
        foreach ($studentDataList as $studentData) {
            $studentList[] = new Student(
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date']),
                $studentData['cep'] ?? '',
                $studentData['address'] ?? ''
            );
        }
        
        return $studentList;
    }

    public function studentsBirthAt(\DateTimeInterface $birthDate): array
    {
        $sql = 'SELECT * FROM students WHERE birth_date = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $birthDate->format('Y-m-d'));
        $stmt->execute();
        $studentDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $studentList = [];
        foreach ($studentDataList as $studentData) {
            $studentList[] = new Student(
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date']),
                $studentData['cep'] ?? '',
                $studentData['address'] ?? ''
            );
        }
        
        return $studentList;
    }

    public function save(Student $student): bool
    {
        if ($student->id() === null) {
            return $this->insert($student);
        }

        return $this->update($student);
    }

    public function remove(Student $student): bool
    {
        $stm = $this->connection->prepare('DELETE FROM students WHERE id = ?');
        $stm->bindValue(1, $student->id(), PDO::PARAM_INT);
        
        return $stm->execute();
    }

    private function insert(Student $student): bool
    {
        $sql = 'INSERT INTO students (name, birth_date, cep, address) VALUES (?, ?, ?, ?)';
        $stmt = $this->connection->prepare($sql);
        $success = $stmt->execute([
            $student->name(),
            $student->birthDate()->format('Y-m-d'),
            $student->cep(),
            $student->address()
        ]);

        if ($success) {
            $student->defineId($this->connection->lastInsertId());
        }

        return $success;
    }

    private function update(Student $student): bool
    {
        $sql = 'UPDATE students SET name = ?, birth_date = ?, cep = ?, address = ? WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $student->name());
        $stmt->bindValue(2, $student->birthDate()->format('Y-m-d'));
        $stmt->bindValue(3, $student->cep());
        $stmt->bindValue(4, $student->address());
        $stmt->bindValue(5, $student->id(), PDO::PARAM_INT);

        return $stmt->execute();
    }
}