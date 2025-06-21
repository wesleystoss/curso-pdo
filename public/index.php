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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 40px;
            padding: 25px;
            border-radius: 10px;
            background: #f8f9fa;
            border-left: 5px solid #667eea;
        }
        
        .section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, input[type="date"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        }
        
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .students-table th,
        .students-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .students-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
        }
        
        .students-table tr:hover {
            background: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-small {
            padding: 8px 15px;
            font-size: 14px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
    </style>
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