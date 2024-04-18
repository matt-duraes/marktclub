<div class="bloco_visualizar_conteudo_geral bloco_linha_tempo_geral">
    <input type="hidden" name="local_principal" value="<?= $localPrincipal ?>">
    <div class="lista_dado">
        <div class="botao_link botao_linha_tempo">Linha do tempo</div>
    </div>

    <div class="bloco_linha_tempo bloco_visualizar_popup_geral display_none">
        <div class="bloco_pagina_popup conteudo">
            <header class="header_pagina_popup">
                <h1>LINHA DO TEMPO</h1>
                <i class="fechar"><?= iconeFechar() ?></i>
            </header>
            <div class="bloco_lista_item">
                <div class="zero display_none">Sem registros no momento</div>
            </div>
            <div class="botao_mais display_none">carregar mais</div>
        </div>
    </div>
    <div class="display_none">
        <div class="item bloco_linha_tempo_padrao">
            <figure></figure>
            <h3></h3>
            <p></p>
            <time></time>
        </div>
    </div>
</div>
