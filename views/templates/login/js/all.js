// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"
// @system "Pagina"
// @system "Popup"
// @import "senha"
// @import "login"
// @import "ativar"


function pegarCookie(nome) {
    const cookies = document.cookie.split('; ');
    for (let c of cookies) {
        const [chave, valor] = c.split('=');
        if (chave === nome) {
          let loginCript = decodeURIComponent(valor)
          const valorOriginal = atob(loginCript);
          return decodeURIComponent(valorOriginal);
        };
    }
    return null;
}

const valorSalvo = pegarCookie('login');

const PopupLogin = new Popup('login', 'popup_login', true, true, () => {
    $('#input_login').focus();
    const tipoUsuarioSelecionado = document.querySelectorAll('.tipo_usuario_selecionado');

    tipoUsuarioSelecionado.forEach(tipo => {
        tipo.addEventListener('click', dataTipo => {
            const elementoClicado = dataTipo.target;
            const tipoUsuario = elementoClicado.getAttribute('data-tipo-usuario');
            localStorage.setItem("tipoUsuarioSelecionado", tipoUsuario);
        });
    });

    const inputLoginDados = $('#input_login');
    if (valorSalvo) {
        inputLoginDados.value = valorSalvo;
    }
});

const PopupSenha = new Popup('senha', 'popup_senha', true, true, () => {
    $('#bloco_cpf').focus();
});

const PaginaAtivar = new Pagina('ativar', LINK + '/login/ativar-buscar', undefined, true, true, loadingAtivarBuscar);

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
            fecharMenu(e);
        }
    });
    const fecharMenu = () => {
        blocoMenuMobile.classList.remove('animar');
        setTimeout(() => {
            blocoMenuMobile.classList.remove('aberto');
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    const botaoLogin = $$('.botao_fazer_login');
    if (botaoLogin) {
        const abrirPaginaLogin = () => {
            if (blocoMenuMobile.classList.contains('aberto')) {
                fecharMenu();
            }
            PopupLogin.abrir();
        };
        botaoLogin.forEach(botao => {
            botao.addEventListener('click', abrirPaginaLogin);
        });
    }
    /*
    |--------------------------------------------------------------------------
    | ATIVAR
    |--------------------------------------------------------------------------
    */
    const botaoAtivar = $('#botao_ativar_conta_home');
    if (botaoAtivar) {
        botaoAtivar.addEventListener('click', () => {
            if (blocoMenuMobile.classList.contains('aberto')) {
                fecharMenu();
            }
            PaginaAtivar.abrir();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | BAIXAR APP
    |--------------------------------------------------------------------------
    */
    const botaoBaixarApp = $('#botao_baixar_app');
    const blocoBaixarApp = $('#bloco_baixar_app');
    if (botaoBaixarApp) {
        botaoBaixarApp.addEventListener('click', e => {
            if (!blocoBaixarApp) {
                window.location.assign(LINK + '/login');
                return;
            }
            if (blocoMenuMobile.classList.contains('animar')) {
                fecharMenu();
            }
            const topo = blocoBaixarApp.getBoundingClientRect().top;
            const resto = window.innerWidth > 1040 ? 200 : 0;
            window.scrollTo({
                top: window.scrollY + topo - resto,
                behavior: 'smooth',
            });
        });
    }
});
