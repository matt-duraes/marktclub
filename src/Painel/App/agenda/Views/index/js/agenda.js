window.addEventListener('load', () => {
    const LINK = document.querySelector('#LINK').value;

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */
    const blocoHeaderH1 = document.querySelector('#header_template .bloco_app h1');
    blocoHeaderH1.insertAdjacentHTML(
        'afterend',
        `
            <div id="bloco_agenda_header" class="display_hide">
                <div class="botao_hoje">HOJE</div>
                <div class="seta anterior">${Icone.setaEsquerda(14)}</div>
                <div class="seta proximo">${Icone.setaDireita(14)}</div>
                <p></p>
            </div>
        `
    );
});
