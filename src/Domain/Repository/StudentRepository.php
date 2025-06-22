<?php

namespace Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;

interface StudentRepository
{
    /**
     * @return array<int, Student>
     */
    public function allStudents(): array;

    /**
     * @param \DateTimeInterface $birthDate
     * @return array<int, Student>
     */
    public function studentsBirthAt(\DateTimeInterface $birthDate): array;

    public function save(Student $student): bool;

    public function remove(Student $student): bool;

    public function findById(int $id): ?Student;

    /**
     * @param string $name
     * @return array<int, Student>
     */
    public function findByName(string $name): array;

    /**
     * @param int|null $id
     * @param string|null $name
     * @param string|null $cep
     * @return array<int, Student>
     */
    public function findByCriteria(?int $id = null, ?string $name = null, ?string $cep = null): array;
}