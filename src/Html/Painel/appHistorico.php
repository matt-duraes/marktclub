<?php
$historico = $config->historico;
$historicoApp = $app;
$historicoAppExtra = '';
$historicoLeitura = true;
$historicoEscrita = true;
$historicoDownload = false;
$historicoTextareaHtml = '<input class="display_none input_app_salvar" type="checkbox" name="app_salvar[]" checked value="' . $app . '">';
$historicoArquivo = false;
$historicoArquivoHtml = '';
if($historico instanceof \PainelConfig\Historico) {
    $historicoApp = $historico->app;
    $historicoLeitura = $historico->leitura;
    $historicoEscrita = $historico->escrita;
    $historicoDownload = $historico->download;
    $historicoAppExtra = $historico->appExtra;
    $historicoArquivo = $historico->arquivo;
    if($historicoArquivo) {
        $historicoArquivoHtml = '<div class="arquivo_previa display_none" id="bloco_previa_lista"></div> <div class="botao_upload"><input type="file" multiple id="botao_historico_upload"><i>' . iconeAnexo(18) . '</i></div>';
    }

    if($historicoAppExtra):
        $historicoTextareaHtml .= '<div class="bloco_app_extra"><h2>Salvar em outro APP?</h2>';
        foreach($historicoAppExtra as $ind => $val):
            $historicoTextareaHtml .= '
                <div class="checkbox_interno">
                    <input type="checkbox" class="input_app_salvar input_app_salvar_visivel" name="app_salvar[]" value="' . $ind . '">
                    <div class="checkbox_interno_titulo">' . $val . '</div>
                </div>
            ';
        endforeach;
        $historicoTextareaHtml .= '</div>';
    endif;
}
?>

<div id="bloco_historico_view" class="<?= $classe ?>">
    <header>
        <?php if ($fechar) : ?>
            <div class="fechar pagina_fechar mobile"><?= iconeVoltar(12) ?></div>
        <?php endif; ?>
        <h1>HISTÓRICO</h1>
        <?php if($historicoLeitura): ?>
        <form action="">
            <input type="text" name="pesquisa" id="input_historico_pesquisa" placeholder="Pesquisa">
            <input type="text" data-mascara="00/00/0000" class="input_data input_data_de" id="input_historico_data_de" placeholder="00/00/000">
            <p>até</p>
            <input type="text" data-mascara="00/00/0000" class="input_data input_data_ate" id="input_historico_data_ate" placeholder="00/00/000">
            <div class="botao" id="botao_buscar_historico">BUSCAR</div>
            <?php if ($historicoDownload && in_array($app . '_download', sessao('USUARIO.permissao'))): ?>
            <div class="botao_download" id="botao_download_historico"><?= iconeDownload(14) ?></div>
            <?php endif; ?>
        </form>
        <?php endif; ?>
        <?php if ($fechar) : ?>
            <div class="fechar pagina_fechar desktop"><?= iconeFechar(12) ?></div>
        <?php endif; ?>
    </header>
    <?php if($historicoEscrita): ?>
    <div class="add_fake" id="bloco_historico_add_fake"></div>
    <?php endif; ?>

    <input type="hidden" id="input_historico_relacionado" value="<?= $r->id ?>">
    <input type="hidden" id="input_historico_app" value="<?= $historicoApp ?>">

    <?php if($historicoEscrita): ?>
    <form action="" class="add form_geral" id="bloco_historico_add">
        <input type="hidden" id="input_historico_titulo" value="<?= base64Encode($titulo) ?>">
        <input type="hidden" id="input_historico_link" value="<?= base64Encode(!empty($link) ? $link : LINK . URI) ?>">
        <input type="hidden" id="input_historico_notificar" value="<?= base64Encode($notificar) ?>">
        <figure style="background-image: url(<?= sessao('USUARIO.imagem', padrao: '') ?>);"></figure>
        <?= formTextarea(
            name: 'historico_novo',
            label: '',
            numeroLinha: 4,
            placeholder: 'Digite sua mensagem',
            id: 'input_historico_mensagem',
            class: $historicoArquivo ? 'textarea_arquivo' : '',
            html: $historicoTextareaHtml,
            htmlPre: $historicoArquivoHtml
        ) ?>
        <p>Aperte Shift+Enter para quebrar linha ou apenas Enter para salvar</p>
        <ul class="bloco_marcar_equipe" id="bloco_historico_marcacao_equipe">
            <?php foreach ((new \PainelModel\Perfil\Equipe())->todos() as $hE) : ?>
                <li data-usuario="<?= $hE->perfil ?>" class="">
                    <div class="imagem" style="background-image: url(<?= $hE->imagem ?>);"></div>
                    <div class="usuario"><?= $hE->perfil ?></div>
                    <div class="nome"><?= $hE->nome ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </form>
    <?php endif; ?>

    <?php if($historicoLeitura): ?>
    <div class="lista" id="bloco_historico_lista">
    </div>
    <div class="mais" id="botao_historico_carregar_mais">CARREGAR MAIS</div>
    <?php endif; ?>
</div>
<div class="display_none">
    <div class="arquivo_previa_item" id="bloco_previa_item_padrao"><div class="arquivo_previa_titulo"></div><i data-ajuda="Remover arquivo"><?= iconeFechar(10) ?></i></div>
</div>
<?php if ($historicoDownload && in_array($app . '_download', sessao('USUARIO.permissao'))): ?>
<div id="bloco_download" class="bloco_pagina_popup">
    <header class="header_pagina_popup">
        <h1>Download</h1>
        <i class="botao pagina_fechar"><?= iconeFechar() ?></i>
    </header>
    <form class="conteudo_pagina_popup form_geral">
        <?= formDataHora(
            name: ['data_de', 'data_ate'],
            placeholder: ['Data de início', 'Data final'],
            label: 'Data da campanha',
            separador: 'até'
        ) ?>
    </form>
    <div class="footer_pagina_popup">
        <div class="flex"></div>
        <div class="button salvar" id="botao_enviar_download">DOWNLOAD</div>
    </div>
</div>
<?php endif; ?>
