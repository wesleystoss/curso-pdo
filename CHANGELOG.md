# 📝 Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

## [2.0.0] - 2024-01-XX

### ✨ Adicionado
- **Modal de Edição**: Interface moderna para editar alunos
  - Modal com fundo escuro e blur
  - Formulário pré-preenchido com dados do aluno
  - Validação em tempo real
  - Busca automática de CEP na modal
  - Animações suaves de entrada e saída
- **Botão de Editar**: Novo botão "✏️ Editar" na tabela de alunos
- **Validação de Edição**: Verificação se o aluno existe antes de atualizar
- **Feedback Visual**: Mensagens de sucesso/erro para operações de edição

### 🎨 Melhorado
- **Interface**: Design mais moderno e responsivo
- **UX**: Melhor experiência do usuário com modais
- **Organização**: Estrutura de arquivos mais limpa e organizada
- **Documentação**: README consolidado e mais completo

### 🧹 Limpeza
- **READMEs Consolidados**: Removidos arquivos README duplicados
- **Scripts Organizados**: Documentação dos scripts mantidos
- **Arquivos Desnecessários**: Removidos scripts básicos de exemplo

### 🔧 Técnico
- **Controller**: Adicionado método `updateStudent()` no `StudentController`
- **Validação**: Verificação de existência do aluno antes da atualização
- **CSS**: Estilos para modal e botão primário
- **JavaScript**: Funções para controle da modal e validações

## [1.5.0] - 2024-01-XX

### ✨ Adicionado
- **Busca Unificada**: Busca por nome ou ID
- **Paginação**: Navegação eficiente para grandes volumes de dados
- **Exclusão em Lote**: Seleção múltipla e exclusão em massa
- **Serviço de CEP**: Busca automática de endereço via API
- **Estatísticas**: Cards com informações em tempo real

### 🎨 Melhorado
- **Interface Web**: Design responsivo e moderno
- **Sistema de Abas**: Separação entre inserção e busca
- **Feedback Visual**: Mensagens de sucesso e erro
- **Validações**: Campos obrigatórios e validação de data

## [1.0.0] - 2024-01-XX

### ✨ Adicionado
- **CRUD Básico**: Inserir, listar e excluir alunos
- **Arquitetura MVC**: Padrão Model-View-Controller
- **Clean Architecture**: Separação de responsabilidades
- **PDO**: Persistência de dados com PHP Data Objects
- **SQLite**: Banco de dados leve e portável
- **Interface CLI**: Scripts para operações via linha de comando
- **Testes**: Testes automatizados para validação

### 🏗️ Arquitetura
- **Domain Layer**: Modelos e interfaces de repositório
- **Infrastructure Layer**: Implementações concretas
- **Web Layer**: Controllers para interface web
- **Persistence Layer**: Configurações de banco de dados

---

## 📋 Convenções

- **✨ Adicionado**: Novas funcionalidades
- **🎨 Melhorado**: Melhorias em funcionalidades existentes
- **🐛 Corrigido**: Correções de bugs
- **🧹 Limpeza**: Remoção de código desnecessário
- **🔧 Técnico**: Mudanças técnicas e de infraestrutura
- **📝 Documentação**: Atualizações na documentação
