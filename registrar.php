<?php
session_start();
include_once './config/config.php';
include_once './classes/Usuario.php';

if (!isset($_SESSION['usuario'])) {
    include 'templates/header.php';
} else {
    include 'templates/headerConectado.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha1 = $_POST['senha'];
    $senha2 = $_POST['senhaConfirma'];

    if ($senha1 !== $senha2) {
        $mensagem_erro = "As senhas não coincidem.";
    } else {
        $usuario = new Usuario($db);
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $senha1;
        $usuario->criar($nome, $email, $senha);
        header('Location: index.php');
        exit();
    }
}
?>
<div class="containerCadastro">
    <div class="boxCadastro">
        <h1>Registrar</h1>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="password" name="senhaConfirma" placeholder="Confirme a senha" required>
            <input type="submit" value="Adicionar">
        </form>
        <div class="mensagemCadastro">
            <?php if (isset($mensagem_erro)) echo $mensagem_erro; ?>
        </div>
    </div>
</div>
</body>

</html>