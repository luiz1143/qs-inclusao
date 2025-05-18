<?php
// Funções para gerenciamento de artigos

// Obter todos os artigos
function getAllArticles($conn) {
    $articles = array();
    
    $sql = "SELECT * FROM articles ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $articles[] = $row;
        }
    }
    
    return $articles;
}

// Obter um artigo pelo ID
function getArticleById($conn, $id) {
    $sql = "SELECT * FROM articles WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 1) {
                return mysqli_fetch_assoc($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    return false;
}

// Adicionar um novo artigo
function addArticle($conn, $title, $date, $excerpt, $content) {
    $sql = "INSERT INTO articles (title, date, excerpt, content) VALUES (?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $title, $date, $excerpt, $content);
        
        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }
    
    return false;
}

// Atualizar um artigo existente
function updateArticle($conn, $id, $title, $date, $excerpt, $content) {
    $sql = "UPDATE articles SET title = ?, date = ?, excerpt = ?, content = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $title, $date, $excerpt, $content, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }
    
    return false;
}

// Excluir um artigo
function deleteArticle($conn, $id) {
    $sql = "DELETE FROM articles WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }
    
    return false;
}

// Gerar arquivo HTML para um artigo
function generateArticleHTML($article, $outputDir) {
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

// Atualizar a página principal do blog
function updateBlogPage($conn, $outputDir) {
    $articles = getAllArticles($conn);
    
    $blogCards = '';
    foreach ($articles as $article) {
        $blogCards .= '
        <div class="blog-card">
            <div class="blog-card-content">
                <h2>' . htmlspecialchars($article['title']) . '</h2>
                <p class="blog-date">' . htmlspecialchars($article['date']) . '</p>
                <p class="blog-excerpt">' . htmlspecialchars($article['excerpt']) . '</p>
                <a href="blog-post-' . $article['id'] . '.html" class="read-more">Ler mais</a>
            </div>
        </div>';
    }
    
    $filename = $outputDir . "/blog.html";
    
    $html = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qs Inclusão - Blog</title>
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
            </ul>
        </nav>
    </header>

    <main>
        <section class="blog-header">
            <div class="section-container">
                <h1>Blog de Inclusão</h1>
                <p class="blog-description">
                    Compartilhamos informações, dicas e reflexões sobre inclusão, acessibilidade e diversidade.
                    Acompanhe nosso conteúdo e fique por dentro das melhores práticas para promover uma sociedade mais inclusiva.
                </p>
            </div>
        </section>

        <section class="blog-posts-list">
            <div class="section-container">
                <div class="posts-grid">
                    ' . $blogCards . '
                </div>
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

// Atualizar a seção de blog na página inicial
function updateHomeBlogSection($conn, $outputDir) {
    $articles = getAllArticles($conn);
    $previewArticles = array_slice($articles, 0, 2); // Pegar apenas os 2 primeiros artigos
    
    $blogCards = '';
    foreach ($previewArticles as $article) {
        $blogCards .= '
        <div class="blog-card">
            <div class="blog-card-content">
                <h3>' . htmlspecialchars($article['title']) . '</h3>
                <p class="blog-date">' . htmlspecialchars($article['date']) . '</p>
                <p class="blog-excerpt">' . htmlspecialchars($article['excerpt']) . '</p>
                <a href="blog-post-' . $article['id'] . '.html" class="read-more">Ler mais</a>
            </div>
        </div>';
    }
    
    return $blogCards;
}
