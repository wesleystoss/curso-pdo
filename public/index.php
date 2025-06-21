<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

$connection = ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($connection);

$message = '';
$error = '';

// Processar formulário de inserção
if ($_POST['action'] ?? '' === 'insert') {
    try {
        $name = $_POST['name'] ?? '';
        $birthDate = $_POST['birth_date'] ?? '';
        
        if (empty($name) || empty($birthDate)) {
            $error = 'Nome e data de nascimento são obrigatórios!';
        } else {
            $student = new Student(null, $name, new DateTimeImmutable($birthDate));
            $repository->save($student);
            $message = 'Aluno inserido com sucesso!';
        }
    } catch (Exception $e) {
        $error = 'Erro ao inserir aluno: ' . $e->getMessage();
    }
}

// Processar exclusão
if ($_POST['action'] ?? '' === 'delete') {
    try {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $student = new Student($id, '', new DateTimeImmutable());
            $repository->remove($student);
            $message = 'Aluno excluído com sucesso!';
        }
    } catch (Exception $e) {
        $error = 'Erro ao excluir aluno: ' . $e->getMessage();
    }
}

// Buscar todos os alunos
try {
    $students = $repository->allStudents();
} catch (Exception $e) {
    $error = 'Erro ao buscar alunos: ' . $e->getMessage();
    $students = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Alunos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Gerenciador de Alunos</h1>
            <p>Sistema de gerenciamento de estudantes com PDO</p>
        </div>
        
        <div class="content">
            <?php if ($message): ?>
                <div class="message success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?= count($students) ?></div>
                    <div class="stat-label">Total de Alunos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count(array_filter($students, fn($s) => $s->age() >= 18)) ?></div>
                    <div class="stat-label">Alunos Maiores de 18</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count(array_filter($students, fn($s) => $s->age() < 18)) ?></div>
                    <div class="stat-label">Alunos Menores de 18</div>
                </div>
            </div>
            
            <div class="section">
                <h2>➕ Inserir Novo Aluno</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="insert">
                    <div class="form-group">
                        <label for="name">Nome do Aluno:</label>
                        <input type="text" id="name" name="name" required placeholder="Digite o nome completo">
                    </div>
                    <div class="form-group">
                        <label for="birth_date">Data de Nascimento:</label>
                        <input type="date" id="birth_date" name="birth_date" required>
                    </div>
                    <button type="submit" class="btn">Inserir Aluno</button>
                </form>
            </div>
            
            <div class="section">
                <h2>📋 Lista de Alunos</h2>
                <?php if (empty($students)): ?>
                    <p style="text-align: center; color: #666; padding: 40px;">Nenhum aluno cadastrado ainda.</p>
                <?php else: ?>
                    <table class="students-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Data de Nascimento</th>
                                <th>Idade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= $student->id() ?></td>
                                    <td><?= htmlspecialchars($student->name()) ?></td>
                                    <td><?= $student->birthDate()->format('d/m/Y') ?></td>
                                    <td><?= $student->age() ?> anos</td>
                                    <td>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este aluno?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $student->id() ?>">
                                            <button type="submit" class="btn btn-danger btn-small">🗑️ Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Definir data máxima como hoje para o campo de data de nascimento
        document.getElementById('birth_date').max = new Date().toISOString().split('T')[0];
        
        // Auto-refresh após operações
        <?php if ($message || $error): ?>
        setTimeout(() => {
            window.location.href = window.location.pathname;
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html> 