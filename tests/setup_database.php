<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/banco-teste.sqlite');
$db->exec('
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        birth_date TEXT NOT NULL,
        cep TEXT,
        address TEXT
    );
');
echo "Tabela students criada/verificada com sucesso no banco de teste.\n"; 