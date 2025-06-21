<?php

require_once 'database-teste.php';

echo "=== LIMPEZA DO BANCO DE TESTE ===\n";

if (file_exists(TEST_DATABASE_PATH)) {
    echo "Removendo banco de teste: " . TEST_DATABASE_PATH . "\n";
    removeTestDatabase();
    echo "Banco de teste removido com sucesso!\n";
} else {
    echo "Banco de teste não existe.\n";
}

echo "=== FIM ===\n"; 