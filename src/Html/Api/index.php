<?php

include 'Models/PegarRequisicoesModel.php';
include 'Models/PegarDadoRequisicaoModel.php';
include 'Models/FazerRequest.php';
include 'Models/SalvarRequest.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'buscar') {
    $Request = new PegarDadoRequisicaoModel($_POST['id']);
    echo json_encode($Request->retorno());
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'request') {
    $Request = new FazerRequest(
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
    $Request = new SalvarRequest(
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
}
$Rota = new PegarRequisicoesModel();
include 'view/index.php';
