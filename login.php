<?php
session_start();
// 1. Conexão com o banco
include_once "./classes/Database.php"; 
$database = new Database(); 
$db = $database->getConnection();

include_once './config/config.php';
include_once './classes/Usuario.php';
include './templates/header.php';

$usuario = new Usuario($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Processar login
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        if ($dados_usuario = $usuario->login($email, $senha)) {
            $_SESSION['usuario'] = $dados_usuario['id'];
            header('Location: index.php');
            exit();
        } else {
            $mensagem_erro = "Credenciais inválidas!";
        }
    }
}

?>

    <div class="container">

        <div class="box">
            <h1>LOGIN</h1>

            <form method="POST">
                <input type="email" name="email" placeholder="EMAIL" required>
                <br><br>
                <input type="password" name="senha" placeholder="SENHA" required>
                <br><br>
                <input type="submit" name="login" value="Login">
            </form>
            <div class="mensagem">
                <?php if (isset($mensagem_erro)) echo '<p>' . $mensagem_erro . '</p>'; ?>
            </div>
        </div>

<?php 
include 'templates/footer.php';
?>

</html>