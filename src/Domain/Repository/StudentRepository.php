<?php

namespace Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;

interface StudentRepository
{
    public function allStudents(): array;
    public function studentsBirthAt(\DateTimeInterface $birthDate): array;
    public function save(Student $student): bool;
    public function remove(Student $student): bool;
    public function findById(int $id): ?Student;
    public function findByName(string $name): array;
    public function findByCriteria(?int $id = null, ?string $name = null, ?string $cep = null): array;
}