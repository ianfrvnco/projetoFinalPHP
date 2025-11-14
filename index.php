<?php
require_once './config/config.php';
require_once './classes/database.php';
require_once './classes/noticia.php';



if (!isset($_SESSION['usuario'])) {
    include 'templates/header.php';
} else {
    include 'templates/headerConectado.php';
}


$noticia = new Noticia($db);
$noticias = $noticia->ler()
?>

<div id="inicio">
    <h2>Últimas Notícias</h2>
    <p>Veja o que há de novo em nossa região</p>
</div>

<div class="noticias-container">
    <?php
    if ($noticias) {
        foreach ($noticias as $item) {
            // Pega os primeiros 150 caracteres do conteúdo
            $conteudo_curto = mb_substr($item->conteudo, 0, 150);
            if (mb_strlen($item->conteudo) > 150) {
                $conteudo_curto .= "...";
            }

            // Exibe o card
            echo '<article class="card">';
            echo '  <div class="card-conteudo">';
            echo '      <h3>' . htmlspecialchars($item->titulo) . '</h3>';
            echo '      <p>' . htmlspecialchars($conteudo_curto) . '</p>';
            echo '  </div>';
            echo '  <div class="card-meta">';
            echo '      Publicado em: ' . htmlspecialchars($item->hora_de_publicacao);
            echo '  </div>';
            echo '</article>';
        }
    } else {
        echo "<p>Nenhuma notícia encontrada no banco de dados.</p>";
    }

    $db = null; // Fecha a conexão
    ?>
</div> <?php
        // 9. INCLUI o rodapé
        include 'templates/footer.php';
        ?>