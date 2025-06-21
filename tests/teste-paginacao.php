<?php

echo "=== Teste da Funcionalidade de Paginação ===\n\n";

$baseUrl = 'http://localhost:8000';

// Teste 1: Verificar página inicial
echo "1. Testando página inicial (página 1):\n";
$response = file_get_contents($baseUrl);

if (strpos($response, 'pagination') !== false) {
    echo "   ✅ Sistema de paginação encontrado\n";
} else {
    echo "   ❌ Sistema de paginação não encontrado\n";
}

// Verificar se há informações de paginação
if (strpos($response, 'Mostrando') !== false) {
    echo "   ✅ Informações de paginação exibidas\n";
} else {
    echo "   ⚠️ Informações de paginação não encontradas (pode ser normal se há poucos alunos)\n";
}

// Verificar se há botões de paginação
if (strpos($response, 'pagination-number') !== false) {
    echo "   ✅ Botões de paginação encontrados\n";
} else {
    echo "   ⚠️ Botões de paginação não encontrados (pode ser normal se há poucos alunos)\n";
}

echo "\n";

// Teste 2: Verificar página específica
echo "2. Testando página específica (página 2):\n";
$response = file_get_contents($baseUrl . '?page=2');

if (strpos($response, 'pagination-number active') !== false) {
    echo "   ✅ Página ativa destacada corretamente\n";
} else {
    echo "   ⚠️ Página ativa não destacada (pode ser normal se não há página 2)\n";
}

echo "\n";

// Teste 3: Verificar navegação
echo "3. Verificando navegação entre páginas:\n";

// Verificar botão "Anterior"
if (strpos($response, 'Anterior') !== false) {
    echo "   ✅ Botão 'Anterior' encontrado\n";
} else {
    echo "   ⚠️ Botão 'Anterior' não encontrado (pode ser normal na primeira página)\n";
}

// Verificar botão "Próxima"
if (strpos($response, 'Próxima') !== false) {
    echo "   ✅ Botão 'Próxima' encontrado\n";
} else {
    echo "   ⚠️ Botão 'Próxima' não encontrado (pode ser normal na última página)\n";
}

echo "\n";

// Teste 4: Verificar contagem de alunos
echo "4. Verificando contagem de alunos:\n";

// Extrair número total de alunos das estatísticas
if (preg_match('/<div class="stat-number">(\d+)<\/div>/', $response, $matches)) {
    $totalStudents = (int)$matches[1];
    echo "   ✅ Total de alunos: $totalStudents\n";
    
    if ($totalStudents > 3) {
        echo "   ✅ Há alunos suficientes para testar paginação\n";
    } else {
        echo "   ⚠️ Poucos alunos para testar paginação (mínimo 4 recomendado)\n";
    }
} else {
    echo "   ❌ Não foi possível extrair o total de alunos\n";
}

echo "\n";

// Teste 5: Verificar estrutura da tabela
echo "5. Verificando estrutura da tabela:\n";

$tableCount = substr_count($response, '<tr>');
if ($tableCount > 1) {
    echo "   ✅ Tabela encontrada com $tableCount linhas\n";
} else {
    echo "   ❌ Tabela não encontrada ou vazia\n";
}

// Verificar se há no máximo 3 registros + cabeçalho
$dataRows = $tableCount - 1; // Subtrair cabeçalho
if ($dataRows <= 3) {
    echo "   ✅ Número correto de registros por página (máximo 3)\n";
} else {
    echo "   ❌ Número incorreto de registros por página ($dataRows > 3)\n";
}

echo "\n";

// Teste 6: Verificar integração com busca
echo "6. Verificando integração com busca:\n";
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

$searchResponse = file_get_contents($baseUrl, false, $context);

if (strpos($searchResponse, 'Resultados da Busca') !== false) {
    echo "   ✅ Busca funciona com paginação\n";
} else {
    echo "   ❌ Busca não funciona com paginação\n";
}

// Verificar se paginação não aparece nos resultados de busca
if (strpos($searchResponse, 'pagination-number') === false) {
    echo "   ✅ Paginação não aparece nos resultados de busca (correto)\n";
} else {
    echo "   ⚠️ Paginação aparece nos resultados de busca\n";
}

echo "\n";

echo "=== Teste concluído ===\n";
echo "\nPara testar manualmente:\n";
echo "1. Acesse: http://localhost:8000\n";
echo "2. Verifique se há mais de 3 alunos cadastrados\n";
echo "3. Observe as informações de paginação acima da tabela\n";
echo "4. Use os botões de navegação para alternar entre páginas\n";
echo "5. Verifique se apenas 3 alunos são exibidos por página\n";
echo "6. Teste a busca e verifique se a paginação não interfere\n";
echo "7. Teste em dispositivos móveis para verificar responsividade\n";

// Sugestão para adicionar mais alunos se necessário
echo "\nSe há poucos alunos para testar, você pode adicionar mais usando:\n";
echo "php scripts/inserir-aluno.php\n"; 