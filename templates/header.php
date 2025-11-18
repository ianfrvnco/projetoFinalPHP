<?php
$url = "https://api.open-meteo.com/v1/forecast?latitude=-30.03&longitude=-51.23&current=temperature_2m";
$json = @file_get_contents($url); // @ evita erro visível se a API falhar
$temperatura = null;

if ($json) {
    $dados = json_decode($json, true);
    $temperatura = $dados['current']['temperature_2m'] ?? null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">



<head>
    <meta charset="UTF-8">
    <title>G11</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/extremos.css">
    <link rel="stylesheet" href="./css/cards.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
</head>

<body>
    <header>
        <div class="weather-widget">
            🌡️ Clima em Porto Alegre:
            <?php
            if ($temperatura !== null) {
                echo '<strong>' . $temperatura . '°C</strong>';
            } else {
                echo '<span>Indisponível</span>';
            }
            ?>
        </div>
        <h1>Bem-vindo ao Portal G11</h1>
        <nav>
            <a href="index.php">Início</a>
            <a href="login.php">Conectar-se</a>
            <a href="sobre.php">Sobre</a>
        </nav>
    </header>