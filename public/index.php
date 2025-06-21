<?php

$data = require_once 'bootstrap.php';
$message = $data['message'];
$error = $data['error'];
$students = $data['students'];
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
                    <div class="form-group">
                        <label for="cep">CEP:</label>
                        <div class="cep-group">
                            <input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9">
                            <button type="button" id="buscar_cep" class="btn btn-secondary">🔍 Buscar</button>
                        </div>
                        <small>Digite o CEP para buscar o endereço automaticamente</small>
                    </div>
                    <div class="form-group">
                        <label for="address">Endereço:</label>
                        <input type="text" id="address" name="address" placeholder="Endereço completo">
                        <small>Será preenchido automaticamente ao buscar o CEP</small>
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
                                <th>CEP</th>
                                <th>Endereço</th>
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
                                    <td><?= htmlspecialchars($student->cep()) ?: '-' ?></td>
                                    <td><?= htmlspecialchars($student->address()) ?: '-' ?></td>
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
        
        // Máscara para CEP
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });
        
        // Buscar CEP
        document.getElementById('buscar_cep').addEventListener('click', function() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            const addressField = document.getElementById('address');
            
            if (cep.length !== 8) {
                alert('Digite um CEP válido (8 dígitos)');
                return;
            }
            
            this.disabled = true;
            this.textContent = '🔍 Buscando...';
            
            const formData = new FormData();
            formData.append('action', 'buscar_cep');
            formData.append('cep', cep);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addressField.value = data.endereco;
                    alert('Endereço encontrado e preenchido automaticamente!');
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erro ao buscar CEP: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.textContent = '🔍 Buscar';
            });
        });
        
        // Auto-refresh após operações
        <?php if ($message || $error): ?>
        setTimeout(() => {
            window.location.href = window.location.pathname;
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>
