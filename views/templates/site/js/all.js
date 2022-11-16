window.addEventListener('load', () => {
    const body = document.querySelector('body');

    const blocoPerfil = document.getElementById('bloco_perfil');
    const botaoMenuPerfil = document.getElementById('botao_menu_perfil');
    const blocoMenuPerfil = document.getElementById('bloco_menu_perfil');

    blocoPerfil.addEventListener('mouseover', e => {
        if (
            e.target.getAttribute('id') == 'botao_menu_perfil' ||
            e.target.closest('#botao_menu_perfil') ||
            blocoPerfil.classList.contains('hover')
        ) {
            return;
        }
        blocoPerfil.classList.add('hover');
    });
    blocoPerfil.addEventListener('mouseout', e => {
        if (!blocoPerfil.classList.contains('hover')) {
            return;
        }
        blocoPerfil.classList.remove('hover');
    });

    body.addEventListener('click', e => {
        const clickNoMenu =
            e.target.getAttribute('id') == 'bloco_menu_perfil' || e.target.closest('#bloco_menu_perfil');
        if (!clickNoMenu) {
            fecharMenuPerfil();
        }
    });

    botaoMenuPerfil.addEventListener('click', () => {
        if (botaoMenuPerfil.classList.contains('abrir')) {
            setTimeout(() => {
                abrirMenuPerfil();
            }, 50);
            return;
        }
    });
    const abrirMenuPerfil = () => {
        blocoMenuPerfil.classList.remove('display_none');
        setTimeout(() => {
            blocoMenuPerfil.classList.add('abrir');
        }, 50);
        botaoMenuPerfil.classList.remove('abrir');
    };
    const fecharMenuPerfil = () => {
        if (botaoMenuPerfil.classList.contains('abrir')) {
            return;
        }

        blocoMenuPerfil.classList.remove('abrir');
        setTimeout(() => {
            blocoMenuPerfil.classList.add('display_none');
        }, 300);
        botaoMenuPerfil.classList.add('abrir');
    };
});
