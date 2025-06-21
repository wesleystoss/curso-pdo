# 📚 Documentação da API REST

Esta documentação descreve os endpoints da API REST do Sistema de Gerenciamento de Alunos.

## 🔗 Base URL

```
http://localhost:8000/api
```

## 📋 Endpoints

### 1. Listar Todos os Alunos

**GET** `/students`

Retorna uma lista de todos os alunos cadastrados.

#### Resposta de Sucesso (200)

```json
[
    {
        "id": 1,
        "name": "João Silva",
        "birth_date": "1990-01-01",
        "age": 33,
        "cep": "12345678",
        "address": "Rua das Flores, 123"
    },
    {
        "id": 2,
        "name": "Maria Santos",
        "birth_date": "1995-05-15",
        "age": 28,
        "cep": "87654321",
        "address": "Avenida Principal, 456"
    }
]
```

### 2. Buscar Aluno por ID

**GET** `/students/{id}`

Retorna os dados de um aluno específico.

#### Parâmetros

- `id` (integer, obrigatório): ID do aluno

#### Resposta de Sucesso (200)

```json
{
    "id": 1,
    "name": "João Silva",
    "birth_date": "1990-01-01",
    "age": 33,
    "cep": "12345678",
    "address": "Rua das Flores, 123"
}
```

#### Resposta de Erro (404)

```json
{
    "error": "Aluno não encontrado",
    "status_code": 404
}
```

### 3. Criar Novo Aluno

**POST** `/students`

Cria um novo aluno no sistema.

#### Corpo da Requisição

```json
{
    "name": "João Silva",
    "birth_date": "1990-01-01",
    "cep": "12345678",
    "address": "Rua das Flores, 123"
}
```

#### Campos Obrigatórios

- `name` (string): Nome completo do aluno (mínimo 2 caracteres)
- `birth_date` (string): Data de nascimento no formato YYYY-MM-DD

#### Campos Opcionais

- `cep` (string): CEP do aluno (8 dígitos)
- `address` (string): Endereço completo

#### Resposta de Sucesso (201)

```json
{
    "id": 3,
    "name": "João Silva",
    "birth_date": "1990-01-01",
    "age": 33,
    "cep": "12345678",
    "address": "Rua das Flores, 123"
}
```

#### Resposta de Erro (400)

```json
{
    "error": "Nome inválido: 'J'. O nome deve ter pelo menos 2 caracteres.",
    "status_code": 400
}
```

### 4. Atualizar Aluno

**PUT** `/students/{id}`

Atualiza os dados de um aluno existente.

#### Parâmetros

- `id` (integer, obrigatório): ID do aluno

#### Corpo da Requisição

```json
{
    "name": "João Silva Santos",
    "birth_date": "1990-01-01",
    "cep": "87654321",
    "address": "Nova Rua, 789"
}
```

**Nota:** Todos os campos são opcionais. Apenas os campos enviados serão atualizados.

#### Resposta de Sucesso (200)

```json
{
    "id": 1,
    "name": "João Silva Santos",
    "birth_date": "1990-01-01",
    "age": 33,
    "cep": "87654321",
    "address": "Nova Rua, 789"
}
```

### 5. Excluir Aluno

**DELETE** `/students/{id}`

Remove um aluno do sistema.

#### Parâmetros

- `id` (integer, obrigatório): ID do aluno

#### Resposta de Sucesso (200)

```json
{
    "message": "Aluno excluído com sucesso"
}
```

### 6. Estatísticas

**GET** `/stats`

Retorna estatísticas gerais do sistema.

#### Resposta de Sucesso (200)

```json
{
    "total_students": 150,
    "adults": 120,
    "minors": 30,
    "average_age": 25.5,
    "cache_stats": {
        "total_files": 5,
        "total_size": 1024,
        "expired_files": 1,
        "valid_files": 4
    }
}
```

## 🔧 Códigos de Status HTTP

| Código | Descrição |
|--------|-----------|
| 200 | OK - Requisição bem-sucedida |
| 201 | Created - Recurso criado com sucesso |
| 400 | Bad Request - Dados inválidos |
| 404 | Not Found - Recurso não encontrado |
| 405 | Method Not Allowed - Método não permitido |
| 500 | Internal Server Error - Erro interno do servidor |

## 🛡️ Validações

### Nome
- Mínimo 2 caracteres
- Apenas letras, espaços e acentos
- Obrigatório

### Data de Nascimento
- Formato: YYYY-MM-DD
- Deve ser uma data válida
- Não pode ser no futuro
- Obrigatório

### CEP
- Exatamente 8 dígitos numéricos
- Opcional
- Se fornecido, deve ser válido

### ID
- Deve ser um número inteiro positivo
- Obrigatório para operações de atualização e exclusão

## 🚀 Exemplos de Uso

### cURL

#### Listar todos os alunos
```bash
curl -X GET http://localhost:8000/api/students
```

#### Criar novo aluno
```bash
curl -X POST http://localhost:8000/api/students \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "birth_date": "1990-01-01",
    "cep": "12345678",
    "address": "Rua das Flores, 123"
  }'
```

#### Atualizar aluno
```bash
curl -X PUT http://localhost:8000/api/students/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva Santos"
  }'
```

#### Excluir aluno
```bash
curl -X DELETE http://localhost:8000/api/students/1
```

### JavaScript (Fetch)

#### Listar todos os alunos
```javascript
fetch('http://localhost:8000/api/students')
  .then(response => response.json())
  .then(data => console.log(data));
```

#### Criar novo aluno
```javascript
fetch('http://localhost:8000/api/students', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'João Silva',
    birth_date: '1990-01-01',
    cep: '12345678',
    address: 'Rua das Flores, 123'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## 🔄 Cache

A API utiliza cache para melhorar a performance:

- **Lista de alunos**: Cache de 5 minutos
- **Estatísticas**: Cache de 10 minutos
- **Cache é invalidado** automaticamente quando há alterações nos dados

## 📝 Logs

Todas as operações da API são registradas em logs para auditoria:

- Criação de alunos
- Atualização de alunos
- Exclusão de alunos
- Erros de validação
- Erros internos

## 🚨 Tratamento de Erros

A API retorna mensagens de erro detalhadas em português:

```json
{
    "error": "Nome inválido: 'J'. O nome deve ter pelo menos 2 caracteres.",
    "status_code": 400
}
```

## 🔒 CORS

A API suporta CORS para requisições cross-origin:

- **Access-Control-Allow-Origin**: `*`
- **Access-Control-Allow-Methods**: `GET, POST, PUT, DELETE, OPTIONS`
- **Access-Control-Allow-Headers**: `Content-Type`

## 📊 Rate Limiting

A API implementa rate limiting para prevenir abuso:

- **Limite padrão**: 100 requisições por hora
- **Configurável** via variável de ambiente `API_RATE_LIMIT` 