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
define('EMPRESA_ID', sessao('EMPRESA.id', padrao: ''));
define('PAINEL_CONFIGURACAO', sessao('PAINEL.configuracao', padrao: []));
define('USUARIO_NOME', sessao('USUARIO.nome'));
define('USUARIO_CPF', sessao('USUARIO.cpf'));
define('USUARIO_IMAGEM', sessao('USUARIO.imagem'));
define('USUARIO_GERENTE', sessao('USUARIO.gerente', padrao: 'nao'));
define('LINK_VOLTAR', isset($linkVoltar) && !empty($linkVoltar) ? $linkVoltar : LINK . URI);

try {
    $Api = new \Helpers\ApiHelper(token: true);
    $notificacaoNova = $Api
        ->json([
            'pagina' => 1,
            'novo' => 'sim'
        ])->get('/painel-notificacao')->object()->dado ?? [];
    $notificacaoNova->lista = (new \PainelModel\Notificacao\HelperModel)->tratarRetorno($notificacaoNova->lista ?? []);
    $notificacaoNumeroNova = $notificacaoNova->registro->total ?? 0;
    $notificacaoNumeroNovaVisualizada = $Api->json(['pagina' => 1, 'clicado' => 'nao'])->get('/painel-notificacao')->object()->dado->registro->total ?? 0;
} catch (\Throwable) {
    $notificacaoNova = [];
    $notificacaoNumeroNova = 0;
    $notificacaoNumeroNovaVisualizada = 0;
}

define('TRABALHO_INICIADO', sessao('TRABALHO.iniciado', padrao: false));
define('TRABALHO_MINIMIZADO', sessao('TRABALHO.minimizado', padrao: false));
define('TRABALHO_ID', sessao('TRABALHO.id', padrao: ''));
define('TRABALHO_TAREFA', sessao('TRABALHO.tarefa', padrao: ''));
define('TRABALHO_DEMANDA', sessao('TRABALHO.demanda', padrao: ''));
define('TRABALHO_DATA', dataHoraBanco(sessao('TRABALHO.data', padrao: '')));
define('TRABALHO_TEMPO', sessao('TRABALHO.tempo', padrao: 0));
define('TRABALHO_TOTAL', sessao('TRABALHO.total', padrao: 0));

function temPermissaoEmpresa(string $app): bool
{
    $usuarioPermissao = sessao('USUARIO.permissao');
    return in_array($app . '_empresa', $usuarioPermissao);
}
