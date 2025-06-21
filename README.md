# Curso PDO - Sistema de Gerenciamento de Alunos

Sistema de gerenciamento de alunos desenvolvido em PHP com PDO, seguindo princípios de Clean Architecture.

## 📋 Descrição

Este projeto demonstra o uso de PDO (PHP Data Objects) para persistência de dados, implementando padrões de Domain-Driven Design (DDD) e Clean Architecture.

## 🏗️ Arquitetura

O projeto segue a arquitetura em camadas:

```
src/
├── Domain/           # Regras de negócio
│   ├── Model/       # Entidades do domínio
│   └── Repository/  # Interfaces dos repositórios
└── Infrastructure/  # Implementações técnicas
    ├── Persistence/ # Configurações de banco
    └── Repository/  # Implementações dos repositórios
```

## 🚀 Instalação

1. Clone o repositório
2. Instale as dependências:
```bash
composer install
```

## 📊 Configuração do Banco

### Banco de Produção
- Arquivo: `database/banco.sqlite`
- Configuração: `config/database.php`

### Banco de Teste
- Arquivo: `database/banco-teste.sqlite`
- Configuração: `config/database-teste.php`

## 🛠️ Comandos Disponíveis

### Produção
```bash
# Listar alunos
composer run listar

# Inserir aluno
composer run inserir

# Excluir aluno
composer run excluir
```

### Desenvolvimento
```bash
# Executar testes
composer run test

# Limpar banco de teste
composer run test:clean

# Executar testes isolados
composer run test:isolated
```

## 📁 Estrutura do Projeto

```
curso-pdo/
├── config/              # Configurações
│   ├── database.php
│   └── database-teste.php
├── database/            # Arquivos de banco
│   ├── banco.sqlite
│   └── banco-teste.sqlite
├── scripts/             # Scripts de execução
│   ├── inserir-aluno.php
│   ├── lista-alunos.php
│   └── excluir-aluno.php
├── tests/               # Testes
│   ├── TestBuscadorDeCursos.php
│   └── RepositoryTest.php
├── src/                 # Código fonte
│   ├── Domain/
│   └── Infrastructure/
├── vendor/              # Dependências
├── composer.json
└── README.md
```

## 🧪 Testes

O projeto inclui testes automatizados para garantir a qualidade do código:

- **Testes de Repositório**: Verificam as operações CRUD
- **Testes Isolados**: Cada teste usa um banco limpo
- **Testes de Integração**: Testam a integração com o banco

## 🔧 Tecnologias

- **PHP 8.0+**
- **PDO** para acesso ao banco
- **SQLite** como banco de dados
- **Composer** para gerenciamento de dependências

## 📝 Licença

Este projeto é parte do curso de PDO da Alura. 