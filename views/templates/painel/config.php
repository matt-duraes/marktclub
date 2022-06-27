<?php

use Helpers\ApiHelper;

$appAcao = $acao ?? '';
$appTitulo = $config->titulo ?? $appTitulo ?? '';
$appVoltar = $appVoltar ?? '';

if (!empty($appVoltar) && is_array($appVoltar) && is_string($appVoltar[0])) {
    $appTitulo = '<a href="' . $appVoltar[0] . '">' . strCortar($appVoltar[1], 15, '...', true) .
        '</a><span>/</span>' .
        strCortar(empty($appAcao) ? $appTitulo : $appAcao, 20, '...', true);
} else if (!empty($appVoltar) && is_array($appVoltar)) {
    $appTituloLista = '';
    foreach ($appVoltar as $appVoltarR) {
        $appTituloLista .= '<a href="' . $appVoltarR[0] . '">' .
            strCortar($appVoltarR[1], 8, '...', true) .
            '</a><span>/</span>';
    }
    $appTitulo = $appTituloLista . strCortar($appTitulo, 15, '...', true);
} else if (!empty($appAcao) && !empty($appTitulo)) {
    $appLink = !empty($config->index->link) ? $config->index->link : LINK . '/app/' . $app;
    $appTitulo = '<a href="' . $appLink . '">' . $appTitulo . '</a><span>/</span>' . $acao;
}

$buscarStatus = $config->permissao->buscar ?? false;
$filtrarStatus = $config->permissao->filtrar ?? false;
$ordemStatus = $config->permissao->ordem ?? false;

$app = $app ?? '';

define('PAINEL_CONFIGURACAO', sessao('PAINEL.configuracao', padrao: []));
define('USUARIO_NOME', sessao('USUARIO.nome'));
define('USUARIO_CPF', sessao('USUARIO.cpf'));
define('USUARIO_IMAGEM', sessao('USUARIO.imagem'));
define('LINK_VOLTAR', isset($linkVoltar) && !empty($linkVoltar) ? $linkVoltar : LINK . URI);
