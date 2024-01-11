<?php

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;

$listaOperadora = (new Operadora())->listarNumero();
$listaTipo = (new Tipo())->listarNumero();
$listaStatus = (new Status())->listarNumero();
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $parcela = numeroAleatorio(1, 96);
    $valorParcela = numeroAleatorio(1, 100000);
    $valorTotal = $valorParcela * $parcela;
    $seeds[] = [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => numeroAleatorio(1, 50),
        'operadora'          => valorAleatorio($listaOperadora),
        'tipo'               => valorAleatorio($listaTipo),
        'parcela'            => $parcela,
        'valor_parcela'      => number_format($valorParcela, 2, thousands_separator: ''),
        'valor_total'        => number_format($valorTotal, 2, thousands_separator: ''),
        'status'             => valorAleatorio($listaStatus)
    ];
}
return $seeds;
