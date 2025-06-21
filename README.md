# 🎓 Sistema de Gerenciamento de Alunos com PDO

Sistema completo de gerenciamento de alunos desenvolvido em PHP com PDO, seguindo princípios de Clean Architecture e padrão MVC. Inclui interface web moderna e responsiva com funcionalidades completas de CRUD, API REST, sistema de logs, cache e testes automatizados.

## ✨ Funcionalidades

### 🎯 Operações CRUD
- **✅ Inserir Aluno**: Formulário para adicionar novos alunos
- **📋 Listar Alunos**: Tabela com paginação e estatísticas
- **✏️ Editar Aluno**: Modal para edição com validação
- **🗑️ Excluir Aluno**: Exclusão individual e em lote
- **🔍 Buscar Aluno**: Busca por nome ou ID

### 🚀 API REST Completa
- **GET /api/students**: Listar todos os alunos
- **GET /api/students/{id}**: Buscar aluno por ID
- **POST /api/students**: Criar novo aluno
- **PUT /api/students/{id}**: Atualizar aluno
- **DELETE /api/students/{id}**: Excluir aluno
- **GET /api/stats**: Estatísticas do sistema

### 🎨 Interface Moderna
- **Responsivo**: Funciona em desktop, tablet e mobile
- **Modal de Edição**: Interface moderna com fundo escuro
- **Validação em Tempo Real**: CEP automático e validações
- **Estatísticas**: Cards com informações em tempo real
- **Paginação**: Navegação eficiente para muitos registros
- **Painel de Administração**: Monitoramento do sistema

### 🛡️ Funcionalidades Avançadas
- **Sistema de Logs**: Auditoria completa de operações
- **Cache Inteligente**: Melhoria de performance
- **Validação Robusta**: Classes de validação customizadas
- **Exceções Customizadas**: Tratamento de erros profissional
- **Configuração por Ambiente**: Variáveis de ambiente
- **Testes Unitários**: Cobertura completa com PHPUnit

## 🏗️ Arquitetura

O projeto segue a arquitetura em camadas com padrão MVC e Clean Architecture:

```
src/
├── Domain/           # Regras de negócio
│   ├── Model/       # Entidades do domínio
│   ├── Repository/  # Interfaces dos repositórios
│   ├── Service/     # Serviços de domínio
│   └── Exception/   # Exceções customizadas
│   └── Repository/  # Interfaces dos repositórios
└── Infrastructure/  # Implementações técnicas
    ├── Persistence/ # Configurações de banco
    ├── Repository/  # Implementações dos repositórios
    ├── Service/     # Serviços externos (CEP, Log, Cache, Config)
    └── Web/         # Controllers (MVC + API)
```

## 🚀 Instalação e Configuração

### 1. Pré-requisitos
- PHP 8.0+
- Composer
- SQLite (incluído no PHP)

### 2. Instalação
```bash
# Clone o repositório
git clone <repository-url>
cd curso-pdo

# Instale as dependências
composer install

# Configure o banco de dados
composer run setup

# Copie o arquivo de configuração (opcional)
cp env.example .env
```

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
- **Painel Admin**: `http://localhost:8000/admin.php`
- **API REST**: `http://localhost:8000/api/students`

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

# Testes unitários
composer run test:unit

# Testes de integração
composer run test:integration

# Cobertura de testes
composer run test:coverage

# Limpar banco de teste
composer run test:clean

# Executar testes isolados
composer run test:isolated
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
├── config/              # Configurações
│   ├── database.php
│   ├── database-teste.php
│   └── environment.php
├── database/            # Arquivos de banco
│   ├── banco.sqlite
│   └── banco-teste.sqlite
├── docs/                # Documentação
│   └── API.md
├── logs/                # Logs do sistema
├── cache/               # Cache do sistema
├── public/              # Front-end (DocumentRoot)
│   ├── assets/
│   │   └── css/
│   │       └── style.css
│   ├── bootstrap.php    # Configuração da aplicação
│   ├── index.php        # View principal
│   ├── admin.php        # Painel de administração
│   ├── api.php          # Endpoint da API
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
│       │   └── EnvironmentConfig.php
│       └── Web/
│           ├── StudentController.php
│           └── ApiController.php
├── tests/               # Testes
│   ├── Unit/
│   │   └── StudentTest.php
│   ├── Integration/
│   ├── teste-repository.php
│   ├── teste-repository-melhorado.php
│   └── test.php
├── vendor/              # Dependências
├── composer.json
├── phpunit.xml
├── env.example
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

### Funcionalidades Técnicas
- **Validações**: Nome e data de nascimento obrigatórios
- **CEP Automático**: Busca de endereço via API
- **Segurança**: Escape de HTML, validação de dados, prepared statements
- **UX/UI**: Auto-refresh, mensagens de feedback, loading states
- **Paginação**: Navegação eficiente para grandes volumes de dados

## 🧪 Testes

O projeto inclui testes automatizados para garantir a qualidade do código:

### Testes Unitários
- **Testes de Modelo**: Verificam a criação e manipulação de objetos Student
- **Testes de Validação**: Verificam as regras de validação
- **Testes de Exceções**: Verificam o tratamento de erros

