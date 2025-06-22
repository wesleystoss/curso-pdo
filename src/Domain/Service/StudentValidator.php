<?php

namespace Alura\Pdo\Domain\Service;

use Alura\Pdo\Domain\Model\Student;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StudentValidator
{
    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }

    public function validateStudent(Student $student): array
    {
        $violations = $this->validator->validate($student);
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }

    public function validateStudentData(array $data): array
    {
        $errors = [];

        // Validar nome
        $nameViolations = $this->validator->validate($data['name'] ?? '', [
            new Assert\NotBlank(['message' => 'Nome é obrigatório']),
            new Assert\Length([
                'min' => 2,
                'max' => 100,
                'minMessage' => 'Nome deve ter pelo menos {{ limit }} caracteres',
                'maxMessage' => 'Nome não pode ter mais que {{ limit }} caracteres'
            ]),
            new Assert\Regex([
                'pattern' => '/^[a-zA-ZÀ-ÿ\s]+$/',
                'message' => 'Nome deve conter apenas letras e espaços'
            ])
        ]);

        foreach ($nameViolations as $violation) {
            $errors['name'][] = $violation->getMessage();
        }

        // Validar data de nascimento
        $birthDate = $data['birth_date'] ?? '';
        $birthDateViolations = $this->validator->validate($birthDate, [
            new Assert\NotBlank(['message' => 'Data de nascimento é obrigatória']),
            new Assert\Date(['message' => 'Data de nascimento deve ser uma data válida']),
            new Assert\LessThan([
                'value' => new \DateTime(),
                'message' => 'Data de nascimento não pode ser no futuro'
            ]),
            new Assert\GreaterThan([
                'value' => new \DateTime('1900-01-01'),
                'message' => 'Data de nascimento deve ser após 1900'
            ])
        ]);

        foreach ($birthDateViolations as $violation) {
            $errors['birth_date'][] = $violation->getMessage();
        }

        // Validar CEP (se fornecido)
        if (!empty($data['cep'])) {
            $cepViolations = $this->validator->validate($data['cep'], [
                new Assert\Regex([
                    'pattern' => '/^\d{5}-?\d{3}$/',
                    'message' => 'CEP deve estar no formato 00000-000 ou 00000000'
                ])
            ]);

            foreach ($cepViolations as $violation) {
                $errors['cep'][] = $violation->getMessage();
            }
        }

        // Validar endereço (se fornecido)
        if (!empty($data['address'])) {
            $addressViolations = $this->validator->validate($data['address'], [
                new Assert\Length([
                    'max' => 255,
                    'maxMessage' => 'Endereço não pode ter mais que {{ limit }} caracteres'
                ])
            ]);

            foreach ($addressViolations as $violation) {
                $errors['address'][] = $violation->getMessage();
            }
        }

        return $errors;
    }

    public function validateSearchCriteria(array $criteria): array
    {
        $errors = [];

        // Verificar se pelo menos um critério foi fornecido
        $hasCriteria = false;
        foreach (['id', 'name', 'cep'] as $field) {
            if (!empty($criteria[$field])) {
                $hasCriteria = true;
                break;
            }
        }

        if (!$hasCriteria) {
            $errors['general'][] = 'Pelo menos um critério de busca deve ser fornecido';
        }

        // Validar ID se fornecido
        if (!empty($criteria['id'])) {
            $idViolations = $this->validator->validate($criteria['id'], [
                new Assert\Type([
                    'type' => 'numeric',
                    'message' => 'ID deve ser um número'
                ]),
                new Assert\Positive([
                    'message' => 'ID deve ser um número positivo'
                ])
            ]);

            foreach ($idViolations as $violation) {
                $errors['id'][] = $violation->getMessage();
            }
        }

        // Validar nome se fornecido
        if (!empty($criteria['name'])) {
            $nameViolations = $this->validator->validate($criteria['name'], [
                new Assert\Length([
                    'min' => 2,
                    'max' => 50,
                    'minMessage' => 'Nome deve ter pelo menos {{ limit }} caracteres',
                    'maxMessage' => 'Nome não pode ter mais que {{ limit }} caracteres'
                ])
            ]);

            foreach ($nameViolations as $violation) {
                $errors['name'][] = $violation->getMessage();
            }
        }

        // Validar CEP se fornecido
        if (!empty($criteria['cep'])) {
            $cepViolations = $this->validator->validate($criteria['cep'], [
                new Assert\Regex([
                    'pattern' => '/^\d{5}-?\d{3}$/',
                    'message' => 'CEP deve estar no formato 00000-000 ou 00000000'
                ])
            ]);

            foreach ($cepViolations as $violation) {
                $errors['cep'][] = $violation->getMessage();
            }
        }

        return $errors;
    }

    public function validateBulkDelete(array $studentIds): array
    {
        $errors = [];

        if (empty($studentIds)) {
            $errors['general'][] = 'Nenhum aluno selecionado para exclusão';
            return $errors;
        }

        if (count($studentIds) > 100) {
            $errors['general'][] = 'Não é possível excluir mais de 100 alunos de uma vez';
        }

        foreach ($studentIds as $index => $id) {
            $idViolations = $this->validator->validate($id, [
                new Assert\Type([
                    'type' => 'numeric',
                    'message' => 'ID deve ser um número'
                ]),
                new Assert\Positive([
                    'message' => 'ID deve ser um número positivo'
                ])
            ]);

            foreach ($idViolations as $violation) {
                $errors['ids'][$index][] = $violation->getMessage();
            }
        }

        return $errors;
    }

    public function sanitizeInput(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remover espaços em branco no início e fim
                $value = trim($value);
                
                // Converter caracteres especiais
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                
                // Remover caracteres de controle
                $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
            }
            
            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    public function formatCep(string $cep): string
    {
        // Remover caracteres não numéricos
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        // Formatar como 00000-000
        if (strlen($cep) === 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5);
        }
        
        return $cep;
    }

    public function isValidAge(\DateTimeInterface $birthDate): bool
    {
        $age = $birthDate->diff(new \DateTimeImmutable())->y;
        return $age >= 0 && $age <= 120;
    }

    public function getAgeGroup(\DateTimeInterface $birthDate): string
    {
        $age = $birthDate->diff(new \DateTimeImmutable())->y;
        
        if ($age < 18) {
            return 'menor';
        } elseif ($age < 30) {
            return 'jovem';
        } elseif ($age < 60) {
            return 'adulto';
        } else {
            return 'idoso';
        }
    }

    public function validateId(?int $id): void
    {
        if ($id !== null && $id <= 0) {
            throw new \Alura\Pdo\Domain\Exception\StudentException("ID deve ser um número positivo: {$id}");
        }
    }

    public function validateName(string $name): void
    {
        if (strlen($name) < 2) {
            throw new \Alura\Pdo\Domain\Exception\StudentException("Nome inválido: '{$name}'. O nome deve ter pelo menos 2 caracteres.");
        }
        
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $name)) {
            throw new \Alura\Pdo\Domain\Exception\StudentException("Nome contém caracteres inválidos: '{$name}'");
        }
    }

    public function validateBirthDate(\DateTimeInterface $birthDate): void
    {
        $now = new \DateTimeImmutable();
        if ($birthDate > $now) {
            throw new \Alura\Pdo\Domain\Exception\StudentException("Data de nascimento não pode ser no futuro");
        }
    }

    public function validateCep(string $cep): void
    {
        if (empty($cep)) {
            return; // CEP é opcional
        }
        
        if (strlen($cep) !== 8) {
            throw new \Alura\Pdo\Domain\Exception\StudentException("CEP inválido: '{$cep}'. O CEP deve ter 8 dígitos.");
        }
        
        if (!ctype_digit($cep)) {
            throw new \Alura\Pdo\Domain\Exception\StudentException("CEP deve conter apenas números: '{$cep}'");
        }
    }

    public function validateAge(int $age): void
    {
        if ($age < 0 || $age > 150) {
            throw new \Alura\Pdo\Domain\Exception\StudentException("Idade inválida: {$age}. A idade deve estar entre 0 e 150 anos.");
        }
    }
} 