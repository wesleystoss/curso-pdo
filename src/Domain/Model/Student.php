<?php

namespace Alura\Pdo\Domain\Model;

class Student
{
    private ?int $id;
    private string $name;
    private \DateTimeInterface $birthDate;
    private string $cep;
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
