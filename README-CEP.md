# Funcionalidade de CEP - Gerenciador de Alunos

## Novas Funcionalidades Implementadas

### 1. Campos Adicionados
- **CEP**: Campo para armazenar o CEP do aluno
- **Endereço**: Campo para armazenar o endereço completo do aluno

### 2. Integração com API dos Correios
- Utiliza a API gratuita ViaCEP (https://viacep.com.br/)
- Busca automática do endereço ao informar o CEP
- Formatação automática do endereço completo

### 3. Interface Web Melhorada
- Campo de CEP com máscara automática (00000-000)
- Botão "Buscar" para consultar o endereço via API
- Campo de endereço preenchido automaticamente
- Tabela de listagem com colunas de CEP e Endereço

### 4. Scripts Atualizados
- `inserir-aluno.php`: Agora inclui campos de CEP e endereço
- `atualizar-tabela.php`: Script para adicionar novas colunas ao banco existente

## Como Usar

### Via Interface Web
1. Acesse http://localhost:8000
2. Preencha o nome e data de nascimento
3. Digite o CEP no formato 00000-000
4. Clique em "🔍 Buscar" para preencher o endereço automaticamente
5. Clique em "Inserir Aluno"

### Via Script CLI
```bash
php scripts/inserir-aluno.php
```

## Estrutura do Banco de Dados

A tabela `students` foi atualizada com as seguintes colunas:
- `id` (INTEGER PRIMARY KEY)
- `name` (TEXT)
- `birth_date` (TEXT)
- `cep` (TEXT) - **NOVO**
- `address` (TEXT) - **NOVO**

## Arquivos Modificados/Criados

### Modelo
- `src/Domain/Model/Student.php` - Adicionados campos cep e address

### Repositório
- `src/Infrastructure/Repository/PdoStudentRepository.php` - Atualizado para incluir novos campos

### Serviços
- `src/Infrastructure/Service/CepService.php` - **NOVO** - Serviço de integração com API dos Correios

### Controlador
- `src/Infrastructure/Web/StudentController.php` - Adicionada funcionalidade de busca de CEP

### Interface
- `public/index.php` - Formulário atualizado com campos de CEP e endereço
- `public/assets/css/style.css` - Estilos para novos elementos

### Scripts
- `scripts/atualizar-tabela.php` - **NOVO** - Script para atualizar banco existente
- `scripts/inserir-aluno.php` - Atualizado para incluir novos campos

## API Utilizada

- **ViaCEP**: https://viacep.com.br/ws/{cep}/json/
- Gratuita e sem necessidade de chave de API
- Retorna dados completos do endereço

## Exemplo de Resposta da API

```json
{
  "cep": "01001-000",
  "logradouro": "Praça da Sé",
  "complemento": "lado ímpar",
  "bairro": "Sé",
  "localidade": "São Paulo",
  "uf": "SP",
  "ibge": "3550308",
  "gia": "1004",
  "ddd": "11",
  "siafi": "7107"
}
```

## Tratamento de Erros

- CEP inválido (menos de 8 dígitos)
- CEP não encontrado na base dos Correios
- Erros de conexão com a API
- Campos opcionais (CEP e endereço podem ficar vazios)

## Responsividade

A interface foi adaptada para dispositivos móveis:
- Campos de CEP empilhados em telas pequenas
- Tabela responsiva com scroll horizontal
- Botões adaptados para touch 