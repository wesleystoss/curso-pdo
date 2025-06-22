<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistema de Gerenciamento de Alunos' ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <meta name="description" content="Sistema profissional de gerenciamento de alunos com PDO e Clean Architecture">
    <meta name="keywords" content="PHP, PDO, Clean Architecture, Student Management, Sistema de Alunos">
    <meta name="author" content="Desenvolvedor">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🎓 <?= $pageTitle ?? 'Sistema de Gerenciamento de Alunos' ?></h1>
            <p>Sistema profissional desenvolvido com PHP, PDO e Clean Architecture</p>
        </header>
        
        <nav class="navigation">
            <div class="nav-container">
                <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                    🏠 Início
                </a>
                <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
                    📊 Dashboard
                </a>
                <a href="admin.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'admin.php' ? 'active' : '' ?>">
                    ⚙️ Administração
                </a>
                <a href="api.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'api.php' ? 'active' : '' ?>">
                    🔌 API
                </a>
            </div>
        </nav>
        
        <main class="content"> 