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

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID da notícia não informado.");
}

$id = (int) $_GET['id'];

// Busca a notícia no banco
$stmt = $db->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->execute([$id]);
$dados_noticia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados_noticia) {
    die("Notícia não encontrada.");
}

// Verifica se o usuário é o autor
if ($dados_noticia['usuario_id'] != $_SESSION['usuario']) {
    die("Você não tem permissão para editar esta notícia.");
}

// Se enviou o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);
    $imagem = trim($_POST['imagem']);

    if (empty($titulo) || empty($conteudo)) {
        $erro = "Título e conteúdo não podem estar vazios.";
    } else {
        if (empty($imagem)) {
            $stmt = $db->prepare("UPDATE noticias SET titulo = ?, conteudo = ?, hora_de_publicacao = NOW() WHERE id = ?");
            $stmt->execute([$titulo, $conteudo, $id]);
        } else {
            $stmt = $db->prepare("UPDATE noticias SET titulo = ?, conteudo = ?, url_imagem = ?, hora_de_publicacao = NOW() WHERE id = ?");
            $stmt->execute([$titulo, $conteudo, $imagem, $id]);
        }

        $sucesso = "Notícia atualizada com sucesso!";
        header('Location: index.php');
        // Atualiza os dados para refletir no formulário
        $dados_noticia['titulo'] = $titulo;
        $dados_noticia['conteudo'] = $conteudo;
        $dados_noticia['imagem'] = $conteudo;
    }
}
?>
<div class="containerPostar">
    <div class="boxPostar">
        <h1>Editar Notícia</h1>
        <?php if ($erro): ?>
            <div class="erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="sucesso"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($dados_noticia['titulo']); ?>">
            <textarea name="conteudo" rows="8"><?php echo htmlspecialchars($dados_noticia['conteudo']); ?></textarea>
            <input type="text" name="imagem" placeholder="URL da imagem">
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</div>

<?php
include './templates/footer.php';
?>

</html>