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
        <?= formTextarea(name: 'historico_novo', label: '', numeroLinha: 4, placeholder: 'Digite sua mensagem', id: 'input_historico_mensagem') ?>
        <p>Aperte Shift+Enter para quebrar linha ou apenas Enter para salvar</p>
        <ul class="bloco_marcar_equipe" id="bloco_historico_marcacao_equipe">
            <li data-usuario="andre.rodrigues" class="">
                <div class="imagem" style="background-image: url(<?= sessao('USUARIO.imagem', padrao: '') ?>);"></div>
                <div class="usuario">andre.rodrigues</div>
                <div class="nome">André Rodrigues</div>
            </li>
            <li data-usuario="mateus.cunha" class="">
                <div class="imagem" style="background-image: url(<?= sessao('USUARIO.imagem', padrao: '') ?>);"></div>
                <div class="usuario">mateus.cunha</div>
                <div class="nome">Mateus Cunha</div>
            </li>
            <li data-usuario="mateus.duram" class="">
                <div class="imagem" style="background-image: url(<?= sessao('USUARIO.imagem', padrao: '') ?>);"></div>
                <div class="usuario">mateus.duram</div>
                <div class="nome">Mateus Duram</div>
            </li>
        </ul>
    </form>
    <div class="lista" id="bloco_historico_lista">
    </div>
    <div class="mais" id="botao_historico_carregar_mais">CARREGAR MAIS</div>
</div>
