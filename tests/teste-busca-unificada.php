<?php

echo "=== Teste da Funcionalidade de Busca Unificada ===\n\n";

$baseUrl = 'http://localhost:8000';

// Teste 1: Verificar se a busca atualiza a tabela principal
echo "1. Testando busca por nome 'João' (deve atualizar tabela principal):\n";
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

// Verificar se a tabela principal foi atualizada
if (strpos($response, 'Resultados da Busca') !== false) {
    echo "   ✅ Tabela principal atualizada com resultados da busca\n";
} else {
    echo "   ❌ Tabela principal não foi atualizada\n";
}

// Verificar se a aba de busca permanece ativa
if (strpos($response, 'tab-button active') !== false && strpos($response, 'data-tab="search"') !== false) {
    echo "   ✅ Aba de busca permanece ativa após a busca\n";
} else {
    echo "   ❌ Aba de busca não permanece ativa\n";
}

// Verificar se há apenas uma tabela
$tableCount = substr_count($response, '<table class="students-table">');
if ($tableCount === 1) {
    echo "   ✅ Apenas uma tabela é exibida (busca unificada)\n";
} else {
    echo "   ❌ Múltiplas tabelas encontradas ($tableCount)\n";
}

echo "\n";

// Teste 2: Verificar se o botão "Limpar Busca" aparece
echo "2. Verificando botão 'Limpar Busca':\n";
if (strpos($response, 'Limpar Busca') !== false) {
    echo "   ✅ Botão 'Limpar Busca' está presente\n";
} else {
    echo "   ❌ Botão 'Limpar Busca' não encontrado\n";
}

// Verificar se o link de limpar busca funciona
if (strpos($response, 'href="index.php"') !== false) {
    echo "   ✅ Link para limpar busca está correto\n";
} else {
    echo "   ❌ Link para limpar busca incorreto\n";
}

echo "\n";

// Teste 3: Verificar estatísticas atualizadas
echo "3. Verificando estatísticas atualizadas:\n";
if (strpos($response, 'Alunos Encontrados') !== false) {
    echo "   ✅ Estatísticas mostram 'Alunos Encontrados'\n";
} else {
    echo "   ❌ Estatísticas não foram atualizadas\n";
}

echo "\n";

// Teste 4: Verificar busca por ID
echo "4. Testando busca por ID '5':\n";
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
    echo "   ✅ Busca por ID atualiza tabela principal\n";
} else {
    echo "   ❌ Busca por ID não atualiza tabela principal\n";
}

// Verificar se o valor do campo é mantido
if (strpos($response, 'value="5"') !== false) {
    echo "   ✅ Campo de busca mantém o valor '5'\n";
} else {
    echo "   ❌ Campo de busca não manteve o valor\n";
}

echo "\n";

// Teste 5: Verificar busca sem resultados
echo "5. Testando busca sem resultados:\n";
$postData = http_build_query([
    'action' => 'search',
    'search_type' => 'name',
    'search_term' => 'NomeInexistente123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postData
    ]
]);

$response = file_get_contents($baseUrl, false, $context);

if (strpos($response, 'Nenhum aluno encontrado com os critérios informados') !== false) {
    echo "   ✅ Mensagem correta para busca sem resultados\n";
} else {
    echo "   ❌ Mensagem incorreta para busca sem resultados\n";
}

echo "\n";

echo "=== Teste concluído ===\n";
echo "\nPara testar manualmente:\n";
echo "1. Acesse: http://localhost:8000\n";
echo "2. Clique na aba '🔍 Buscar Aluno'\n";
echo "3. Selecione 'Por Nome' e digite 'João'\n";
echo "4. Clique em '🔍 Buscar'\n";
echo "5. Verifique se:\n";
echo "   - A tabela principal foi atualizada com os resultados\n";
echo "   - A aba de busca permanece ativa\n";
echo "   - O botão '🔄 Limpar Busca' aparece\n";
echo "   - As estatísticas mostram 'Alunos Encontrados'\n";
echo "6. Clique em '🔄 Limpar Busca' para voltar à lista completa\n"; 