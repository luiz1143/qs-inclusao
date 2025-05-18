<?php
session_start();
require_once 'config.php';

// Verificar se o usuário já está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: index.php");
    exit;
}

// Definir variáveis e inicializar com valores vazios
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processar dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verificar se o nome de usuário está vazio
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, informe o nome de usuário.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Verificar se a senha está vazia
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, informe a senha.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validar credenciais
    if (empty($username_err) && empty($password_err)) {
        // Preparar a declaração select
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Vincular variáveis à instrução preparada como parâmetros
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Definir parâmetros
            $param_username = $username;
            
            // Tentar executar a declaração preparada
            if (mysqli_stmt_execute($stmt)) {
                // Armazenar resultado
                mysqli_stmt_store_result($stmt);
                
                // Verificar se o nome de usuário existe, se sim, verificar a senha
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Vincular variáveis de resultado
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Senha está correta, iniciar uma nova sessão
                            session_start();
                            
                            // Armazenar dados em variáveis de sessão
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            
                            // Redirecionar o usuário para a página de boas-vindas
                            header("location: index.php");
                        } else {
                            // Senha não é válida, exibir mensagem de erro genérica
                            $login_err = "Nome de usuário ou senha inválidos.";
                        }
                    }
                } else {
                    // Nome de usuário não existe, exibir mensagem de erro genérica
                    $login_err = "Nome de usuário ou senha inválidos.";
                }
            } else {
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            mysqli_stmt_close($stmt);
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
    <title>Login - Sistema de Gerenciamento de Blog</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h2>Sistema de Gerenciamento de Blog</h2>
            <h3>Qs Inclusão</h3>
        </div>

        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nome de Usuário</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Entrar">
            </div>
            <p class="login-info">Usuário padrão: admin<br>Senha padrão: qsinclusao2025</p>
        </form>
    </div>
</body>
</html>
