<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" href="../imagens/login.png" type="image/png">
    <link rel="stylesheet" href="styleLogin.css" type="text/css">
</head>
<body>
    <?php session_start(); ?>
    
    <div class="caixa">
        <h1 style="text-align:center; color: black">Entre ou cadastre-se</h1>

        <p id="error-message" style="color:red;"></p>

        <form id="login-form" action="login.php" method="POST">
            <label for="login"><b>Email:</b></label>
            <input type="text" name="login" id="login" required/><br><br>

            <label for="senha"><b>Senha:</b></label>
            <input type="password" name="senha" id="senha" required/><br><br>

            <button type="submit">Entrar</button>
        </form>

        <button id="register-btn" onclick="window.location.href='cadastro.html'">Fazer cadastro</button>
        <button id="dados-btn" onclick="window.location.href='dados.php'">Meus dados</button>
        <button id="logout-btn" onclick="window.location.href='logout.php'">Sair</button>
    </div>

    <script>
        //verificando se usuario tá logado
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        
        document.getElementById('login-form').style.display = isLoggedIn ? 'none' : 'block';
        document.getElementById('register-btn').style.display = isLoggedIn ? 'none' : 'block';
        document.getElementById('dados-btn').style.display = isLoggedIn ? 'block' : 'none';
        document.getElementById('logout-btn').style.display = isLoggedIn ? 'block' : 'none';

        const errorMessage = sessionStorage.getItem('error');
        if (errorMessage) {
            document.getElementById('error-message').textContent = errorMessage;
            sessionStorage.removeItem('error');
        }
    </script>
</body>
</html>
