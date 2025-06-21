<?php

$data = require_once 'bootstrap.php';
$message = $data['message'];
$error = $data['error'];
$students = $data['students'];
$searchResults = $data['searchResults'] ?? [];
$pagination = $data['pagination'] ?? [];
$isSearch = isset($_POST['action']) && $_POST['action'] === 'search';

// Se foi uma busca, usar os resultados da busca na tabela principal
if ($isSearch && !empty($searchResults)) {
    $students = $searchResults;
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
            <?php if ($message && !$isSearch): ?>
                <div class="message success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <?php if ($error && !$isSearch): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?= $pagination['totalStudents'] ?? count($students) ?></div>
                    <div class="stat-label"><?= $isSearch ? 'Alunos Encontrados' : 'Total de Alunos' ?></div>
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
                <div class="tabs">
                    <button class="tab-button <?= !$isSearch ? 'active' : '' ?>" data-tab="insert">➕ Inserir Novo Aluno</button>
                    <button class="tab-button <?= $isSearch ? 'active' : '' ?>" data-tab="search">🔍 Buscar Aluno</button>
                </div>
                
                <!-- Aba de Inserir Aluno -->
                <div class="tab-content <?= !$isSearch ? 'active' : '' ?>" id="insert-tab">
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
                
                <!-- Aba de Buscar Aluno -->
                <div class="tab-content <?= $isSearch ? 'active' : '' ?>" id="search-tab">
                    <h2>🔍 Buscar Aluno</h2>
                    
                    <?php if ($isSearch && $message): ?>
                        <div class="message success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($isSearch && $error): ?>
                        <div class="message error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" id="search-form">
                        <input type="hidden" name="action" value="search">
                        <div class="form-group">
                            <label for="search_type">Tipo de Busca:</label>
                            <select id="search_type" name="search_type" required>
                                <option value="">Selecione o tipo de busca</option>
                                <option value="name" <?= (isset($_POST['search_type']) && $_POST['search_type'] === 'name') ? 'selected' : '' ?>>Por Nome</option>
                                <option value="id" <?= (isset($_POST['search_type']) && $_POST['search_type'] === 'id') ? 'selected' : '' ?>>Por ID</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="search_term">Termo de Busca:</label>
                            <input type="text" id="search_term" name="search_term" 
                                   value="<?= htmlspecialchars($_POST['search_term'] ?? '') ?>" 
                                   placeholder="Digite o nome ou ID do aluno" required>
                        </div>
                        <div class="search-buttons">
                            <button type="submit" class="btn">🔍 Buscar</button>
                            <?php if ($isSearch): ?>
                                <a href="index.php" class="btn btn-secondary">🔄 Limpar Busca</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="section">
                <h2>📋 Lista de Alunos <?= $isSearch ? '<span class="search-indicator">(Resultados da Busca)</span>' : '' ?></h2>
                
                <?php if (!empty($students)): ?>
                    <!-- Controles de seleção múltipla -->
                    <div class="bulk-actions">
                        <div class="bulk-controls">
                            <label class="checkbox-container">
                                <input type="checkbox" id="select-all">
                                <span class="checkmark"></span>
                                Selecionar Todos
                            </label>
                            <button type="button" id="delete-selected" class="btn btn-danger" disabled>
                                🗑️ Excluir Selecionados (<span id="selected-count">0</span>)
                            </button>
                        </div>
                    </div>
                    
                    <!-- Informações de paginação -->
                    <?php if (!$isSearch && $pagination['totalPages'] > 1): ?>
                        <div class="pagination-info">
                            <p>
                                Mostrando <?= (($pagination['currentPage'] - 1) * $pagination['itemsPerPage']) + 1 ?> 
                                a <?= min($pagination['currentPage'] * $pagination['itemsPerPage'], $pagination['totalStudents']) ?> 
                                de <?= $pagination['totalStudents'] ?> alunos
                                (Página <?= $pagination['currentPage'] ?> de <?= $pagination['totalPages'] ?>)
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="bulk-delete-form">
                        <input type="hidden" name="action" value="bulk_delete">
                        <table class="students-table">
                            <thead>
                                <tr>
                                    <th class="checkbox-header">
                                        <label class="checkbox-container">
                                            <input type="checkbox" id="select-all-header">
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
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
                                        <td class="checkbox-cell">
                                            <label class="checkbox-container">
                                                <input type="checkbox" name="selected_ids[]" value="<?= $student->id() ?>" class="student-checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td><?= $student->id() ?></td>
                                        <td><?= htmlspecialchars($student->name()) ?></td>
                                        <td><?= $student->birthDate()->format('d/m/Y') ?></td>
                                        <td><?= $student->age() ?> anos</td>
                                        <td><?= htmlspecialchars($student->cep()) ?: '-' ?></td>
                                        <td><?= htmlspecialchars($student->address()) ?: '-' ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-primary btn-small" 
                                                        onclick="openEditModal(<?= $student->id() ?>, '<?= htmlspecialchars($student->name()) ?>', '<?= $student->birthDate()->format('Y-m-d') ?>', '<?= htmlspecialchars($student->cep()) ?>', '<?= htmlspecialchars($student->address()) ?>')">
                                                    ✏️ Editar
                                                </button>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este aluno?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $student->id() ?>">
                                                    <button type="submit" class="btn btn-danger btn-small">🗑️ Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                    
                    <!-- Paginação -->
                    <?php if (!$isSearch && $pagination['totalPages'] > 1): ?>
                        <div class="pagination">
                            <?php if ($pagination['hasPreviousPage']): ?>
                                <a href="?page=<?= $pagination['currentPage'] - 1 ?>" class="btn btn-secondary">← Anterior</a>
                            <?php endif; ?>
                            
                            <div class="pagination-numbers">
                                <?php
                                $startPage = max(1, $pagination['currentPage'] - 2);
                                $endPage = min($pagination['totalPages'], $pagination['currentPage'] + 2);
                                
                                if ($startPage > 1): ?>
                                    <a href="?page=1" class="pagination-number">1</a>
                                    <?php if ($startPage > 2): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="?page=<?= $i ?>" 
                                       class="pagination-number <?= $i === $pagination['currentPage'] ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($endPage < $pagination['totalPages']): ?>
                                    <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                    <a href="?page=<?= $pagination['totalPages'] ?>" class="pagination-number">
                                        <?= $pagination['totalPages'] ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($pagination['hasNextPage']): ?>
                                <a href="?page=<?= $pagination['currentPage'] + 1 ?>" class="btn btn-secondary">Próxima →</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 40px;">
                        <?= $isSearch ? 'Nenhum aluno encontrado com os critérios informados.' : 'Nenhum aluno cadastrado ainda.' ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Modal de Edição -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Aluno</h2>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label for="edit_name">Nome do Aluno:</label>
                    <input type="text" id="edit_name" name="name" required placeholder="Digite o nome completo">
                </div>
                <div class="form-group">
                    <label for="edit_birth_date">Data de Nascimento:</label>
                    <input type="date" id="edit_birth_date" name="birth_date" required>
                </div>
                <div class="form-group">
                    <label for="edit_cep">CEP:</label>
                    <div class="cep-group">
                        <input type="text" id="edit_cep" name="cep" placeholder="00000-000" maxlength="9">
                        <button type="button" id="edit_buscar_cep" class="btn btn-secondary">🔍 Buscar</button>
                    </div>
                    <small>Digite o CEP para buscar o endereço automaticamente</small>
                </div>
                <div class="form-group">
                    <label for="edit_address">Endereço:</label>
                    <input type="text" id="edit_address" name="address" placeholder="Endereço completo">
                    <small>Será preenchido automaticamente ao buscar o CEP</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Definir data máxima como hoje para o campo de data de nascimento
        document.getElementById('birth_date').max = new Date().toISOString().split('T')[0];
        
        // Sistema de abas
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                
                // Remove active de todos os botões e conteúdos
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Adiciona active ao botão clicado e conteúdo correspondente
                button.classList.add('active');
                document.getElementById(targetTab + '-tab').classList.add('active');
            });
        });
        
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
        
        // Validação do formulário de busca
        document.getElementById('search_type').addEventListener('change', function() {
            const searchTerm = document.getElementById('search_term');
            const searchType = this.value;
            
            if (searchType === 'id') {
                searchTerm.placeholder = 'Digite o ID do aluno (número)';
                searchTerm.type = 'number';
                searchTerm.min = '1';
            } else if (searchType === 'name') {
                searchTerm.placeholder = 'Digite o nome do aluno';
                searchTerm.type = 'text';
                searchTerm.removeAttribute('min');
            }
        });
        
        // Sistema de seleção múltipla
        const selectAllCheckbox = document.getElementById('select-all');
        const selectAllHeaderCheckbox = document.getElementById('select-all-header');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const deleteSelectedBtn = document.getElementById('delete-selected');
        const selectedCountSpan = document.getElementById('selected-count');
        
        // Função para atualizar contador e estado do botão
        function updateSelectionState() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const totalBoxes = studentCheckboxes.length;
            
            selectedCountSpan.textContent = checkedBoxes.length;
            deleteSelectedBtn.disabled = checkedBoxes.length === 0;
            
            // Atualizar estado dos checkboxes "selecionar todos"
            const allChecked = checkedBoxes.length === totalBoxes && totalBoxes > 0;
            selectAllCheckbox.checked = allChecked;
            selectAllHeaderCheckbox.checked = allChecked;
        }
        
        // Event listener para checkboxes individuais
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionState);
        });
        
        // Event listener para "selecionar todos" (fora da tabela)
        selectAllCheckbox.addEventListener('change', function() {
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionState();
        });
        
        // Event listener para "selecionar todos" (no cabeçalho da tabela)
        selectAllHeaderCheckbox.addEventListener('change', function() {
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionState();
        });
        
        // Event listener para excluir selecionados
        deleteSelectedBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                alert('Selecione pelo menos um aluno para excluir.');
                return;
            }
            
            const confirmMessage = selectedIds.length === 1 
                ? 'Tem certeza que deseja excluir este aluno?' 
                : `Tem certeza que deseja excluir ${selectedIds.length} alunos selecionados?`;
            
            if (confirm(confirmMessage)) {
                document.getElementById('bulk-delete-form').submit();
            }
        });
        
        // Auto-refresh apenas para operações de inserção e exclusão (não busca)
        <?php if (($message || $error) && !$isSearch): ?>
        setTimeout(() => {
            window.location.href = window.location.pathname;
        }, 2000);
        <?php endif; ?>
        
        // Funções para controlar a modal de edição
        function openEditModal(id, name, birthDate, cep, address) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_birth_date').value = birthDate;
            document.getElementById('edit_cep').value = cep;
            document.getElementById('edit_address').value = address;
            
            // Definir data máxima como hoje para o campo de data de nascimento
            document.getElementById('edit_birth_date').max = new Date().toISOString().split('T')[0];
            
            document.getElementById('editModal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Previne scroll do body
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Restaura scroll do body
        }
        
        // Fechar modal ao clicar fora dela
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
        
        // Máscara para CEP na modal
        document.getElementById('edit_cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });
        
        // Buscar CEP na modal
        document.getElementById('edit_buscar_cep').addEventListener('click', function() {
            const cep = document.getElementById('edit_cep').value.replace(/\D/g, '');
            const addressField = document.getElementById('edit_address');
            
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
    </script>
</body>
</html>
