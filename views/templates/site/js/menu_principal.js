window.addEventListener('load', () => {
    const botaoMenu = document.getElementById('botao_menu_mobile');
    if (!botaoMenu) {
        return;
    }
    const botaoSwiped = document.getElementById('botao_swiped_esquerdo');

    const blocoMenu = document.getElementById('menu_principal');
    const blocoMenuBg = blocoMenu.querySelector('.bg');

    botaoSwiped.addEventListener('swiped-right', () => {
        abrirMenu();
    });
    botaoMenu.addEventListener('click', () => {
        if (blocoMenu.classList.contains('ativo')) {
            fecharMenu();
            return;
        }
        abrirMenu();
    });
    blocoMenu.addEventListener('swiped-left', () => {
        fecharMenu();
    });
    blocoMenuBg.addEventListener('click', e => {
        if (e.target.classList.contains('bg') || e.target.closest('.bg')) {
            fecharMenu();
        }
    });
    const abrirMenu = () => {
        blocoMenu.classList.remove('menu_fechado');
        setTimeout(() => {
            blocoMenu.classList.add('ativo');
        }, 40);
    };
    const fecharMenu = () => {
        blocoMenu.classList.remove('ativo');
        setTimeout(() => {
            blocoMenu.classList.add('menu_fechado');
        }, 300);
    };
    const posicionarMenuPrincipal = () => {
        const blocoMenuPrincipal = document.querySelector('#menu_principal');
        const blocoScroll = blocoMenuPrincipal.querySelector('.conteudo');
        const menuAtual = blocoMenuPrincipal.querySelector('.pagina_atual');
        if (!menuAtual) {
            return;
        }
        const posicaoMenu = menuAtual.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        const menuTop = posicaoMenu - windowHeight + windowHeight / 2;
        blocoScroll.scrollTop = menuTop;
    };
    posicionarMenuPrincipal();
});
