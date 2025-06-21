<?php

use Alura\Pdo\Infrastructure\Service\CepService;

require_once __DIR__ . '/../vendor/autoload.php';

$cepService = new CepService();

echo "=== Teste da Funcionalidade de CEP ===\n\n";

$ceps = [
    '01001-000', // São Paulo
    '20040-007', // Rio de Janeiro
    '40026-010', // Salvador
    '90020-100', // Porto Alegre
    '00000-000'  // CEP inválido
];

foreach ($ceps as $cep) {
    echo "Testando CEP: $cep\n";
    $resultado = $cepService->buscarEndereco($cep);
    
    if ($resultado) {
        echo "✅ Endereço encontrado:\n";
        echo "   CEP: {$resultado['cep']}\n";
        echo "   Logradouro: {$resultado['logradouro']}\n";
        echo "   Bairro: {$resultado['bairro']}\n";
        echo "   Cidade: {$resultado['localidade']}\n";
        echo "   UF: {$resultado['uf']}\n";
        echo "   Endereço completo: {$resultado['endereco_completo']}\n";
    } else {
        echo "❌ CEP não encontrado ou inválido\n";
    }
    echo "\n";
}

echo "=== Teste concluído ===\n"; 