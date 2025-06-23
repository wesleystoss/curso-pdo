# 🔔 Sistema de Notificações

Este documento explica como configurar, usar e testar o sistema de notificações do projeto.

## 📋 Configuração

### 1. Variáveis de Ambiente

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# Sistema de Notificações
notifications_enabled=true
admin_email=seu-email@exemplo.com
app_email=noreply@exemplo.com
app_name=Gerenciador de Alunos
```

### 2. Configurações do Servidor

Para que as notificações por email funcionem, certifique-se de que:

- A função `mail()` do PHP está habilitada
- O servidor SMTP está configurado corretamente
- As extensões necessárias estão instaladas

## 🧪 Como Testar as Notificações

### Método 2: Via Script de Teste

Execute o script de teste via terminal:

```bash
php scripts/teste-notificacoes.php
```

Este script irá:
- Verificar as configurações
- Testar notificações de novo aluno
- Testar notificações de exclusão
- Testar notificações de erro
- Testar emails personalizados
- Testar notificações do navegador
- Executar teste completo

### Método 3: Via API

Você pode testar as notificações programaticamente:

```php
use Alura\Pdo\Infrastructure\Service\NotificationService;

$notifications = NotificationService::getInstance();

// Teste de email
$notifications->sendEmail(
    'destinatario@exemplo.com',
    'Assunto do Email',
    '<h2>Conteúdo HTML</h2><p>Mensagem do email.</p>'
);

// Teste de notificação do navegador
$notifications->sendBrowserNotification(
    'Título da Notificação',
    'Mensagem da notificação'
);
```

## 📧 Notificações por Email

### Tipos de Notificação

1. **Novo Aluno Cadastrado**
   - Assunto: `🎓 Novo Aluno Cadastrado - [Nome do App]`
   - Enviada quando um aluno é criado

2. **Aluno Excluído**
   - Assunto: `🗑️ Aluno Excluído - [Nome do App]`
   - Enviada quando um aluno é removido

3. **Erro do Sistema**
   - Assunto: `🚨 Erro no Sistema - [Nome do App]`
   - Enviada quando ocorre um erro crítico

4. **Email Personalizado**
   - Assunto e conteúdo customizáveis
   - Suporte a templates HTML

### Templates de Email

O sistema suporta dois templates:

#### Template Padrão
```php
$notifications->sendEmail($to, $subject, $message);
```

#### Template Minimal
```php
$notifications->sendEmail($to, $subject, $message, ['template' => 'minimal']);
```

### Opções Avançadas

```php
$notifications->sendEmail($to, $subject, $message, [
    'from' => 'remetente@exemplo.com',
    'reply_to' => 'resposta@exemplo.com',
    'cc' => 'copia@exemplo.com',
    'bcc' => 'copia-oculta@exemplo.com',
    'template' => 'minimal'
]);
```

## 🌐 Notificações do Navegador

### Configuração

1. **Permissão**: O usuário deve permitir notificações
2. **HTTPS**: Deve estar em conexão segura (ou localhost)
3. **Navegador**: Deve estar aberto e ativo

### Solicitar Permissão

```javascript
// Na página de admin, clique em "🔔 Solicitar Permissão"
// Ou use o JavaScript:

async function requestNotificationPermission() {
    if (!('Notification' in window)) {
        alert('Este navegador não suporta notificações');
        return;
    }
    
    const permission = await Notification.requestPermission();
    if (permission === 'granted') {
        console.log('Permissão concedida!');
    }
}
```

### Opções de Notificação

```php
$notifications->sendBrowserNotification($title, $message, [
    'icon' => '/caminho/para/icone.png',
    'badge' => '/caminho/para/badge.png',
    'tag' => 'identificador-unico',
    'data' => ['chave' => 'valor'],
    'requireInteraction' => false,
    'silent' => false
]);
```

## 🔧 Integração com o Sistema

### Notificações Automáticas

O sistema envia notificações automaticamente para:

- **Criação de alunos**: Via API ou interface web
- **Exclusão de alunos**: Via API ou interface web
- **Erros críticos**: Capturados pelo sistema de logs

### Logs de Notificação

Todas as notificações são registradas nos logs:

```php
// Exemplo de log
$logger->info('Email enviado com sucesso', [
    'to' => 'admin@exemplo.com',
    'subject' => 'Novo Aluno Cadastrado'
]);
```

## 🚨 Solução de Problemas

### Email não está sendo enviado

1. **Verifique as configurações**:
   ```bash
   php scripts/teste-notificacoes.php
   ```

2. **Verifique o servidor SMTP**:
   ```php
   // Teste básico
   $result = mail('teste@exemplo.com', 'Teste', 'Mensagem de teste');
   var_dump($result);
   ```

3. **Verifique os logs**:
   ```bash
   tail -f logs/app.log
   ```

### Notificações do navegador não aparecem

1. **Verifique a permissão**:
   ```javascript
   console.log(Notification.permission);
   ```

2. **Verifique se está em HTTPS/localhost**

3. **Verifique se o navegador está ativo**

### Configurações de Debug

Para debug detalhado, ative o modo debug no `.env`:

```env
app_debug=true
```

## 📊 Monitoramento

### Estatísticas de Notificação

O sistema registra estatísticas que podem ser consultadas:

```php
// Via API
GET /public/api.php/stats

// Via código
$cacheStats = Cache::getInstance()->getStats();
```

### Logs de Notificação

Os logs são salvos em:
- `logs/app.log` - Logs gerais
- `logs/error.log` - Logs de erro
- `logs/notification.log` - Logs específicos de notificação

## 🔒 Segurança

### Boas Práticas

1. **Validação de Email**: Sempre valide endereços de email
2. **Rate Limiting**: Implemente limitação de taxa para evitar spam
3. **Sanitização**: Sanitize dados antes de enviar
4. **Logs**: Mantenha logs de todas as notificações

### Configurações de Segurança

```env
# Limitar notificações por hora
notifications_rate_limit=100

# Lista de emails permitidos
allowed_emails=admin@exemplo.com,suporte@exemplo.com

# Modo de teste (não envia emails reais)
notifications_test_mode=false
```

## 📝 Exemplos Práticos

### Exemplo 1: Notificação de Novo Aluno

```php
// No StudentController ou ApiController
public function createStudent($data) {
    $student = new Student($data);
    $repository->save($student);
    
    // Enviar notificação
    $notifications = NotificationService::getInstance();
    $notifications->notifyNewStudent($student->name(), $student->email());
}
```

### Exemplo 2: Notificação de Erro

```php
try {
    // Código que pode gerar erro
} catch (Exception $e) {
    $notifications = NotificationService::getInstance();
    $notifications->notifySystemError($e->getMessage(), 'Contexto do erro');
}
```

### Exemplo 3: Notificação Personalizada

```php
$notifications = NotificationService::getInstance();

// Email com template personalizado
$notifications->sendEmail(
    'usuario@exemplo.com',
    'Bem-vindo ao Sistema',
    '<h1>Olá!</h1><p>Seja bem-vindo ao nosso sistema.</p>',
    ['template' => 'minimal']
);

// Notificação do navegador com dados extras
$notifications->sendBrowserNotification(
    'Nova Mensagem',
    'Você tem uma nova mensagem',
    [
        'tag' => 'mensagem',
        'data' => ['id' => 123],
        'requireInteraction' => true
    ]
);
```

## 🎯 Conclusão

O sistema de notificações oferece uma solução completa para manter os usuários informados sobre eventos importantes do sistema. Com configuração adequada e testes regulares, você pode garantir que as notificações funcionem corretamente em todos os cenários. 