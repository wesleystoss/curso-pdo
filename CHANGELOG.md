# Changelog - Reorganização do Projeto

## [1.0.0] - 2024-06-21

### 🎯 Adicionado
- **Estrutura de pastas organizada** seguindo boas práticas
- **Sistema de configuração centralizado** em `config/`
- **Scripts organizados** em `scripts/`
- **Testes organizados** em `tests/`
- **Bancos de dados separados** em `database/`
- **Script de setup automático** para facilitar a inicialização
- **Arquivo .gitignore** para excluir arquivos desnecessários
- **README.md completo** com documentação detalhada
- **Configuração de ambiente** para diferentes ambientes

### 📁 Reorganização de Pastas

#### Antes:
```
curso-pdo/
├── banco.sqlite
├── banco-teste.sqlite
├── config-teste.php
├── inserir-aluno.php
├── lista-alunos.php
├── excluir-aluno.php
├── teste-repository.php
├── teste-repository-melhorado.php
├── test.php
├── criar-tabela.php
├── conexao.php
├── projeto-inicial.php
├── limpar-banco-teste.php
├── src/
└── vendor/
```

#### Depois:
```
curso-pdo/
├── config/              # Configurações centralizadas
│   ├── database.php
│   ├── database-teste.php
│   ├── environment.php
│   └── limpar-banco-teste.php
├── database/            # Arquivos de banco
│   ├── banco.sqlite
│   └── banco-teste.sqlite
├── scripts/             # Scripts de execução
│   ├── setup.php
│   ├── inserir-aluno.php
│   ├── lista-alunos.php
│   ├── excluir-aluno.php
│   ├── criar-tabela.php
│   ├── conexao.php
│   └── projeto-inicial.php
├── tests/               # Testes organizados
│   ├── teste-repository.php
│   ├── teste-repository-melhorado.php
│   └── test.php
├── src/                 # Código fonte (mantido)
├── vendor/              # Dependências
├── .gitignore          # Exclusões do Git
├── README.md           # Documentação principal
├── CHANGELOG.md        # Este arquivo
└── composer.json       # Scripts atualizados
```

### 🔧 Melhorias Técnicas

#### Configuração Centralizada
- **`config/database.php`**: Configuração principal do banco
- **`config/database-teste.php`**: Configuração específica para testes
- **`config/environment.php`**: Configuração baseada em ambiente

#### Scripts do Composer
```json
{
    "setup": "php scripts/setup.php",
    "listar": "php scripts/lista-alunos.php",
    "inserir": "php scripts/inserir-aluno.php",
    "excluir": "php scripts/excluir-aluno.php",
    "test": "php tests/teste-repository-melhorado.php",
    "test:clean": "php config/limpar-banco-teste.php",
    "test:isolated": "php tests/teste-repository.php"
}
```

#### Caminhos Atualizados
- Todos os arquivos agora usam caminhos relativos corretos
- Configuração centralizada evita duplicação de código
- Separação clara entre produção e teste

### 🧪 Testes
- **Testes isolados**: Cada teste usa banco limpo
- **Testes de integração**: Verificam funcionalidades completas
- **Script de limpeza**: Remove dados de teste automaticamente

### 📚 Documentação
- **README.md**: Documentação completa do projeto
- **CHANGELOG.md**: Histórico de mudanças
- **Comentários**: Código documentado adequadamente

### 🚀 Como Usar

#### Primeira vez:
```bash
composer install
composer run setup
```

#### Uso diário:
```bash
# Produção
composer run listar
composer run inserir
composer run excluir

# Desenvolvimento
composer run test
composer run test:clean
```

### ✅ Benefícios da Reorganização

1. **Manutenibilidade**: Código mais fácil de manter e entender
2. **Escalabilidade**: Estrutura preparada para crescimento
3. **Testabilidade**: Testes organizados e isolados
4. **Configurabilidade**: Configurações centralizadas e flexíveis
5. **Documentação**: Projeto bem documentado
6. **Padrões**: Seguindo boas práticas da comunidade PHP 
## [2.0.0] - 2025-06-21

### 🏗️ Nova Arquitetura MVC Implementada

#### ✨ Principais Mudanças

1. **Padrão MVC Implementado**
   - Controller: `src/Infrastructure/Web/StudentController.php`
   - View: `public/index.php` (simplificado)
   - Bootstrap: `public/bootstrap.php` (configuração)

2. **Separação de Responsabilidades**
   - Lógica de negócio movida para o Controller
   - View apenas para apresentação
   - CSS separado em `public/assets/css/style.css`

3. **Estrutura de Arquivos Melhorada**
   ```
   src/Infrastructure/Web/     # Nova pasta para controllers
   public/assets/css/          # Assets organizados
   public/bootstrap.php        # Configuração centralizada
   ```

#### 🚀 Novos Comandos do Servidor

```bash
# Gerenciamento do servidor web
composer run server:start      # Iniciar servidor
composer run server:stop       # Parar servidor
composer run server:status     # Verificar status
composer run server:restart    # Reiniciar servidor
```

#### 📊 Benefícios da Nova Arquitetura

1. **Manutenibilidade**: Código mais organizado e fácil de manter
2. **Escalabilidade**: Estrutura pronta para novos controllers
3. **Testabilidade**: Cada componente pode ser testado isoladamente
4. **Reutilização**: Controller pode ser usado por outras views
5. **Padrões**: Seguindo boas práticas MVC da comunidade PHP

#### 🔧 Melhorias Técnicas

- **index.php**: Reduzido de 339 para 115 linhas
- **CSS**: Separado em arquivo externo
- **Controller**: Gerencia toda lógica de negócio
- **Bootstrap**: Configuração centralizada
- **Autoload**: Atualizado para nova estrutura

#### 📝 Documentação Atualizada

- README.md: Inclui nova estrutura MVC
- README-FRONTEND.md: Detalhes da implementação MVC
- Comandos do servidor documentados
- Fluxo de dados explicado

### 🎯 Próximos Passos

- [ ] Implementar edição de alunos
- [ ] Adicionar busca e filtros
- [ ] Criar API REST
- [ ] Implementar testes automatizados para o front-end
