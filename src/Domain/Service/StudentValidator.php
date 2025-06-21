<?php

namespace Alura\Pdo\Domain\Service;

use Alura\Pdo\Domain\Exception\StudentException;
use Alura\Pdo\Domain\Model\Student;

class StudentValidator
{
    public function validateStudent(Student $student): void
    {
        $this->validateName($student->name());
        $this->validateBirthDate($student->birthDate());
        $this->validateCep($student->cep());
        $this->validateAge($student->age());
    }

    public function validateName(string $name): void
    {
        $name = trim($name);
        
        if (strlen($name) < 2) {
            throw StudentException::invalidName($name);
        }

        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $name)) {
            throw new StudentException("Nome contém caracteres inválidos: '{$name}'");
        }
    }

    public function validateBirthDate(\DateTimeInterface $birthDate): void
    {
        $now = new \DateTimeImmutable();
        $minDate = $now->modify('-150 years');
        $maxDate = $now->modify('+1 day');

        if ($birthDate < $minDate || $birthDate > $maxDate) {
            throw new StudentException("Data de nascimento deve estar entre {$minDate->format('Y-m-d')} e {$maxDate->format('Y-m-d')}");
        }
    }

    public function validateCep(string $cep): void
    {
        if (empty($cep)) {
            return; // CEP é opcional
        }

        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            throw StudentException::invalidCep($cep);
        }

        if (!is_numeric($cep)) {
            throw new StudentException("CEP deve conter apenas números: '{$cep}'");
        }
    }

    public function validateAge(int $age): void
    {
        if ($age < 0 || $age > 150) {
            throw StudentException::invalidAge($age);
        }
    }

    public function validateId(?int $id): void
    {
        if ($id !== null && $id <= 0) {
            throw new StudentException("ID deve ser um número positivo: {$id}");
        }
    }
} 