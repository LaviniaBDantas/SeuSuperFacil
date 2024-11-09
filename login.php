<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['login']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        echo "<script>
                sessionStorage.setItem('error', 'Email e senha são obrigatórios.');
                window.location.href = 'login.html';
              </script>";
        exit;
    }

    $sql = "SELECT * FROM cliente WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senha, $cliente['senha'])) {
            $_SESSION['user_id'] = $cliente['cpf'];
            header("Location: home.html");
            exit;
        } else {
            echo "<script>
                    sessionStorage.setItem('error', 'Senha incorreta.');
                    window.location.href = 'login.html';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                sessionStorage.setItem('error', 'Email não encontrado.');
                window.location.href = 'login.html';
              </script>";
        exit;
    }
} else {
    echo "<script>
            sessionStorage.setItem('error', 'Método POST não foi usado.');
            window.location.href = 'login.html';
          </script>";
    exit;
}
?>
