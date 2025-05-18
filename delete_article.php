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

// Processar a exclusão quando confirmada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Excluir o artigo
    if (deleteArticle($conn, $id)) {
        // Atualizar a página principal do blog
        $site_dir = "/var/www/html/qs_inclusao_site"; // Diretório do site estático
        updateBlogPage($conn, $site_dir);
        
        // Definir mensagem de sucesso
        $_SESSION['message'] = "Artigo excluído com sucesso!";
        $_SESSION['message_type'] = "success";
        
        // Redirecionar para a página principal
        header("location: index.php");
        exit;
    } else {
        echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
    }
    
    // Fechar conexão
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Artigo - Sistema de Gerenciamento de Blog</title>
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
            <h2>Excluir Artigo</h2>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div>

        <div class="delete-confirmation">
            <h3>Tem certeza que deseja excluir este artigo?</h3>
            <p><strong>Título:</strong> <?php echo htmlspecialchars($article["title"]); ?></p>
            <p><strong>Data:</strong> <?php echo htmlspecialchars($article["date"]); ?></p>
            <p><strong>Resumo:</strong> <?php echo htmlspecialchars($article["excerpt"]); ?></p>
            
            <div class="confirmation-buttons">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post">
                    <input type="submit" class="btn btn-danger" value="Sim, Excluir">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
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
