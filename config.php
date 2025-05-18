<?php
// Configurações do banco de dados para o site publicado
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'qs_inclusao_blog');

// Tentativa de conexão com o banco de dados MySQL
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Verificar conexão
if (!$conn) {
    die("ERRO: Não foi possível conectar ao MySQL. " . mysqli_connect_error());
}

// Criar banco de dados se não existir
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $sql)) {
    // Selecionar o banco de dados
    mysqli_select_db($conn, DB_NAME);
    
    // Criar tabela de usuários se não existir
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $sql)) {
        die("ERRO: Não foi possível criar a tabela de usuários. " . mysqli_error($conn));
    }
    
    // Criar tabela de artigos se não existir
    $sql = "CREATE TABLE IF NOT EXISTS articles (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        date VARCHAR(50) NOT NULL,
        excerpt TEXT NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $sql)) {
        die("ERRO: Não foi possível criar a tabela de artigos. " . mysqli_error($conn));
    }
    
    // Verificar se já existe um usuário admin
    $sql = "SELECT id FROM users WHERE username = 'admin' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        // Criar usuário admin padrão
        $default_password = password_hash("qsinclusao2025", PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password) VALUES ('admin', '$default_password')";
        
        if (!mysqli_query($conn, $sql)) {
            die("ERRO: Não foi possível criar o usuário admin. " . mysqli_error($conn));
        }
    }
} else {
    die("ERRO: Não foi possível criar o banco de dados. " . mysqli_error($conn));
}
