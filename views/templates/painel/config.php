<?php

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
define('USUARIO_GERENTE', sessao('USUARIO.gerente', padrao: 'nao'));
define('LINK_VOLTAR', isset($linkVoltar) && !empty($linkVoltar) ? $linkVoltar : LINK . URI);

try {
    $Api = new \Helpers\ApiHelper(token: true);
    $notificacaoNova = $Api->json(['novo' => 'sim'])->get('/painel-notificacao')->object()->dado ?? [];
    $notificacaoNumeroNova = $notificacaoNova->registro->total ?? 0;
    $notificacaoNumeroNovaVisualizada = $Api->json(['clicado' => 'nao'])->get('/painel-notificacao')->object()->dado->registro->total ?? 0;
} catch (\Throwable) {
}

define('TRABALHO_INICIADO', cookieExiste('TRABALHO_INICIADO') ? cookie('TRABALHO_INICIADO') : false);
define('TRABALHO_MINIMIZADO', cookieExiste('TRABALHO_MINIMIZADO') ? cookie('TRABALHO_MINIMIZADO') : null);
define('TRABALHO_ID', cookieExiste('TRABALHO_ID') ? cookie('TRABALHO_ID') : null);
define('TRABALHO_TAREFA', cookieExiste('TRABALHO_TAREFA') ? cookie('TRABALHO_TAREFA') : null);
define('TRABALHO_DATA', cookieExiste('TRABALHO_DATA') ? cookie('TRABALHO_DATA') : null);
define('TRABALHO_TEMPO', cookieExiste('TRABALHO_TEMPO') ? cookie('TRABALHO_TEMPO') : '');
define('TRABALHO_TOTAL', cookieExiste('TRABALHO_TOTAL') ? cookie('TRABALHO_TOTAL') : '');
