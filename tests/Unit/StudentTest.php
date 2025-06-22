<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Service\StudentValidator;
use Alura\Pdo\Domain\Exception\StudentException;

class StudentTest extends TestCase
{
    private StudentValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new StudentValidator();
    }

    public function testStudentCreationWithValidData(): void
    {
        $student = new Student(
            null,
            'João Silva',
            new \DateTimeImmutable('1990-01-01'),
            '12345678',
            'Rua das Flores, 123'
        );

        $this->assertNull($student->id());
        $this->assertEquals('João Silva', $student->name());
        $this->assertEquals('1990-01-01', $student->birthDate()->format('Y-m-d'));
        $this->assertEquals('12345678', $student->cep());
        $this->assertEquals('Rua das Flores, 123', $student->address());
        $this->assertGreaterThan(0, $student->age());
    }

    public function testStudentAgeCalculation(): void
    {
        $birthDate = new \DateTimeImmutable('1990-01-01');
        $student = new Student(null, 'Test', $birthDate);
        
        $expectedAge = (new \DateTimeImmutable())->diff($birthDate)->y;
        $this->assertEquals($expectedAge, $student->age());
    }

    public function testStudentNameChange(): void
    {
        $student = new Student(1, 'João', new \DateTimeImmutable('1990-01-01'));
        $student->changeName('João Silva');
        
        $this->assertEquals('João Silva', $student->name());
    }

    public function testStudentCepChange(): void
    {
        $student = new Student(1, 'João', new \DateTimeImmutable('1990-01-01'));
        $student->changeCep('87654321');
        
        $this->assertEquals('87654321', $student->cep());
    }

    public function testStudentAddressChange(): void
    {
        $student = new Student(1, 'João', new \DateTimeImmutable('1990-01-01'));
        $student->changeAddress('Nova Rua, 456');
        
        $this->assertEquals('Nova Rua, 456', $student->address());
    }

    public function testStudentIdDefinition(): void
    {
        $student = new Student(null, 'João', new \DateTimeImmutable('1990-01-01'));
        $student->defineId(123);
        
        $this->assertEquals(123, $student->id());
    }

    public function testValidatorWithValidStudent(): void
    {
        $student = new Student(
            null,
            'João Silva',
            new \DateTimeImmutable('1990-01-01'),
            '12345678',
            'Rua das Flores, 123'
        );

        $this->validator->validateStudent($student);
        $this->assertTrue(true); // Se chegou aqui, não houve exceção
    }

    public function testValidatorWithInvalidName(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("Nome inválido: 'J'. O nome deve ter pelo menos 2 caracteres.");
        
        $this->validator->validateName('J');
    }

    public function testValidatorWithEmptyName(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("Nome inválido: ''. O nome deve ter pelo menos 2 caracteres.");
        
        $this->validator->validateName('');
    }

    public function testValidatorWithInvalidNameCharacters(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("Nome contém caracteres inválidos: 'João123'");
        
        $this->validator->validateName('João123');
    }

    public function testValidatorWithInvalidBirthDate(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("Data de nascimento inválida: 'invalid-date'. Use o formato YYYY-MM-DD.");
        
        $this->validator->validateBirthDate(new \DateTimeImmutable('invalid-date'));
    }

    public function testValidatorWithFutureBirthDate(): void
    {
        $this->expectException(StudentException::class);
        
        $futureDate = new \DateTimeImmutable('+1 year');
        $this->validator->validateBirthDate($futureDate);
    }

    public function testValidatorWithInvalidCep(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("CEP inválido: '123'. O CEP deve ter 8 dígitos.");
        
        $this->validator->validateCep('123');
    }

    public function testValidatorWithNonNumericCep(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("CEP deve conter apenas números: '1234567a'");
        
        $this->validator->validateCep('1234567a');
    }

    public function testValidatorWithValidCep(): void
    {
        $this->validator->validateCep('12345678');
        $this->assertTrue(true); // Se chegou aqui, não houve exceção
    }

    public function testValidatorWithEmptyCep(): void
    {
        $this->validator->validateCep('');
        $this->assertTrue(true); // CEP vazio é válido (opcional)
    }

    public function testValidatorWithInvalidAge(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("Idade inválida: -5. A idade deve estar entre 0 e 150 anos.");
        
        $this->validator->validateAge(-5);
    }

    public function testValidatorWithInvalidId(): void
    {
        $this->expectException(StudentException::class);
        $this->expectExceptionMessage("ID deve ser um número positivo: -1");
        
        $this->validator->validateId(-1);
    }

    public function testValidatorWithValidId(): void
    {
        $this->validator->validateId(1);
        $this->assertTrue(true); // Se chegou aqui, não houve exceção
    }

    public function testValidatorWithNullId(): void
    {
        $this->validator->validateId(null);
        $this->assertTrue(true); // ID null é válido
    }
} 