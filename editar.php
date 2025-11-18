<?php
session_start();
include_once "./classes/Database.php";
$database = new Database();
$db = $database->getConnection();

include_once './config/config.php';
include_once './classes/Usuario.php';
include './templates/headerConectado.php';

$usuario = new Usuario($db);

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario']]);
$dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $novoNome = trim($_POST['nome']);
    $novoEmail = trim($_POST['email']);
    $novoIcone = $_POST['icone'];

    if (empty($novoNome) || empty($novoEmail)) {
        $erro = "Nome e email não podem estar vazios.";
    } else {
        $stmt = $db->prepare("UPDATE usuarios SET nome = ?, email = ?, icone = ? WHERE id = ?");
        $stmt->execute([$novoNome, $novoEmail, $novoIcone, $_SESSION['usuario']]);

        $dados_usuario['nome'] = $novoNome;
        $dados_usuario['email'] = $novoEmail;
        $dados_usuario['icone'] = $novoIcone;
        $sucesso = "Perfil atualizado com sucesso!";
        header('Location: index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .box {
            background: rgb(124, 0, 108);
            color: white;
            padding: 20px;
            border-radius: 15px;
            max-width: 350px;
            width: 100%;
            text-align: center;
        }

        .box h1 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .box input {
            padding: 8px;
            margin: 8px 0;
            border-radius: 4px;
            border: none;
            width: 90%;
        }

        .box button {
            margin-top: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            background: white;
            color: rgb(124, 0, 108);
            font-weight: bold;
            cursor: pointer;
        }

        .icone-perfil {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            cursor: pointer;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .modal-content h3 {
            margin-bottom: 15px;
            color: rgb(124, 0, 108);
        }

        .icones-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .icones-grid img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .icones-grid img:hover {
            border-color: rgb(124, 0, 108);
        }

        #fecharModal {
            padding: 6px 12px;
            background: rgb(124, 0, 108);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="box">
            <h1>Perfil</h1>
            <form method="POST">
                <img src="./img/icones/<?php echo $dados_usuario['icone']; ?>" alt="Ícone de perfil"
                    class="icone-perfil" id="iconeAtual">
                <input type="hidden" name="icone" id="iconeSelecionado" value="<?php echo $dados_usuario['icone']; ?>">
                <input type="text" name="nome" value="<?php echo htmlspecialchars($dados_usuario['nome']); ?>" disabled>
                <input type="email" name="email" value="<?php echo htmlspecialchars($dados_usuario['email']); ?>"
                    disabled>
                <div class="botoes">
                    <button type="button" id="btnEditar">Editar</button>
                    <button type="submit" name="editar" id="btnSalvar" disabled>Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="modalIcones">
        <div class="modal-content">
            <h3>Escolha seu novo ícone</h3>
            <div class="icones-grid">
                <?php
                $icones = ['castor.png', 'girafa.png', 'urubu.png', 'peixe.png', 'gato.png', 'cobra.png'];
                foreach ($icones as $icone) {
                    echo "<img src='./img/icones/$icone' data-icone='$icone'>";
                }
                ?>
            </div>
            <button id="fecharModal">Fechar</button>
        </div>
    </div>

    <script>
        const btnEditar = document.getElementById('btnEditar');
        const btnSalvar = document.getElementById('btnSalvar');
        const inputs = document.querySelectorAll('.box input[type="text"], .box input[type="email"]');
        const iconeAtual = document.getElementById('iconeAtual');
        const modal = document.getElementById('modalIcones');
        const fecharModal = document.getElementById('fecharModal');
        const iconeSelecionado = document.getElementById('iconeSelecionado');

        let editando = false;

        btnEditar.addEventListener('click', () => {
            inputs.forEach(input => input.disabled = false);
            btnEditar.style.display = 'none';
            btnSalvar.style.display = 'inline-block';
            editando = true; // agora pode alterar ícone
        });

        iconeAtual.addEventListener('click', () => {
            if (editando) { // só abre modal se estiver editando
                modal.style.display = 'flex';
            }
        });

        fecharModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        document.querySelectorAll('.icones-grid img').forEach(img => {
            img.addEventListener('click', () => {
                if (editando) { 
                    iconeAtual.src = img.src;
                    iconeSelecionado.value = img.dataset.icone;
                    modal.style.display = 'none';
                }
            });
        });

        btnEditar.addEventListener('click', () => {
            inputs.forEach(input => input.disabled = false);
            btnEditar.style.display = 'none';
            btnSalvar.disabled = false; 
            editando = true;
        });
    </script>
</body>

</html>