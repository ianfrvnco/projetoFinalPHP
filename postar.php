<?php
session_start();
include_once "./classes/Database.php";
$database = new Database();
$db = $database->getConnection();

include_once './config/config.php';
include_once './classes/Usuario.php';
include_once './classes/Noticia.php';
include './templates/headerConectado.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$noticia = new Noticia($db);
$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);
    $imagem = trim($_POST['imagem']);
    $usuario_id = $_SESSION['usuario'];

    if (empty($titulo) || empty($conteudo)) {
        $erro = "Título e conteúdo não podem estar vazios.";
    } else {

        if (empty($imagem)) {
            $noticia->registrarSemImagem($titulo, $conteudo, $usuario_id);
        } else {
            $noticia->registrarComImagem($titulo, $conteudo, $imagem, $usuario_id);    
        }
        
        $sucesso = "Notícia publicada com sucesso!";
        header('Location: index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Postar Notícia</title>
    
</head>

<body>
    <div class="containerPostar">
        <div class="boxPostar">
            <h1>Postar Notícia</h1>
            <?php if ($erro): ?>
                <div class="erro"><?php echo $erro; ?></div><?php endif; ?>
            <?php if ($sucesso): ?>
                <div class="sucesso"><?php echo $sucesso; ?></div><?php endif; ?>
            <form method="POST">
                <input type="text" name="titulo" placeholder="Título da notícia">
                <textarea name="conteudo" rows="8" placeholder="Escreva sua notícia aqui..."></textarea>
                <input type="text" name="imagem" placeholder="URL da imagem">
                <button type="submit">Publicar</button>
            </form>
        </div>
    </div>
</body>

</html>