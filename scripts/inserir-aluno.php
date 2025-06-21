<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;
use Alura\Pdo\Infrastructure\Service\CepService;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);
$repository = new PdoStudentRepository($pdo);
$cepService = new CepService();

echo "Digite o nome do aluno: ";
$name = trim(fgets(STDIN));
echo "Digite a data de nascimento (formato YYYY-MM-DD): ";
$birthDateInput = trim(fgets(STDIN));
echo "Digite o CEP (opcional): ";
$cep = trim(fgets(STDIN));

$address = '';
if (!empty($cep)) {
    echo "Buscando endereço para o CEP: $cep" . PHP_EOL;
    $endereco = $cepService->buscarEndereco($cep);
    
    if ($endereco) {
        $address = $endereco['endereco_completo'];
        echo "Endereço encontrado: $address" . PHP_EOL;
    } else {
        echo "CEP não encontrado. Digite o endereço manualmente: ";
        $address = trim(fgets(STDIN));
    }
} else {
    echo "Digite o endereço (opcional): ";
    $address = trim(fgets(STDIN));
}

try {
    $birthDate = new \DateTimeImmutable($birthDateInput);
} catch (\Exception $e) {
    echo "Data inválida! Use o formato YYYY-MM-DD" . PHP_EOL;
    exit(1);
}

$student = new Student(
    id: null,
    name: $name,
    birthDate: $birthDate,
    cep: $cep,
    address: $address
);

if ($repository->save($student)) {
    echo "Aluno incluído" . PHP_EOL;
} else {
    echo "Erro ao incluir aluno" . PHP_EOL;
}