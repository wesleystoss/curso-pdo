# 🎓 Front-end para Gerenciador de Alunos

Este é um front-end HTML moderno e responsivo para testar as operações de CRUD (Create, Read, Delete) do sistema de gerenciamento de alunos.

## ✨ Funcionalidades

- **Inserir Aluno**: Formulário para adicionar novos alunos com nome e data de nascimento
- **Listar Alunos**: Tabela com todos os alunos cadastrados, mostrando ID, nome, data de nascimento e idade
- **Excluir Aluno**: Botão para remover alunos com confirmação
- **Estatísticas**: Cards mostrando total de alunos, maiores e menores de 18 anos
- **Interface Moderna**: Design responsivo com gradientes e animações

## 🚀 Como Usar

### 1. Configuração Inicial

Certifique-se de que o banco de dados está configurado:

```bash
# Navegar para o diretório do projeto
cd /home/wesley/Projetos/Alura/curso-pdo

# Instalar dependências (se ainda não fez)
composer install

# Configurar o banco de dados
php scripts/setup.php
```

### 2. Iniciar o Servidor

Você pode usar o servidor embutido do PHP:

```bash
# Navegar para a pasta public
cd public

# Iniciar servidor na porta 8000
php -S localhost:8000
```

Ou usar o servidor Apache/Nginx configurando o DocumentRoot para a pasta `public`.

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

### UX/UI
- Auto-refresh após operações
- Mensagens de feedback
- Loading states implícitos
- Hover effects nos botões

## 🔧 Estrutura de Arquivos

```
public/
├── index.php          # Front-end principal
└── .htaccess         # Configurações do servidor web
```

## 🐛 Solução de Problemas

### Erro de Conexão com Banco
- Verifique se o arquivo `database/banco.sqlite` existe
- Execute `php scripts/setup.php` para criar o banco

### Erro de Permissões
- Certifique-se de que a pasta `database/` tem permissões de escrita
- Execute: `chmod 755 database/`

### Página não Carrega
- Verifique se o PHP está instalado: `php --version`
- Confirme se o servidor está rodando na porta correta
- Verifique os logs de erro do servidor

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

---

**Desenvolvido para o curso de PDO da Alura** 🚀 