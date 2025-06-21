# Curso PDO - Sistema de Gerenciamento de Alunos

Sistema de gerenciamento de alunos desenvolvido em PHP com PDO, seguindo princípios de Clean Architecture e padrão MVC.

## 📋 Descrição

Este projeto demonstra o uso de PDO (PHP Data Objects) para persistência de dados, implementando padrões de Domain-Driven Design (DDD), Clean Architecture e MVC (Model-View-Controller).

## 🏗️ Arquitetura

O projeto segue a arquitetura em camadas com padrão MVC:

```
src/
├── Domain/           # Regras de negócio
│   ├── Model/       # Entidades do domínio
│   └── Repository/  # Interfaces dos repositórios
└── Infrastructure/  # Implementações técnicas
    ├── Persistence/ # Configurações de banco
    ├── Repository/  # Implementações dos repositórios
    └── Web/         # Controllers (MVC)
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

### Servidor Web
```bash
# Iniciar servidor PHP
composer run server:start

# Parar servidor
composer run server:stop

# Verificar status do servidor
composer run server:status

# Reiniciar servidor
composer run server:restart
```

## 📁 Estrutura do Projeto

```
curso-pdo/
├── config/              # Configurações
│   ├── database.php
│   ├── database-teste.php
│   └── environment.php
├── database/            # Arquivos de banco
│   ├── banco.sqlite
│   └── banco-teste.sqlite
├── public/              # Front-end (DocumentRoot)
│   ├── assets/
│   │   └── css/
│   │       └── style.css
│   ├── bootstrap.php    # Configuração da aplicação
│   ├── index.php        # View principal
│   └── .htaccess
├── scripts/             # Scripts de execução CLI
│   ├── inserir-aluno.php
│   ├── lista-alunos.php
│   ├── excluir-aluno.php
│   ├── setup.php
│   └── criar-tabela.php
├── src/                 # Código fonte
│   ├── Domain/
│   │   ├── Model/
│   │   │   └── Student.php
│   │   └── Repository/
│   │       └── StudentRepository.php
│   └── Infrastructure/
│       ├── Persistence/
│       │   └── ConnectionCreator.php
│       ├── Repository/
│       │   └── PdoStudentRepository.php
│       └── Web/
│           └── StudentController.php
├── tests/               # Testes
│   ├── teste-repository.php
│   ├── teste-repository-melhorado.php
│   └── test.php
├── vendor/              # Dependências
├── composer.json
├── README.md
└── README-FRONTEND.md
```

## 🌐 Front-end Web

O projeto inclui um front-end moderno e responsivo para gerenciar alunos:

- **Interface Web**: Acesse `http://localhost:8000`
- **Funcionalidades**: Inserir, listar e excluir alunos
- **Design**: Responsivo com CSS moderno
- **Arquitetura**: Padrão MVC implementado

Veja mais detalhes em [README-FRONTEND.md](README-FRONTEND.md)

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
- **MVC** para organização do código
- **Clean Architecture** para separação de responsabilidades

## 📝 Licença

Este projeto é parte do curso de PDO da Alura.
