<?php

$metodo = $_SERVER['REQUEST_METHOD'];
$acao = $_POST['acao'] ?? 'vazio';

$controller = [
    'POST' => [
        'atualizar-nome-grupo' => 'GrupoRenomear',
        'atualizar-nome-requisicao' => 'RequisicaoRenomear',
        'buscar-requisicao' => 'RequisicaoBuscar',
        'deletar-grupo' => 'GrupoDeletar',
        'deletar-requisicao' => 'RequisicaoDeletar',
        'requisicao' => 'RequisicaoEnviar',
        'salvar' => 'RequisicaoSalvar',
    ],
    'GET' => [
        'vazio' => 'MontarMenu'
    ]
];

$Classe = $controller[$metodo][$acao] ?? '';
if (empty($Classe)) {
    exit();
}
if (array_key_exists('acao', $_POST)) {
    unset($_POST['acao']);
}
$Classe = '\\System\\Html\\Postman\\Models\\' . $Classe;
$Run = new $Classe($_POST);
if ($metodo == 'POST') {
    echo jsonEncode([
        'status' => 'sucesso',
        'dado' => $Run->retorno()
    ]);
    exit();
}
$menu = $Run->retorno();
include 'view/index.php';

// if ($post && $acao == 'buscar') {
//     $Request = new PegarDadoRequisicao($_POST['id']);
//     echo json_encode($Request->retorno());
//     exit();
// } elseif ($post && $acao == 'diretorio-salvar') {
//     $Diretorio = new DiretorioSalvar(
//         nome: $_POST['nome'],
//         pai: $_POST['pai']
//     );
//     echo $Diretorio->retorno();
//     exit();
// } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'request') {
//     $Request = new FazerRequisicao(
//         token: $_POST['token'],
//         metodo: $_POST['metodo'],
//         uri: $_POST['uri'],
//         parametro: jsonDecode($_POST['parametro'], true, true),
//         body: jsonDecode($_POST['body'], true, true),
//         header: jsonDecode($_POST['header'], true, true),
//         json: jsonDecode($_POST['json'], true, true),
//     );
//     echo $Request->retorno();
//     exit();
// } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'salvar') {
//     $Request = new SalvarRequisicao(
//         id: $_POST['id'],
//         token: $_POST['token'],
//         metodo: $_POST['metodo'],
//         uri: $_POST['uri'],
//         parametro: jsonDecode($_POST['parametro'], true, true),
//         body: jsonDecode($_POST['body'], true, true),
//         header: jsonDecode($_POST['header'], true, true),
//         json: jsonDecode($_POST['json'], true, true),
//         descricao: $_POST['descricao'],
//         requisicao: $_POST['requisicao'],
//         resposta: $_POST['resposta'],
//         variavel: jsonDecode($_POST['variavel'], true, true)
//     );
//     echo $Request->retorno();
//     exit();
// }
