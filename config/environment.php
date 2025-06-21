<?php

/**
 * Configuração de ambiente
 * Define constantes e configurações baseadas no ambiente
 */

// Detectar ambiente
$environment = getenv('APP_ENV') ?: 'development';

// Configurações por ambiente
$configs = [
    'development' => [
        'debug' => true,
        'database' => __DIR__ . '/../database/banco.sqlite',
        'test_database' => __DIR__ . '/../database/banco-teste.sqlite',
    ],
    'production' => [
        'debug' => false,
        'database' => __DIR__ . '/../database/banco.sqlite',
        'test_database' => __DIR__ . '/../database/banco-teste.sqlite',
    ],
    'testing' => [
        'debug' => true,
        'database' => __DIR__ . '/../database/banco-teste.sqlite',
        'test_database' => __DIR__ . '/../database/banco-teste.sqlite',
    ]
];

// Definir configuração atual
$currentConfig = $configs[$environment] ?? $configs['development'];

// Definir constantes
define('APP_ENV', $environment);
define('APP_DEBUG', $currentConfig['debug']);
define('DATABASE_PATH', $currentConfig['database']);
define('TEST_DATABASE_PATH', $currentConfig['test_database']);

return $currentConfig; 