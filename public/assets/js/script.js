// Definir data máxima como hoje para o campo de data de nascimento
document.addEventListener('DOMContentLoaded', function() {
    const birthDateField = document.getElementById('birth_date');
    if (birthDateField) {
        birthDateField.max = new Date().toISOString().split('T')[0];
    }
    
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
    const cepField = document.getElementById('cep');
    if (cepField) {
        cepField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
            
            // Busca automática quando o CEP estiver completo
            if (value.length === 9) {
                buscarCepAutomaticamente(value, 'address');
            }
        });
    }
    
    // Máscara para CEP na busca
    const searchCepField = document.getElementById('search_cep');
    if (searchCepField) {
        searchCepField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });
    }
    
    // Sistema de seleção múltipla
    const selectAllCheckbox = document.getElementById('select-all');
    const selectAllHeaderCheckbox = document.getElementById('select-all-header');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    const deleteSelectedBtn = document.getElementById('delete-selected');
    const selectedCountSpan = document.getElementById('selected-count');
    
    if (selectAllCheckbox && selectAllHeaderCheckbox && studentCheckboxes.length > 0) {
        // Função para atualizar contador e estado do botão
        function updateSelectionState() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const totalBoxes = studentCheckboxes.length;
            
            if (selectedCountSpan) {
                selectedCountSpan.textContent = checkedBoxes.length;
            }
            if (deleteSelectedBtn) {
                deleteSelectedBtn.disabled = checkedBoxes.length === 0;
            }
            
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
        if (deleteSelectedBtn) {
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
        }
    }
    
    // Máscara para CEP na modal
    const editCepField = document.getElementById('edit_cep');
    if (editCepField) {
        editCepField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
            
            // Busca automática quando o CEP estiver completo
            if (value.length === 9) {
                buscarCepAutomaticamente(value, 'edit_address');
            }
        });
    }
});

// Função para buscar CEP automaticamente
function buscarCepAutomaticamente(cep, targetFieldId) {
    const addressField = document.getElementById(targetFieldId);
    const cepLimpo = cep.replace(/\D/g, '');
    
    if (cepLimpo.length !== 8) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'buscar_cep');
    formData.append('cep', cepLimpo);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addressField.value = data.endereco;
        }
    })
    .catch(error => {
        console.error('Erro ao buscar CEP:', error);
    });
}

// Funções para controlar a modal de edição
function openEditModal(id, name, birthDate, cep, address) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_birth_date').value = birthDate;
    document.getElementById('edit_cep').value = cep;
    document.getElementById('edit_address').value = address;
    
    // Definir data máxima como hoje para o campo de data de nascimento
    const editBirthDateField = document.getElementById('edit_birth_date');
    if (editBirthDateField) {
        editBirthDateField.max = new Date().toISOString().split('T')[0];
    }
    
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
