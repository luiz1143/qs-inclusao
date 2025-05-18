<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Verificar se o ID do artigo foi fornecido
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("location: index.php");
    exit;
}

// Obter o ID do artigo
$id = $_GET["id"];

// Obter o artigo pelo ID
$article = getArticleById($conn, $id);

// Verificar se o artigo existe
if (!$article) {
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Artigo - Sistema de Gerenciamento de Blog</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1>Sistema de Gerenciamento de Blog - Qs Inclusão</h1>
            <div class="user-info">
                <span>Olá, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn btn-logout">Sair</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="page-header">
            <h2>Visualizar Artigo</h2>
            <div class="action-buttons">
                <a href="index.php" class="btn btn-secondary">Voltar</a>
                <a href="edit_article.php?id=<?php echo $id; ?>" class="btn btn-edit">Editar</a>
                <a href="delete_article.php?id=<?php echo $id; ?>" class="btn btn-delete">Excluir</a>
            </div>
        </div>

        <div class="article-view">
            <h3><?php echo htmlspecialchars($article["title"]); ?></h3>
            <p class="article-date"><?php echo htmlspecialchars($article["date"]); ?></p>
            
            <div class="article-section">
                <h4>Resumo:</h4>
                <p><?php echo htmlspecialchars($article["excerpt"]); ?></p>
            </div>
            
            <div class="article-section">
                <h4>Conteúdo:</h4>
                <div class="article-content">
                    <?php echo $article["content"]; ?>
                </div>
            </div>
            
            <div class="article-preview">
                <h4>Visualização no site:</h4>
                <p>Veja como este artigo aparece no <a href="https://cubxtgis.manus.space/blog-post-<?php echo $id; ?>.html" target="_blank">site público</a>.</p>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Qs Inclusão - Sistema de Gerenciamento de Blog</p>
        </div>
    </footer>
</body>
</html>
