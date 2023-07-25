<div id="bloco_historico_view" class="<?= $classe ?>">
    <header>
        <?php if($fechar): ?>
        <div class="fechar pagina_fechar mobile"><?= iconeVoltar(12) ?></div>
        <?php endif; ?>
        <h1>HISTÓRICO</h1>
        <form action="">
            <input type="text" name="pesquisa" id="input_historico_pesquisa" placeholder="Pesquisa">
            <input type="text" data-mascara="00/00/0000" class="input_data input_data_de" id="input_historico_data_de" placeholder="00/00/000">
            <p>até</p>
            <input type="text" data-mascara="00/00/0000" class="input_data input_data_ate" id="input_historico_data_ate" placeholder="00/00/000">
            <div class="botao" id="botao_buscar_historico">BUSCAR</div>
        </form>
        <?php if($fechar): ?>
        <div class="fechar pagina_fechar desktop"><?= iconeFechar(12) ?></div>
        <?php endif; ?>
    </header>
    <div class="add_fake" id="bloco_historico_add_fake"></div>
    <form action="" class="add form_geral" id="bloco_historico_add">
        <input type="hidden" id="input_historico_relacionado" value="<?= $r->id ?>">
        <input type="hidden" id="input_historico_app" value="<?= $app ?>">
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
            html: '<div class="botao_upload"><i id="botao_historico_upload">' . iconeAnexo(18) . '</i></div>'
        ) ?>
        <p>Aperte Shift+Enter para quebrar linha ou apenas Enter para salvar</p>
        <ul class="bloco_marcar_equipe" id="bloco_historico_marcacao_equipe">
            <?php foreach ((new \PainelModel\Historico\Equipe())->pegarListaEquipe() as $hE) : ?>
                <li data-usuario="<?= $hE->perfil ?>" class="">
                    <div class="imagem" style="background-image: url(<?= $hE->imagem ?>);"></div>
                    <div class="usuario"><?= $hE->perfil ?></div>
                    <div class="nome"><?= $hE->nome ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </form>
    <div class="lista" id="bloco_historico_lista">
    </div>
    <div class="mais" id="botao_historico_carregar_mais">CARREGAR MAIS</div>
</div>
