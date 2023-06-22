<?php

include 'Models/FazerRequisicao.php';
include 'Models/MontarMenu.php';
include 'Models/PegarDadoRequisicao.php';
include 'Models/SalvarRequisicao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'buscar') {
    $Request = new PegarDadoRequisicao($_POST['id']);
    echo json_encode($Request->retorno());
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'request') {
    $Request = new FazerRequisicao(
        token: $_POST['token'],
        metodo: $_POST['metodo'],
        uri: $_POST['uri'],
        parametro: jsonDecode($_POST['parametro'], true, true),
        body: jsonDecode($_POST['body'], true, true),
        header: jsonDecode($_POST['header'], true, true),
        json: jsonDecode($_POST['json'], true, true),
    );
    echo $Request->retorno();
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'salvar') {
    $Request = new SalvarRequisicao(
        id: $_POST['id'],
        token: $_POST['token'],
        metodo: $_POST['metodo'],
        uri: $_POST['uri'],
        parametro: jsonDecode($_POST['parametro'], true, true),
        body: jsonDecode($_POST['body'], true, true),
        header: jsonDecode($_POST['header'], true, true),
        json: jsonDecode($_POST['json'], true, true),
        descricao: $_POST['descricao'],
        requisicao: $_POST['requisicao'],
        resposta: $_POST['resposta'],
        variavel: jsonDecode($_POST['variavel'], true, true)
    );
    echo $Request->retorno();
    exit();
}
$Rota = new MontarMenu();
include 'view/index.php';