### Testes de Integração
- **Testes de Repositório**: Verificam as operações CRUD
- **Testes Isolados**: Cada teste usa um banco limpo
- **Testes de API**: Verificam os endpoints REST

### Executar Testes
```bash
# Todos os testes
composer run test

# Testes unitários
composer run test:unit

# Testes de integração
composer run test:integration

# Cobertura de código
composer run test:coverage
```

## 🚀 API REST

### Endpoints Disponíveis

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/students` | Listar todos os alunos |
| GET | `/api/students/{id}` | Buscar aluno por ID |
| POST | `/api/students` | Criar novo aluno |
| PUT | `/api/students/{id}` | Atualizar aluno |
| DELETE | `/api/students/{id}` | Excluir aluno |
| GET | `/api/stats` | Estatísticas do sistema |

### Exemplo de Uso
```bash
# Listar alunos
curl http://localhost:8000/api/students

# Criar aluno
curl -X POST http://localhost:8000/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"João Silva","birth_date":"1990-01-01"}'
```

**Documentação completa**: [docs/API.md](docs/API.md)

## 🔧 Tecnologias

- **PHP 8.0+**
- **PDO** para acesso ao banco
- **SQLite** como banco de dados
- **Composer** para gerenciamento de dependências
- **PHPUnit** para testes automatizados
- **MVC** para organização do código
- **Clean Architecture** para separação de responsabilidades
- **HTML5/CSS3** para interface moderna
- **JavaScript** para interações dinâmicas

## 🛡️ Funcionalidades de Segurança

### Validação de Dados
- **Validação de Nome**: Mínimo 2 caracteres, apenas letras e acentos
- **Validação de Data**: Formato YYYY-MM-DD, não pode ser no futuro
- **Validação de CEP**: 8 dígitos numéricos
- **Validação de ID**: Números positivos

### Segurança da API
- **CORS**: Configurável via variáveis de ambiente
- **Rate Limiting**: Proteção contra abuso
- **Validação de Entrada**: Todos os dados são validados
- **Logs de Auditoria**: Todas as operações são registradas

### Cache e Performance
- **Cache Inteligente**: Melhoria de performance
- **TTL Configurável**: Tempo de vida do cache
- **Invalidação Automática**: Cache limpo quando dados mudam
- **Estatísticas**: Monitoramento do uso do cache

## 🐛 Solução de Problemas

### Erro de Conexão com Banco
- Verifique se o arquivo `database/banco.sqlite` existe
- Execute `composer run setup` para criar o banco

### Erro de Permissões
- Certifique-se de que as pastas `database/`, `logs/`, `cache/` têm permissões de escrita
- Execute: `chmod 755 database/ logs/ cache/`

### Página não Carrega
- Verifique se o PHP está instalado: `php --version`
- Confirme se o servidor está rodando: `composer run server:status`
- Verifique os logs de erro do servidor

### Erro de Autoload
- Execute: `composer dump-autoload`
- Verifique se o namespace está correto

### Problemas com Cache
- Limpe o cache: `composer run cache:clear`
- Verifique as estatísticas: `composer run cache:stats`

### Problemas com Logs
- Visualize os logs: `composer run logs:view`
- Limpe os logs: `composer run logs:clear`

## 📊 Operações Disponíveis

### 1. Inserir Aluno
- Preencha o nome completo
- Selecione a data de nascimento
- Opcional: Digite o CEP para buscar endereço automaticamente
- Clique em "Inserir Aluno"

### 2. Editar Aluno
- Clique no botão "✏️ Editar" na linha do aluno
- Modal será aberta com dados preenchidos
- Faça as alterações necessárias
- Clique em "Salvar Alterações"

### 3. Buscar Aluno
- Selecione o tipo de busca (Nome ou ID)
- Digite o termo de busca
- Clique em "Buscar"

### 4. Excluir Aluno
- Clique no botão "🗑️ Excluir" na linha do aluno
- Confirme a exclusão
- Para exclusão em lote, marque os alunos e clique em "Excluir Selecionados"

### 5. Painel de Administração
- Acesse `http://localhost:8000/admin.php`
- Visualize estatísticas do sistema
- Monitore logs e cache
- Teste a API REST

## 🎯 Melhorias Implementadas

### Funcionalidades Avançadas
- ✅ **API REST Completa**: Endpoints para todas as operações CRUD
- ✅ **Sistema de Logs**: Auditoria completa de operações
- ✅ **Cache Inteligente**: Melhoria de performance
- ✅ **Validação Robusta**: Classes de validação customizadas
- ✅ **Exceções Customizadas**: Tratamento de erros profissional
- ✅ **Configuração por Ambiente**: Variáveis de ambiente
- ✅ **Testes Unitários**: Cobertura completa com PHPUnit
- ✅ **Painel de Administração**: Monitoramento do sistema
- ✅ **Documentação da API**: Guia completo de uso

### Qualidade de Código
- ✅ **Clean Architecture**: Separação clara de responsabilidades
- ✅ **SOLID Principles**: Código bem estruturado
- ✅ **Error Handling**: Tratamento robusto de erros
- ✅ **Security**: Validação e sanitização de dados
- ✅ **Performance**: Cache e otimizações
- ✅ **Maintainability**: Código limpo e bem documentado

---

**🎉 Projeto completamente funcional e profissional seguindo as melhores práticas de desenvolvimento!**
