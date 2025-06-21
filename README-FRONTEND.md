# 🎓 Front-end para Gerenciador de Alunos

Este é um front-end HTML moderno e responsivo para testar as operações de CRUD (Create, Read, Delete) do sistema de gerenciamento de alunos, implementado com padrão MVC.

## ✨ Funcionalidades

- **Inserir Aluno**: Formulário para adicionar novos alunos com nome e data de nascimento
- **Listar Alunos**: Tabela com todos os alunos cadastrados, mostrando ID, nome, data de nascimento e idade
- **Excluir Aluno**: Botão para remover alunos com confirmação
- **Estatísticas**: Cards mostrando total de alunos, maiores e menores de 18 anos
- **Interface Moderna**: Design responsivo com gradientes e animações

## 🏗️ Arquitetura MVC

O front-end segue o padrão MVC (Model-View-Controller):

- **Model**: `src/Domain/Model/Student.php` - Entidade do aluno
- **View**: `public/index.php` - Interface HTML
- **Controller**: `src/Infrastructure/Web/StudentController.php` - Lógica de negócio

## 🚀 Como Usar

### 1. Configuração Inicial

Certifique-se de que o banco de dados está configurado:

```bash
# Navegar para o diretório do projeto
cd /home/wesley/Projetos/Alura/curso-pdo

# Instalar dependências (se ainda não fez)
composer install

# Configurar o banco de dados
composer run setup
```

### 2. Iniciar o Servidor

Use os comandos do Composer para gerenciar o servidor:

```bash
# Iniciar servidor PHP
composer run server:start

# Verificar status
composer run server:status

# Parar servidor
composer run server:stop

# Reiniciar servidor
composer run server:restart
```

Ou manualmente:
```bash
# Navegar para a pasta public
cd public

# Iniciar servidor na porta 8000
php -S localhost:8000
```

### 3. Acessar o Sistema

Abra seu navegador e acesse:
- **Servidor PHP**: `http://localhost:8000`
- **Apache/Nginx**: `http://localhost/curso-pdo/public`

## 🎨 Características do Design

- **Responsivo**: Funciona em desktop, tablet e mobile
- **Moderno**: Gradientes, sombras e animações suaves
- **Intuitivo**: Interface clara e fácil de usar
- **Feedback Visual**: Mensagens de sucesso e erro
- **Validação**: Campos obrigatórios e validação de data

## 📱 Funcionalidades Técnicas

### Validações
- Nome e data de nascimento obrigatórios
- Data de nascimento não pode ser futura
- Confirmação antes de excluir aluno

### Segurança
- Escape de HTML para prevenir XSS
- Validação de dados de entrada
- Prepared statements (já implementado no repository)
- Separação de responsabilidades (MVC)

### UX/UI
- Auto-refresh após operações
- Mensagens de feedback
- Loading states implícitos
- Hover effects nos botões

## 🔧 Estrutura de Arquivos

```
public/
├── assets/
│   └── css/
│       └── style.css      # Estilos CSS separados
├── bootstrap.php          # Configuração da aplicação
├── index.php              # View principal (HTML)
└── .htaccess             # Configurações do servidor web
```

## 🏗️ Componentes MVC

### Controller (`src/Infrastructure/Web/StudentController.php`)
- Gerencia requisições HTTP
- Processa formulários
- Valida dados
- Chama o repository
- Retorna dados estruturados

### View (`public/index.php`)
- Apenas HTML e apresentação
- Sem lógica de negócio
- CSS externo
- JavaScript mínimo

### Bootstrap (`public/bootstrap.php`)
- Configura dependências
- Cria instâncias necessárias
- Chama o controller
- Retorna dados para a view

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

1. **Inserir Aluno**
   - Preencha o nome completo
   - Selecione a data de nascimento
   - Clique em "Inserir Aluno"

2. **Visualizar Alunos**
   - Lista automática de todos os alunos
   - Informações: ID, Nome, Data de Nascimento, Idade
   - Estatísticas em tempo real

3. **Excluir Aluno**
   - Clique no botão "🗑️ Excluir" na linha do aluno
   - Confirme a exclusão no popup
   - Aluno será removido permanentemente

## 🎯 Próximas Melhorias

- [ ] Funcionalidade de edição de alunos
- [ ] Busca e filtros
- [ ] Paginação para muitos registros
- [ ] Exportação de dados
- [ ] Upload de foto do aluno
- [ ] Sistema de autenticação
- [ ] API REST para integração
- [ ] Testes automatizados para o front-end

## 🔄 Fluxo de Dados

1. **Requisição HTTP** → `public/index.php`
2. **Bootstrap** → `public/bootstrap.php` configura dependências
3. **Controller** → `src/Infrastructure/Web/StudentController.php` processa
4. **Repository** → `src/Infrastructure/Repository/PdoStudentRepository.php` acessa banco
5. **Model** → `src/Domain/Model/Student.php` representa dados
6. **View** → `public/index.php` exibe resultado

---

**Desenvolvido para o curso de PDO da Alura** 🚀
