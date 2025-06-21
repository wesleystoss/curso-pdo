# Funcionalidade de Busca - Gerenciador de Alunos

## Novas Funcionalidades Implementadas

### 1. Sistema de Abas
- **Aba "Inserir Novo Aluno"**: Formulário para cadastrar novos alunos
- **Aba "Buscar Aluno"**: Interface para buscar alunos existentes
- **Alternância Dinâmica**: Clique nas abas para alternar entre as funcionalidades

### 2. Busca por ID
- Busca exata por ID do aluno
- Retorna um único resultado ou nenhum
- Campo numérico com validação

### 3. Busca por Nome
- Busca parcial por nome (usando LIKE)
- Retorna múltiplos resultados
- Ordenação alfabética por nome
- Campo de texto com busca flexível

### 4. Interface Melhorada
- Design responsivo com animações suaves
- Validação em tempo real dos campos
- Feedback visual para resultados de busca
- Mensagens informativas sobre o resultado

## Como Usar

### Via Interface Web
1. Acesse http://localhost:8000
2. **Para Inserir Aluno**:
   - Clique na aba "➕ Inserir Novo Aluno"
   - Preencha os campos necessários
   - Use a funcionalidade de CEP se desejar
   - Clique em "Inserir Aluno"

3. **Para Buscar Aluno**:
   - Clique na aba "🔍 Buscar Aluno"
   - Selecione o tipo de busca (Por Nome ou Por ID)
   - Digite o termo de busca
   - Clique em "🔍 Buscar"
   - Visualize os resultados na tabela

### Tipos de Busca

#### Busca por ID
- **Campo**: Numérico
- **Comportamento**: Busca exata
- **Resultado**: 0 ou 1 aluno
- **Exemplo**: ID "5" retorna o aluno com ID 5

#### Busca por Nome
- **Campo**: Texto
- **Comportamento**: Busca parcial (contém)
- **Resultado**: 0 ou mais alunos
- **Exemplo**: "João" retorna "João Silva", "João Ventura", etc.

## Estrutura de Arquivos Modificados

### Interface
- `public/index.php` - Sistema de abas e formulário de busca
- `public/assets/css/style.css` - Estilos para abas e resultados

### Backend
- `src/Domain/Repository/StudentRepository.php` - Interface com novos métodos
- `src/Infrastructure/Repository/PdoStudentRepository.php` - Implementação dos métodos de busca
- `src/Infrastructure/Web/StudentController.php` - Lógica de busca

### Testes
- `scripts/teste-busca.php` - Script de teste para funcionalidade de busca

## Métodos de Busca Implementados

### findById(int $id): ?Student
```php
// Busca um aluno específico por ID
$student = $repository->findById(5);
if ($student) {
    echo "Aluno encontrado: " . $student->name();
}
```

### findByName(string $name): array
```php
// Busca alunos por nome (busca parcial)
$students = $repository->findByName("João");
foreach ($students as $student) {
    echo "Aluno: " . $student->name();
}
```

## Funcionalidades da Interface

### Sistema de Abas
- **Transição Suave**: Animação fadeIn ao alternar abas
- **Estado Ativo**: Indicação visual da aba selecionada
- **Responsivo**: Adaptação para dispositivos móveis

### Formulário de Busca
- **Validação Dinâmica**: Campo se adapta ao tipo de busca
- **Placeholder Inteligente**: Dicas contextuais
- **Validação de Entrada**: Tipo correto para cada busca

### Resultados de Busca
- **Tabela Responsiva**: Mesma estrutura da listagem geral
- **Mensagens Informativas**: Feedback sobre quantidade de resultados
- **Ações Disponíveis**: Exclusão de alunos encontrados

## Exemplos de Uso

### Busca por ID
1. Selecione "Por ID"
2. Digite "5"
3. Clique em "Buscar"
4. Resultado: Aluno com ID 5 (se existir)

### Busca por Nome
1. Selecione "Por Nome"
2. Digite "Silva"
3. Clique em "Buscar"
4. Resultado: Todos os alunos com "Silva" no nome

## Tratamento de Erros

- **ID Inválido**: Retorna null para IDs inexistentes
- **Nome Vazio**: Retorna array vazio para nomes não encontrados
- **Campos Obrigatórios**: Validação no frontend e backend
- **SQL Injection**: Prepared statements para segurança

## Responsividade

- **Desktop**: Abas lado a lado
- **Mobile**: Abas empilhadas verticalmente
- **Tablet**: Layout adaptativo
- **Touch**: Otimizado para dispositivos touch

## Testes

Execute o script de teste para verificar a funcionalidade:
```bash
php scripts/teste-busca.php
```

O script testa:
- Busca por ID existente
- Busca por ID inexistente
- Busca por nome (parcial)
- Busca por nome inexistente
- Validação de retornos

## Integração com Funcionalidades Existentes

- **CEP**: Mantida na aba de inserção
- **Listagem Geral**: Continua funcionando normalmente
- **Exclusão**: Disponível nos resultados de busca
- **Estatísticas**: Atualizadas automaticamente 