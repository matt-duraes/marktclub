// @system "Player"
// @system "Loading"
// @system "DragDrop"
// @system "Ajuda"
// @system "Alerta"
// @system "Pagina"
// @system "Mascara"
// @system "Calendario"
// @system "Editor"
// @system "SwipeEvent"
// @system "ArquivoUpload"
// @system "Form"
// @system "Icone"

// @painel "pagina_popup"
// @painel "form_geral"
// @painel "historico"

// @import "ordem"
// @import "filtrar"
// @import "buscar"
// @import "body"
// @import "senha"
// @import "notificacao"
// @import "menu"
// @import "relogar"

const usuarioGerente = $('#USUARIO_GERENTE') ? $('#USUARIO_GERENTE').value : 'nao';
window.addEventListener('load', () => {
    historicoLoad();
    /*
    |--------------------------------------------------------------------------
    | AJUDA DO SISTEMA
    |--------------------------------------------------------------------------
    */
    const listaAjuda = document.querySelectorAll('*[data-ajuda]');
    listaAjuda.forEach(bloco => {
        bloco.addEventListener('mouseover', () => {
            const texto = bloco.getAttribute('data-ajuda');
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | CALENDARIO PADRÃO
    |--------------------------------------------------------------------------
    */
    Calendario.init({
        input: '.bloco_input_data .input_data[data-mascara="00/00/0000"]',
    });
    Calendario.init({
        input: '.bloco_input_data .input_data[data-mascara="00/00/0000 00:00:00"]',
        hora: true,
    });

    /*
    |--------------------------------------------------------------------------
    | ABRE HISTÓRICO PENDENTE
    |--------------------------------------------------------------------------
    */
    if (document.querySelector('#bloco_historico_pendente')) {
        const paginaHistoricoPendente = new Pagina(
            'Histórico',
            LINK + '/historico',
            {
                method: 'POST',
            },
            false,
            false,
            () => {
                document.querySelector('#input_historico_atualizar_texto').focus();
            }
        );
        paginaHistoricoPendente.abrir();
    }

    /*
    |--------------------------------------------------------------------------
    | MANIPULA O MENU PRINCIPAL
    |--------------------------------------------------------------------------
    */
    const botaoMenuMobile = document.querySelector('#menu_principal');
    const blocoMenuPrincipal = document.querySelector('#nav_template');
    botaoMenuMobile.addEventListener('click', () => {
        menuPrincipalAbrir();
    });
    const menuPrincipalAbrir = () => {
        blocoMenuPrincipal.classList.add('show');
        setTimeout(() => {
            blocoMenuPrincipal.classList.add('menu_aberto');
        }, 20);
    };

    blocoMenuPrincipal.addEventListener('click', e => {
        if (e.target.classList.contains('bg')) {
            menuPrincipalFechar();
        }
    });
    const menuPrincipalFechar = () => {
        blocoMenuPrincipal.classList.remove('menu_aberto');
        setTimeout(() => {
            blocoMenuPrincipal.classList.remove('show');
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | MANIPULA O MENU DE CONFIGURAÇÃO
    |--------------------------------------------------------------------------
    */
    const blocoConfig = document.getElementById('bloco_config_template');
    const botaoConfig = document.getElementById('botao_menu_config');
    const botaoConfigFechar = document.querySelectorAll('.botao_menu_config_fechar');

    botaoConfig.addEventListener('click', () => {
        menuConfigAbrir();
    });

    const menuConfigAbrir = () => {
        blocoConfig.classList.remove('fechado');
        setTimeout(() => {
            blocoConfig.classList.add('aberto');
        }, 20);
    };

    const menuConfigFechar = () => {
        blocoConfig.classList.remove('aberto');
        setTimeout(() => {
            blocoConfig.classList.add('fechado');
        }, 320);
    };

    [].forEach.call(botaoConfigFechar, botao => {
        botao.addEventListener('click', menuConfigFechar);
    });
    blocoConfig.addEventListener('click', e => {
        if (e.target.classList.contains('bg')) {
            menuConfigFechar();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | MANIPULA O SWITCH DO MENU LATERAL
    |--------------------------------------------------------------------------
    */
    const switchMenuEsquerdo = document.getElementById('switch_menu_esquerdo');
    const switchMenuDireito = document.getElementById('switch_menu_direito');
    switchMenuEsquerdo.addEventListener('swiped-right', () => {
        menuPrincipalAbrir();
    });
    switchMenuDireito.addEventListener('swiped-left', () => {
        menuConfigAbrir();
    });
    document.querySelector('#nav_template').addEventListener('swiped-left', () => {
        menuPrincipalFechar();
    });
    document.querySelector('#bloco_config_template').addEventListener('swiped-right', () => {
        menuConfigFechar();
    });
});
