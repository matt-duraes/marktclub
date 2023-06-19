<?php

include 'Models/PegarRequisicoesModel.php';
include 'Models/PegarDadoRequisicaoModel.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'buscar') {
    $Request = new PegarDadoRequisicaoModel($_POST['id']);
    echo json_encode($Request->retorno());
    exit();
}
$Rota = new PegarRequisicoesModel();
include 'view/index.php';
