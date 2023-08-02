// @system "Funcao"
const LINK = $('#LINK').value;

window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | MENU MOBILE
    |--------------------------------------------------------------------------
    */
    const botaoAbrirMenu = $('#botao_abrir_menu');
    const blocoMenuMobile = $('#bloco_menu_mobile');
    botaoAbrirMenu.addEventListener('click', () => {
        blocoMenuMobile.classList.add('aberto');
        setTimeout(() => {
            blocoMenuMobile.classList.add('animar');
        }, 40);
    });
    blocoMenuMobile.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_menu_mobile' && e.target.classList.contains('aberto')) {
            blocoMenuMobile.classList.remove('animar');
            setTimeout(() => {
                blocoMenuMobile.classList.remove('aberto');
            }, 300);
        }
    });
});
