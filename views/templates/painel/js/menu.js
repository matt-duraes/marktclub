window.addEventListener('load', () => {
    const listaDropDown = document.querySelectorAll('#bloco_menu_principal .menu');
    if (listaDropDown.length > 0) {
        let menuBotao, menuQuantidade;
        listaDropDown.forEach(menu => {
            menuBotao = menu.querySelector('.botao');
            menuQuantidade = menu.querySelectorAll('a').length;
            menuBotao.addEventListener('click', () => {
                abrirFecharMenu(menu, menuQuantidade);
            });
        });
    }

    const abrirFecharMenu = (menu, quantidade) => {
        if (menu.classList.contains('aberto')) {
            menu.classList.remove('aberto');
            menu.style.height = '45px';
            return;
        }
        const height = 45 + quantidade * 47;
        menu.classList.add('aberto');
        menu.style.height = height + 'px';
    };

    const menuAberto = document.querySelector('#bloco_menu_principal .menu.aberto');
    if (menuAberto) {
        menuAberto.style.height = 'auto';
        setTimeout(() => {
            menuAberto.style.height = 47 + menuAberto.querySelectorAll('a').length * 47 + 'px';
        }, 300);
    }
});

const posicionarMenuPrincipal = () => {
    const blocoMenuPrincipal = document.querySelector('#nav_template');
    const blocoScroll = blocoMenuPrincipal.querySelector('.conteudo');
    const menuAtual = blocoMenuPrincipal.querySelector('.pagina_atual');
    if (!menuAtual) {
        return;
    }
    const posicaoMenu = menuAtual.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;
    const menuTop = posicaoMenu - windowHeight - 45 + windowHeight / 2;
    blocoScroll.scrollTop = menuTop;
};
posicionarMenuPrincipal();
