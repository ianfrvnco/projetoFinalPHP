<?php
session_start();
include_once "./classes/Database.php";
$database = new Database();
$db = $database->getConnection();

include_once './config/config.php';
include_once './classes/Noticia.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$noticia = new Noticia($db);

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID da notícia não informado.");
}

$id = (int) $_GET['id'];

// Busca a notícia para verificar se o usuário é o autor
$stmt = $db->prepare("SELECT usuario_id FROM noticias WHERE id = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    die("Notícia não encontrada.");
}

// Só permite excluir se for o autor
if ($dados['usuario_id'] != $_SESSION['usuario']) {
    die("Você não tem permissão para excluir esta notícia.");
}

// Exclui a notícia
$noticia->deletar($id);

// Redireciona para index.php
header("Location: index.php");
exit();
