window.addEventListener('load', () => {
    const botaoMenu = document.getElementById('botao_menu_perfil');
    const blocoMenu = document.getElementById('bloco_menu_perfil');
    const body = document.querySelector('body');

    botaoMenu.addEventListener('click', () => {
        if (blocoMenu.classList.contains('ativo')) {
            fecharMenu();
            return;
        }
        abrirMenu();
    });
    body.addEventListener('click', e => {
        const eMenu = e.target.classList.contains('bloco_geral_sub_menu') || e.target.closest('.bloco_geral_sub_menu');
        if (blocoMenu.classList.contains('ativo') && !eMenu) {
            fecharMenu();
        }
    });
    const abrirMenu = () => {
        blocoMenu.classList.remove('display_none');
        setTimeout(() => {
            blocoMenu.classList.add('ativo');
        }, 40);
    };
    const fecharMenu = () => {
        blocoMenu.classList.remove('ativo');
        setTimeout(() => {
            blocoMenu.classList.add('display_none');
        }, 300);
    };
});
