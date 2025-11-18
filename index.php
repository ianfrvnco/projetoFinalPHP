<?php
session_start();
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
<div id="inteiro">

    <div id="inicio">
        <h2>Últimas Notícias</h2>
        <p>Veja o que há de novo em nossa região</p>
    </div>

    <div class="noticias-container">
        <?php
        if ($noticias) {
            foreach ($noticias as $item) {
                $conteudo_curto = mb_substr($item->conteudo, 0, 150);
                if (mb_strlen($item->conteudo) > 150) {
                    $conteudo_curto .= "...";
                }

                // Define a imagem corretamente
                $imagem = $item->url_imagem ?? 'sem_imagem.png';

                // Se for URL externa, usa direto; senão monta caminho local
                if (str_starts_with($imagem, 'http')) {
                    $caminhoImagem = $imagem;
                } else {
                    $caminhoImagem = './img/imgIndex/' . $imagem;
                }

                echo '<article class="card">';
                echo '  <img src="' . $caminhoImagem . '" alt="Imagem da notícia" class="card-imagem">';
                echo '  <div class="card-inferior">';
                echo '  <div class="card-conteudo">';
                echo '      <h3>' . htmlspecialchars($item->titulo) . '</h3>';
                echo '      <p>' . htmlspecialchars($conteudo_curto) . '</p>';
                echo '  </div>';
                echo '  <div class="card-meta">';
                echo '      Publicado em: ' . htmlspecialchars($item->hora_de_publicacao);
                echo '  </div>';
                echo '  </div>';
                echo '  <div class="card-botoes">';
                echo '      <button class="btn-expandir" 
                        data-titulo="' . htmlspecialchars($item->titulo) . '" 
                        data-conteudo="' . htmlspecialchars($item->conteudo) . '" 
                        data-autor="' . htmlspecialchars($item->autor) . '" 
                        data-editado="' . htmlspecialchars($item->hora_de_publicacao) . '">+</button>';

                if (isset($_SESSION['usuario']) && $_SESSION['usuario'] == $item->usuario_id) {
                    echo ' <a href="editarNoticia.php?id=' . $item->id . '" class="btn-editar">Editar</a>';
                    echo ' <a href="excluirNoticia.php?id=' . $item->id . '" class="btn-excluir">Excluir</a>';
                }
                echo '  </div>';
                echo '</article>';
            }
        } else {
            echo "<p>Nenhuma notícia encontrada no banco de dados.</p>";
        }
        ?>
    </div>

    <div class="modalNoticia" id="modalNoticia">
        <div class="modalConteudoNoticia">
            <button id="fecharModal" class="btn-fechar">X</button>
            <h2 id="modalTitulo"></h2>
            <p id="modalConteudo"></p>
            <p><strong>Autor:</strong> <span id="modalAutor"></span></p>
            <p id="modalEditado"></p>
        </div>
    </div>
    <?php
    // 9. INCLUI o rodapé
    include 'templates/footer.php';
    ?>