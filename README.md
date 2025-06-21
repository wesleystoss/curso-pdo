# 🎓 Sistema de Gerenciamento de Alunos com PDO

Sistema completo de gerenciamento de alunos desenvolvido em PHP com PDO, seguindo princípios de Clean Architecture e padrão MVC. Inclui interface web moderna e responsiva com funcionalidades completas de CRUD.

## ✨ Funcionalidades

### 🎯 Operações CRUD
- **✅ Inserir Aluno**: Formulário para adicionar novos alunos
- **📋 Listar Alunos**: Tabela com paginação e estatísticas
- **✏️ Editar Aluno**: Modal para edição com validação
- **🗑️ Excluir Aluno**: Exclusão individual e em lote
- **🔍 Buscar Aluno**: Busca por nome ou ID

### 🎨 Interface Moderna
- **Responsivo**: Funciona em desktop, tablet e mobile
- **Modal de Edição**: Interface moderna com fundo escuro
- **Validação em Tempo Real**: CEP automático e validações
- **Estatísticas**: Cards com informações em tempo real
- **Paginação**: Navegação eficiente para muitos registros

## 🏗️ Arquitetura

O projeto segue a arquitetura em camadas com padrão MVC e Clean Architecture:

```
src/
├── Domain/           # Regras de negócio
│   ├── Model/       # Entidades do domínio
│   └── Repository/  # Interfaces dos repositórios
└── Infrastructure/  # Implementações técnicas
    ├── Persistence/ # Configurações de banco
    ├── Repository/  # Implementações dos repositórios
    ├── Service/     # Serviços externos (CEP)
    └── Web/         # Controllers (MVC)
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
Abra seu navegador e acesse: `http://localhost:8000`

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
│       ├── Service/
│       │   └── CepService.php
│       └── Web/
│           └── StudentController.php
├── tests/               # Testes
│   ├── teste-repository.php
│   ├── teste-repository-melhorado.php
│   └── test.php
├── vendor/              # Dependências
├── composer.json
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

- **Testes de Repositório**: Verificam as operações CRUD
- **Testes Isolados**: Cada teste usa um banco limpo
- **Testes de Integração**: Testam a integração com o banco

### Executar Testes
```bash
# Todos os testes
composer run test

# Testes específicos
php tests/teste-repository.php
php tests/teste-repository-melhorado.php
```

## 🔧 Tecnologias

- **PHP 8.0+**
- **PDO** para acesso ao banco
- **SQLite** como banco de dados
- **Composer** para gerenciamento de dependências
- **MVC** para organização do código
- **Clean Architecture** para separação de responsabilidades
- **HTML5/CSS3** para interface moderna
- **JavaScript** para interações dinâmicas

## 🐛 Solução de Problemas

### Erro de Conexão com Banco
- Verifique se o arquivo `database/banco.sqlite` existe
- Execute `composer run setup` para criar o banco

### Erro de Permissões
- Certifique-se de que a pasta `database/` tem permissões de escrita
- Execute: `chmod 755 database/`

### Página não Carrega
- Verifique se o PHP está instalado: `php --version`
- Confirme se o servidor está rodando: `composer run server:status`
- Verifique os logs de erro do servidor

### Erro de Autoload
- Execute: `composer dump-autoload`
- Verifique se o namespace está correto

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
- Clique em "🔍 Buscar"
- Resultados serão exibidos na tabela

### 4. Excluir Aluno
- **Individual**: Clique no botão "🗑️ Excluir" na linha do aluno
- **Em Lote**: Selecione múltiplos alunos e clique em "Excluir Selecionados"
- Confirme a exclusão no popup

## 🔄 Fluxo de Dados

1. **Requisição HTTP** → `public/index.php`
2. **Bootstrap** → `public/bootstrap.php` configura dependências
3. **Controller** → `src/Infrastructure/Web/StudentController.php` processa
4. **Repository** → `src/Infrastructure/Repository/PdoStudentRepository.php` acessa banco
5. **Model** → `src/Domain/Model/Student.php` representa dados
6. **View** → `public/index.php` exibe resultado

## 🎯 Próximas Melhorias

- [x] Funcionalidade de edição de alunos
- [x] Busca e filtros
- [x] Paginação para muitos registros
- [x] Exclusão em lote
- [ ] Exportação de dados
- [ ] Upload de foto do aluno
- [ ] Sistema de autenticação
- [ ] API REST para integração
- [ ] Testes automatizados para o front-end

## 📝 Licença

Este projeto é parte do curso de PDO da Alura.

---

**Desenvolvido para o curso de PDO da Alura** 🚀
