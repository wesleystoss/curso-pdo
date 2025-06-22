<?php

namespace Alura\Pdo\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\Callback("validateStudent")
 */
class Student
{
    private ?int $id;
    
    /**
     * @Assert\NotBlank(message="Nome é obrigatório")
     * @Assert\Length(min=2, max=100, minMessage="Nome deve ter pelo menos {{ limit }} caracteres", maxMessage="Nome não pode ter mais que {{ limit }} caracteres")
     * @Assert\Regex(pattern="/^[a-zA-ZÀ-ÿ\s]+$/", message="Nome deve conter apenas letras e espaços")
     */
    private string $name;
    
    /**
     * @Assert\NotNull(message="Data de nascimento é obrigatória")
     * @Assert\LessThan(value="today", message="Data de nascimento não pode ser no futuro")
     * @Assert\GreaterThan(value="1900-01-01", message="Data de nascimento deve ser após 1900")
     */
    private \DateTimeInterface $birthDate;
    
    /**
     * @Assert\Regex(pattern="/^\d{5}-?\d{3}$/", message="CEP deve estar no formato 00000-000 ou 00000000")
     */
    private string $cep;
    
    /**
     * @Assert\Length(max=255, maxMessage="Endereço não pode ter mais que {{ limit }} caracteres")
     */
    private string $address;

    public function __construct(?int $id, string $name, \DateTimeInterface $birthDate, string $cep = '', string $address = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthDate = $birthDate;
        $this->cep = $cep;
        $this->address = $address;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function birthDate(): \DateTimeInterface
    {
        return $this->birthDate;
    }

    public function cep(): string
    {
        return $this->cep;
    }

    public function changeCep(string $cep): void
    {
        $this->cep = $cep;
    }

    public function address(): string
    {
        return $this->address;
    }

    public function changeAddress(string $address): void
    {
        $this->address = $address;
    }

    public function age(): int
    {
        return $this->birthDate
            ->diff(new \DateTimeImmutable())
            ->y;
    }

    public function defineId(int $id): void
    {
        $this->id = $id;
    }
}
