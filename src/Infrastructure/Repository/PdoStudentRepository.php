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

    public function findById(int $id): ?Student
    {
        $sql = 'SELECT * FROM students WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $studentData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($studentData) {
            return new Student(
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date']),
                $studentData['cep'] ?? '',
                $studentData['address'] ?? ''
            );
        }
        
        return null;
    }

    public function findByName(string $name): array
    {
        $sql = 'SELECT * FROM students WHERE name LIKE ? ORDER BY name';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, '%' . $name . '%');
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

    public function findByCriteria(?int $id = null, ?string $name = null, ?string $cep = null): array
    {
        $conditions = [];
        $params = [];
        $paramIndex = 1;
        
        if ($id !== null) {
            $conditions[] = 'id = ?';
            $params[] = $id;
            $paramIndex++;
        }
        
        if ($name !== null && trim($name) !== '') {
            $conditions[] = 'name LIKE ?';
            $params[] = '%' . trim($name) . '%';
            $paramIndex++;
        }
        
        if ($cep !== null && trim($cep) !== '') {
            $conditions[] = 'cep LIKE ?';
            $params[] = '%' . trim($cep) . '%';
            $paramIndex++;
        }
        
        if (empty($conditions)) {
            return [];
        }
        
        $sql = 'SELECT * FROM students WHERE ' . implode(' AND ', $conditions) . ' ORDER BY name';
        $stmt = $this->connection->prepare($sql);
        
        foreach ($params as $index => $param) {
            $stmt->bindValue($index + 1, $param);
        }
        
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