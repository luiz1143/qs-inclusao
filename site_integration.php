<?php
// Ajuste do caminho para o site publicado
$site_dir = "/home/ubuntu/qs_inclusao_site_static";

// Incluir as funções e configurações
require_once 'config.php';
require_once 'functions.php';

// Atualizar as funções para usar o novo diretório
function generateArticleHTML($article, $outputDir = null) {
    if ($outputDir === null) {
        $outputDir = "/home/ubuntu/qs_inclusao_site_static";
    }
    
    $filename = $outputDir . "/blog-post-" . $article['id'] . ".html";
    
    $html = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qs Inclusão - ' . htmlspecialchars($article['title']) . '</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <a href="index.html">
                <img src="images/logo.jpeg" alt="Qs Inclusão Logo" class="logo">
            </a>
        </div>
        <nav class="nav">
            <ul class="nav-list">
                <li class="nav-item"><a href="index.html" class="nav-link">Início</a></li>
                <li class="nav-item"><a href="quem-somos.html" class="nav-link">Quem Somos</a></li>
                <li class="nav-item"><a href="servicos.html" class="nav-link">Serviços</a></li>
                <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="contato.html" class="nav-link">Contato</a></li>
                <li class="nav-item"><a href="admin/" class="nav-link">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="blog-post-full">
            <div class="section-container">
                <a href="blog.html" class="back-button">← Voltar para todos os artigos</a>
                <article class="post-content">
                    <h2>' . htmlspecialchars($article['title']) . '</h2>
                    <p class="post-date">' . htmlspecialchars($article['date']) . '</p>
                    <div class="post-body">
                        ' . $article['content'] . '
                    </div>
                </article>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Qs Inclusão</h3>
                <p>Contribuindo para uma sociedade mais justa e inclusiva.</p>
            </div>
            <div class="footer-section">
                <h3>Contato</h3>
                <p>E-mail: helio@qsinclusao.com.br / luizqsinclusao@gmail.com</p>
                <p>Instagram: <a href="https://www.instagram.com/qsinclusao" target="_blank" rel="noopener noreferrer">@qsinclusao</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <span id="current-year"></span> Qs Inclusão. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        document.getElementById("current-year").textContent = new Date().getFullYear();
    </script>
</body>
</html>';
    
    file_put_contents($filename, $html);
    return $filename;
}
