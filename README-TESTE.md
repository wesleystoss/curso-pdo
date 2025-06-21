# Configuração de Banco de Teste

Este projeto agora suporta execução de testes em um banco de dados separado para não afetar os dados de produção.

## Arquivos Criados

1. **`config-teste.php`** - Configuração centralizada para testes
2. **`teste-repository.php`** - Versão modificada do teste original (usa banco de teste)
3. **`teste-repository-melhorado.php`** - Versão melhorada com limpeza automática
4. **`limpar-banco-teste.php`** - Script para remover o banco de teste

## Como Usar

### Opção 1: Teste Simples
```bash
php teste-repository.php
```
- Usa o banco `banco-teste.sqlite`
- Cria a tabela automaticamente
- Mantém os dados após a execução

### Opção 2: Teste Melhorado (Recomendado)
```bash
php teste-repository-melhorado.php
```
- Limpa o banco antes de cada execução
- Usa configuração centralizada
- Garante testes isolados

### Opção 3: Limpar Banco de Teste
```bash
php limpar-banco-teste.php
```
- Remove completamente o arquivo do banco de teste

## Vantagens

1. **Isolamento**: Testes não afetam dados de produção
2. **Reprodutibilidade**: Cada execução começa com banco limpo
3. **Segurança**: Não há risco de perder dados importantes
4. **Flexibilidade**: Fácil de configurar e usar

## Estrutura dos Arquivos

```
curso-pdo/
├── banco.sqlite          # Banco de produção
├── banco-teste.sqlite    # Banco de teste (criado automaticamente)
├── config-teste.php      # Configuração de teste
├── teste-repository.php  # Teste modificado
├── teste-repository-melhorado.php  # Teste melhorado
└── limpar-banco-teste.php # Script de limpeza
```

## Configuração

Para alterar o caminho do banco de teste, edite a constante `TEST_DATABASE_PATH` no arquivo `config-teste.php`. 