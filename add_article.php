<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Definir variáveis e inicializar com valores vazios
$title = $date = $excerpt = $content = "";
$title_err = $date_err = $excerpt_err = $content_err = "";

// Processar dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validar título
    if (empty(trim($_POST["title"]))) {
        $title_err = "Por favor, informe o título do artigo.";
    } else {
        $title = trim($_POST["title"]);
    }
    
    // Validar data
    if (empty(trim($_POST["date"]))) {
        $date_err = "Por favor, informe a data do artigo.";
    } else {
        $date = trim($_POST["date"]);
    }
    
    // Validar resumo
    if (empty(trim($_POST["excerpt"]))) {
        $excerpt_err = "Por favor, informe o resumo do artigo.";
    } else {
        $excerpt = trim($_POST["excerpt"]);
    }
    
    // Validar conteúdo
    if (empty(trim($_POST["content"]))) {
        $content_err = "Por favor, informe o conteúdo do artigo.";
    } else {
        $content = trim($_POST["content"]);
    }
    
    // Verificar os erros de entrada antes de inserir no banco de dados
    if (empty($title_err) && empty($date_err) && empty($excerpt_err) && empty($content_err)) {
        
        // Adicionar o artigo
        if (addArticle($conn, $title, $date, $excerpt, $content)) {
            // Obter o ID do artigo recém-adicionado
            $article_id = mysqli_insert_id($conn);
            
            // Obter o artigo completo
            $article = getArticleById($conn, $article_id);
            
            // Gerar o arquivo HTML do artigo
            $site_dir = "/var/www/html/qs_inclusao_site"; // Diretório do site estático
            generateArticleHTML($article, $site_dir);
            
            // Atualizar a página principal do blog
            updateBlogPage($conn, $site_dir);
            
            // Definir mensagem de sucesso
            $_SESSION['message'] = "Artigo adicionado com sucesso!";
            $_SESSION['message_type'] = "success";
            
            // Redirecionar para a página principal
            header("location: index.php");
            exit;
        } else {
            echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
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
    <title>Adicionar Artigo - Sistema de Gerenciamento de Blog</title>
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
            <h2>Adicionar Novo Artigo</h2>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Data (ex: Maio 2025)</label>
                <input type="text" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                <span class="invalid-feedback"><?php echo $date_err; ?></span>
            </div>
            <div class="form-group">
                <label>Resumo</label>
                <textarea name="excerpt" class="form-control <?php echo (!empty($excerpt_err)) ? 'is-invalid' : ''; ?>"><?php echo $excerpt; ?></textarea>
                <span class="invalid-feedback"><?php echo $excerpt_err; ?></span>
            </div>
            <div class="form-group">
                <label>Conteúdo (HTML permitido)</label>
                <textarea name="content" class="form-control content-editor <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" rows="10"><?php echo $content; ?></textarea>
                <span class="invalid-feedback"><?php echo $content_err; ?></span>
                <div class="html-tips">
                    <p>Dicas de formatação HTML:</p>
                    <ul>
                        <li>&lt;p&gt;Parágrafo&lt;/p&gt;</li>
                        <li>&lt;h3&gt;Subtítulo&lt;/h3&gt;</li>
                        <li>&lt;ul&gt;&lt;li&gt;Item de lista&lt;/li&gt;&lt;/ul&gt;</li>
                        <li>&lt;strong&gt;Texto em negrito&lt;/strong&gt;</li>
                        <li>&lt;em&gt;Texto em itálico&lt;/em&gt;</li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Adicionar Artigo">
            </div>
        </form>
    </main>

    <footer class="admin-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Qs Inclusão - Sistema de Gerenciamento de Blog</p>
        </div>
    </footer>
</body>
</html>
