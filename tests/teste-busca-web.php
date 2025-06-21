<?php

echo "=== Teste da Funcionalidade de Busca Web ===\n\n";

$baseUrl = 'http://localhost:8000';

// Teste 1: Busca por nome
echo "1. Testando busca por nome 'João':\n";
$postData = http_build_query([
    'action' => 'search',
    'search_type' => 'name',
    'search_term' => 'João'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postData
    ]
]);

$response = file_get_contents($baseUrl, false, $context);

if (strpos($response, 'Resultados da Busca') !== false) {
    echo "   ✅ Resultados de busca encontrados na página\n";
    
    // Verificar se há alunos com "João" no nome
    if (strpos($response, 'João') !== false) {
        echo "   ✅ Alunos com 'João' encontrados\n";
    } else {
        echo "   ⚠️ Nenhum aluno com 'João' encontrado\n";
    }
} else {
    echo "   ❌ Resultados de busca não encontrados\n";
}

// Teste 2: Verificar se não há auto-refresh
if (strpos($response, 'setTimeout') !== false && strpos($response, 'search') !== false) {
    echo "   ⚠️ Auto-refresh ainda presente (pode ser para outras ações)\n";
} else {
    echo "   ✅ Auto-refresh removido para buscas\n";
}

echo "\n";

// Teste 3: Busca por ID
echo "2. Testando busca por ID '5':\n";
$postData = http_build_query([
    'action' => 'search',
    'search_type' => 'id',
    'search_term' => '5'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postData
    ]
]);

$response = file_get_contents($baseUrl, false, $context);

if (strpos($response, 'Resultados da Busca') !== false) {
    echo "   ✅ Resultados de busca encontrados na página\n";
    
    // Verificar se há aluno com ID 5
    if (strpos($response, 'value="5"') !== false) {
        echo "   ✅ Campo de busca mantém o valor '5'\n";
    } else {
        echo "   ⚠️ Campo de busca não manteve o valor\n";
    }
} else {
    echo "   ❌ Resultados de busca não encontrados\n";
}

echo "\n";

// Teste 4: Verificar se os valores são mantidos no formulário
echo "3. Verificando persistência dos valores do formulário:\n";
if (strpos($response, 'selected') !== false) {
    echo "   ✅ Tipo de busca selecionado é mantido\n";
} else {
    echo "   ⚠️ Tipo de busca não está sendo mantido\n";
}

if (strpos($response, 'value="5"') !== false) {
    echo "   ✅ Termo de busca é mantido no campo\n";
} else {
    echo "   ⚠️ Termo de busca não está sendo mantido\n";
}

echo "\n";

echo "=== Teste concluído ===\n";
echo "\nPara testar manualmente:\n";
echo "1. Acesse: http://localhost:8000\n";
echo "2. Clique na aba '🔍 Buscar Aluno'\n";
echo "3. Selecione 'Por Nome' e digite 'João'\n";
echo "4. Clique em '🔍 Buscar'\n";
echo "5. Verifique se os resultados permanecem visíveis\n";
echo "6. Verifique se os campos mantêm os valores preenchidos\n"; 