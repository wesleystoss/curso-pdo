# 📁 Scripts do Projeto

Este diretório contém scripts utilitários para o sistema de gerenciamento de alunos.

## 🛠️ Scripts de Configuração

### `setup.php`
Script principal de configuração do projeto.
- Cria o banco de dados SQLite
- Cria a tabela `students`
- Configura as permissões necessárias

**Uso:**
```bash
composer run setup
# ou
php scripts/setup.php
```

### `criar-tabela.php`
Cria apenas a tabela `students` no banco de dados.

**Uso:**
```bash
php scripts/criar-tabela.php
```

### `atualizar-tabela.php`
Atualiza a estrutura da tabela (adiciona colunas CEP e endereço).

**Uso:**
```bash
php scripts/atualizar-tabela.php
```

## 📊 Scripts de Operação

### `inserir-aluno.php`
Script para inserir um aluno via linha de comando.

**Uso:**
```bash
composer run inserir
# ou
php scripts/inserir-aluno.php
```

### `lista-alunos.php`
Lista todos os alunos cadastrados no banco.

**Uso:**
```bash
composer run listar
# ou
php scripts/lista-alunos.php
```

### `excluir-aluno.php`
Remove um aluno específico do banco.

**Uso:**
```bash
composer run excluir
# ou
php scripts/excluir-aluno.php
```

## 🧪 Scripts de Teste

### `teste-busca.php`
Testa a funcionalidade de busca por nome.

### `teste-busca-web.php`
Testa a funcionalidade de busca via web.

### `teste-busca-unificada.php`
Testa a busca unificada (nome e ID).

### `teste-cep.php`
Testa o serviço de busca de CEP.

### `teste-exclusao-lote.php`
Testa a funcionalidade de exclusão em lote.

### `teste-paginacao.php`
Testa a funcionalidade de paginação.

## 📝 Notas

- Todos os scripts de teste são importantes para validar as funcionalidades
- Os scripts de configuração devem ser executados apenas uma vez
- Os scripts de operação podem ser usados para automação ou debugging
- Mantenha todos os arquivos de teste para garantir a qualidade do código 