<?php

namespace Alura\Pdo\Domain\Exception;

class StudentException extends \Exception
{
    public static function invalidName(string $name): self
    {
        return new self("Nome inválido: '{$name}'. O nome deve ter pelo menos 2 caracteres.");
    }

    public static function invalidBirthDate(string $date): self
    {
        return new self("Data de nascimento inválida: '{$date}'. Use o formato YYYY-MM-DD.");
    }

    public static function invalidCep(string $cep): self
    {
        return new self("CEP inválido: '{$cep}'. O CEP deve ter 8 dígitos.");
    }

    public static function studentNotFound(int $id): self
    {
        return new self("Aluno com ID {$id} não encontrado.");
    }

    public static function studentAlreadyExists(string $name): self
    {
        return new self("Já existe um aluno com o nome '{$name}'.");
    }

    public static function invalidAge(int $age): self
    {
        return new self("Idade inválida: {$age}. A idade deve estar entre 0 e 150 anos.");
    }
} 