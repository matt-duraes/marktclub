<div id="bloco_historico_view" class="<?= $classe ?>">
    <header>
        <h1>HISTÓRICO</h1>
        <form action="">
            <input type="text" name="pesquisa" id="input_historico_pesquisa" placeholder="Pesquisa">
            <input type="text" data-mascara="00/00/0000" class="input_data input_data_de" id="input_historico_data_de" placeholder="00/00/000">
            <p>até</p>
            <input type="text" data-mascara="00/00/0000" class="input_data input_data_ate" id="input_historico_data_ate" placeholder="00/00/000">
            <div class="botao" id="botao_buscar_historico">BUSCAR</div>
        </form>
    </header>
    <div class="add_fake" id="bloco_historico_add_fake"></div>
    <form action="" class="add form_geral" id="bloco_historico_add">
        <input type="hidden" id="input_historico_relacionado" value="<?= $r->id ?>">
        <input type="hidden" id="input_historico_app" value="<?= $app ?>">
        <figure style="background-image: url(<?= sessao('USUARIO.imagem', padrao: '') ?>);"></figure>
        <?= formTextarea(name: 'historico_novo', label: '', placeholder: 'Digite sua mensagem', enter: false, id: 'input_historico_mensagem') ?>
        <p>Aperte Shift+Enter para quebrar linha ou apenas Enter para salvar</p>
    </form>
    <div class="lista" id="bloco_historico_lista">
    </div>
    <div class="mais" id="botao_historico_carregar_mais">CARREGAR MAIS</div>
</div>
