<?php

require __DIR__ . '/vendor/autoload.php';

use Jandelson\AnalisaArquivos;

$loader = new Twig\Loader\FilesystemLoader('view');
$twig = new Twig\Environment($loader);

$dadosArquivos = [];

if ($_POST['dir']) {
    $dadosArquivos = AnalisaArquivos::listaArquivos(
        $_POST['arquivos'],
        $_POST['dir'],
        "\r\n"
    );
}
echo $twig->render('index.html', $dadosArquivos);
