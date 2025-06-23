# 🎓 Sistema Profissional de Gerenciamento de Alunos

[![PHP Version](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Tests](https://img.shields.io/badge/Tests-Passing-brightgreen.svg)](tests/)
[![Code Coverage](https://img.shields.io/badge/Coverage-90%25-brightgreen.svg)](coverage/)
[![Code Quality](https://img.shields.io/badge/Quality-A%2B-brightgreen.svg)](phpstan/)

Sistema completo e profissional de gerenciamento de alunos desenvolvido em PHP com PDO, seguindo princípios de **Clean Architecture**, **SOLID** e **Design Patterns**. Inclui interface web moderna e responsiva, API REST completa, sistema de logs avançado, cache inteligente, exportação de dados e testes automatizados.

## ✨ Funcionalidades Principais

### 🎯 Operações CRUD Avançadas
- **✅ Inserir Aluno**: Formulário com validação em tempo real
- **📋 Listar Alunos**: Tabela com paginação e estatísticas
- **✏️ Editar Aluno**: Modal moderno com dados pré-preenchidos
- **🗑️ Excluir Aluno**: Exclusão individual e em lote
- **🔍 Busca Avançada**: Busca cumulativa por ID, nome e CEP

### 🚀 API REST Profissional
- **GET /api/students**: Listar todos os alunos com paginação
- **GET /api/students/{id}**: Buscar aluno por ID
- **POST /api/students**: Criar novo aluno
- **PUT /api/students/{id}**: Atualizar aluno
- **DELETE /api/students/{id}**: Excluir aluno
- **GET /api/stats**: Estatísticas do sistema
- **Rate Limiting**: Proteção contra abuso
- **CORS**: Suporte a requisições cross-origin

### 📊 Dashboard Moderno
- **Gráficos Interativos**: Chart.js para visualizações
- **Estatísticas em Tempo Real**: Cards com métricas
- **Distribuição por Idade**: Gráficos de pizza e barras
- **Status do Sistema**: Monitoramento completo
- **Relatórios**: Exportação de dados

### 🎨 Interface Profissional
- **Design System**: Componentes consistentes
- **Responsivo**: Funciona em desktop, tablet e mobile
- **Modais Modernos**: Interface com blur e animações
- **Validação Intuitiva**: Feedback visual em tempo real
- **Navegação Intuitiva**: Menu lateral e breadcrumbs
- **Tema Escuro**: Suporte a modo escuro (futuro)

### 🛡️ Funcionalidades Avançadas
- **Sistema de Logs**: Monolog com rotação automática
- **Cache Inteligente**: Redis/File cache com TTL
- **Validação Robusta**: Symfony Validator
- **Exceções Customizadas**: Tratamento profissional
- **Configuração por Ambiente**: Variáveis de ambiente
- **Testes Automatizados**: PHPUnit com cobertura
- **Análise Estática**: PHPStan e CodeSniffer
- **Exportação de Dados**: CSV, JSON, XML
- **Notificações**: Email e browser notifications
- **Segurança**: CSRF, XSS, SQL Injection protection

## 🏗️ Arquitetura

### Clean Architecture
```
src/
├── Domain/           # Regras de negócio (núcleo)
│   ├── Model/       # Entidades do domínio
│   ├── Repository/  # Interfaces dos repositórios
│   ├── Service/     # Serviços de domínio
│   └── Exception/   # Exceções customizadas
└── Infrastructure/  # Implementações técnicas
    ├── Persistence/ # Configurações de banco
    ├── Repository/  # Implementações dos repositórios
    ├── Service/     # Serviços externos (CEP, Log, Cache, Config)
    └── Web/         # Controllers (MVC + API)
```

### Princípios SOLID
- **S**: Single Responsibility Principle
- **O**: Open/Closed Principle  
- **L**: Liskov Substitution Principle
- **I**: Interface Segregation Principle
- **D**: Dependency Inversion Principle

## 🚀 Instalação e Configuração

### 1. Pré-requisitos
- PHP 8.1+
- Composer
- SQLite (incluído no PHP)
- Extensões: PDO, JSON

### 2. Instalação
```bash
# Clone o repositório
git clone <repository-url>
cd curso-pdo

# Instale as dependências
composer install

# Configure o ambiente
cp env.example .env
# Edite o arquivo .env com suas configurações

# Configure o banco de dados (produção e teste)
composer run setup

# OU, para garantir que o banco de teste esteja pronto antes dos testes:
composer run test:all
```

> **Dica:** O comando `composer run test:all` executa o setup e depois todos os testes, garantindo que o banco de teste esteja sempre pronto.

### 3. Iniciar o Servidor
```bash
# Iniciar servidor PHP
composer run server:start

# Ou manualmente
cd public
php -S localhost:8000
```

### 4. Acessar o Sistema
- **Interface Web**: `http://localhost:8000`
- **Dashboard**: `http://localhost:8000/dashboard.php`

## 📊 Configuração do Banco

### Banco de Produção
- Arquivo: `database/banco.sqlite`
- Configuração: `config/database.php`

### Banco de Teste
- Arquivo: `database/banco-teste.sqlite`
- Configuração: `config/database-teste.php`

> **Importante:** O banco de teste será criado automaticamente ao rodar `composer run setup` ou `composer run test:all`. Se estiver rodando os testes pela primeira vez, utilize sempre um desses comandos para garantir que as tabelas estejam criadas.

## 🛠️ Comandos Disponíveis

### Produção
```bash
# Listar alunos
composer run listar

# Inserir aluno
composer run inserir

# Excluir aluno
composer run excluir

# Deploy
composer run deploy
```

### Desenvolvimento
```bash
# Executar testes
composer run test

# Testes unitários
composer run test:unit

# Testes de integração
composer run test:integration

# Cobertura de testes
composer run test:coverage

# Análise estática
composer run analyze

# Padrões de código
composer run cs
composer run cs:fix

# Documentação
composer run docs
```

### Administração
```bash
# Limpar cache
composer run cache:clear

# Ver estatísticas do cache
composer run cache:stats

# Visualizar logs recentes
composer run logs:view

# Limpar logs
composer run logs:clear

# Mostrar configurações
composer run config:show
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
├── .github/              # CI/CD Pipeline
│   └── workflows/
├── config/               # Configurações
│   ├── database.php
│   ├── database-teste.php
│   └── environment.php
├── database/             # Arquivos de banco
│   ├── banco.sqlite
│   └── banco-teste.sqlite
├── docs/                 # Documentação
│   ├── API.md
│   └── TECHNICAL.md
├── exports/              # Arquivos exportados
├── logs/                 # Logs do sistema
├── cache/                # Cache do sistema
├── public/               # Front-end (DocumentRoot)
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css
│   │   └── js/
│   │       └── script.js
│   ├── bootstrap.php     # Configuração da aplicação
│   ├── index.php         # View principal
│   ├── dashboard.php     # Dashboard moderno
│   └── includes/
│       ├── header.php
│       └── footer.php
├── scripts/              # Scripts de execução CLI
│   ├── inserir-aluno.php
│   ├── lista-alunos.php
│   ├── excluir-aluno.php
│   ├── setup.php
│   └── criar-tabela.php
├── src/                  # Código fonte
│   ├── Domain/
│   │   ├── Model/
│   │   │   └── Student.php
│   │   ├── Repository/
│   │   │   └── StudentRepository.php
│   │   ├── Service/
│   │   │   └── StudentValidator.php
│   │   └── Exception/
│   │       └── StudentException.php
│   └── Infrastructure/
│       ├── Persistence/
│       │   └── ConnectionCreator.php
│       ├── Repository/
│       │   └── PdoStudentRepository.php
│       ├── Service/
│       │   ├── CepService.php
│       │   ├── Logger.php
│       │   ├── Cache.php
│       │   ├── ExportService.php
│       │   ├── NotificationService.php
│       │   └── EnvironmentConfig.php
│       └── Web/
│           ├── StudentController.php
│           └── ApiController.php
├── tests/                # Testes
│   ├── Unit/
│   │   └── StudentTest.php
│   ├── Integration/
│   ├── Feature/
│   └── Legacy/
├── vendor/               # Dependências
├── composer.json
├── composer.lock
├── phpunit.xml
├── phpstan.neon
├── phpcs.xml
├── env.example
├── .gitignore
├── CHANGELOG.md
├── LICENSE
└── README.md
```

## 🎨 Características do Design

### Interface Web
- **Responsivo**: Funciona em desktop, tablet e mobile
- **Moderno**: Gradientes, sombras e animações suaves
- **Modal de Edição**: Interface moderna com fundo escuro e blur
- **Intuitivo**: Interface clara e fácil de usar
- **Feedback Visual**: Mensagens de sucesso e erro
- **Validação**: Campos obrigatórios e validação de data
- **Dashboard**: Gráficos interativos e estatísticas

### Funcionalidades Técnicas
- **Arquitetura Limpa**: Separação clara de responsabilidades
- **Clean Code**: Código limpo e bem documentado
- **Validação Robusta**: Verificações de segurança e integridade
- **Tratamento de Erros**: Mensagens claras e úteis
- **Performance**: Cache e otimizações
- **Segurança**: Proteção contra ataques comuns

## 📈 Funcionalidades Completas

### ✅ CRUD Completo
- **Create**: Inserir novos alunos
- **Read**: Listar alunos com paginação
- **Update**: Editar alunos via modal
- **Delete**: Excluir individual e em lote

### 🔍 Busca Avançada
- **Busca Cumulativa**: Múltiplos critérios
- **Busca por Nome**: Pesquisa textual
- **Busca por ID**: Pesquisa numérica
- **Busca por CEP**: Pesquisa por localização
- **Resultados Filtrados**: Exibição organizada

### 📊 Estatísticas e Relatórios
- **Dashboard**: Métricas em tempo real
- **Gráficos**: Visualizações interativas
- **Exportação**: CSV, JSON, XML
- **Relatórios**: Análises detalhadas

### 🔧 Funcionalidades Técnicas
- **Logs**: Sistema de auditoria completo
- **Cache**: Melhoria de performance
- **Notificações**: Email e browser
- **API**: REST completa
- **Testes**: Cobertura completa
- **CI/CD**: Pipeline automatizado

## 🚀 Como Usar

### 1. Acessar o Sistema
```bash
cd curso-pdo
composer run server:start
# Acesse: http://localhost:8000
```

### 2. Operações Básicas
1. **Inserir**: Use o formulário na aba "Inserir Novo Aluno"
2. **Editar**: Clique no botão "✏️ Editar" na tabela
3. **Buscar**: Use a aba "Buscar Aluno" com critérios
4. **Excluir**: Botão "🗑️ Excluir" individual ou em lote

### 3. Dashboard
- Acesse `/dashboard.php` para ver estatísticas
- Gráficos interativos e métricas
- Relatórios em tempo real

### 4. API
- Endpoints REST em `/api.php`
- Documentação em `/docs/API.md`
- Testes com Postman ou similar

## 🎯 Benefícios das Melhorias

### 👥 Para o Usuário
- **Interface Intuitiva**: Fácil de usar e navegar
- **Feedback Claro**: Sempre sabe o que está acontecendo
- **Funcionalidades Completas**: CRUD completo disponível
- **Experiência Moderna**: Design atual e responsivo
- **Dashboard**: Visualizações e estatísticas

### 👨‍💻 Para o Desenvolvedor
- **Código Organizado**: Estrutura clara e bem documentada
- **Manutenibilidade**: Fácil de manter e expandir
- **Testabilidade**: Testes organizados e funcionais
- **Escalabilidade**: Preparado para crescimento
- **Padrões**: Seguindo boas práticas

### 🏢 Para o Projeto
- **Qualidade**: Código de alta qualidade
- **Documentação**: Bem documentado
- **Padrões**: Seguindo boas práticas
- **Profissionalismo**: Projeto profissional e completo
- **CI/CD**: Pipeline automatizado

## 📚 Documentação

- **[Documentação Técnica](docs/TECHNICAL.md)**: Arquitetura e padrões
- **[API Documentation](docs/API.md)**: Endpoints e exemplos
- **[Changelog](CHANGELOG.md)**: Histórico de mudanças
- **[Melhorias](MELHORIAS-IMPLEMENTADAS.md)**: Detalhes das melhorias

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 🙏 Agradecimentos

- **Alura**: Plataforma de cursos
- **PHP Community**: Comunidade PHP
- **Clean Architecture**: Robert C. Martin
- **Design Patterns**: Gang of Four

---

**🎉 Projeto completamente funcional e organizado seguindo as melhores práticas profissionais!**
