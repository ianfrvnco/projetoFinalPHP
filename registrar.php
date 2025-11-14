<?php
include_once './config/config.php';
include_once './classes/Usuario.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $senha1 = $_POST['senha'];
    $senha2 = $_POST['senhaConfirma'];

    if ($senha1 !== $senha2) {
    echo "As senhas não coincidem.";
    exit();
}

    $usuario = new Usuario($db);
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $senha1;
    $usuario->criar($nome, $email, $senha);
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Usuário</title>
</head>
<body>
    <h1>Adicionar Usuário</h1>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required>
        <br><br>
        <label for="senha">Confirmação de senha:</label>
        <input type="password" name="senhaConfirma" required>
        <br><br>
        <input type="submit" value="Adicionar">
    </form>
</body>
</html>
