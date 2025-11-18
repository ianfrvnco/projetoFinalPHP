<?php
session_start();
require_once './config/config.php'; // Conexão com o banco
require_once './classes/Usuario.php'; // Classe que acessa o banco

$email = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica se os campos foram preenchidos
if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = 'Preencha todos os campos.';
    header('Location: index.php');
    exit;
}

$usuario = new Usuario($db);
$dados = $usuario->buscarPorEmail($email);

if ($dados && password_verify($senha, $dados['senha'])) {
    $_SESSION['usuario'] = $dados['nome']; // ou outro identificador

    if (isset($_POST['lembrar'])) {
        setcookie('usuario', $email, time() + (60 * 60 * 24 * 30), "/");
    } else {
        setcookie('usuario', '', time() - 3600, "/");
    }

    header('Location: dashboard.php');
    exit;
} else {
    $_SESSION['erro'] = 'Email ou senha inválidos.';
    header('Location: index.php');
    exit;
}
