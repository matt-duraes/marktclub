// @system "Funcao"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"
// @system "Pagina"

const LINK = $('#LINK').value;
const body = $('body');

const loadingLogin = () => {
    const inputLogin = $('#input_login');
    const inputSenha = $('#input_senha');

    const botaoAtivar = $('#botao_ativar_conta');
    const botaoFazerLogin = $('#botao_fazer_login');

    inputLogin.focus();

    botaoAtivar.addEventListener('click', () => {
        PaginaBuscar.abrir();
    });
    botaoFazerLogin.addEventListener('click', () => {
        fazerLogin();
    });
    inputLogin.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            fazerLogin();
        }
    });
    inputSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            fazerLogin();
        }
    });

    const fazerLogin = async () => {
        if (inputLogin.value == '') {
            Alerta.notificacao('Digite seu login para continuar.', false);
            return;
        } else if (inputSenha.value == '') {
            Alerta.notificacao('Digite sua senha para continuar.', false);
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/login/login',
            {
                login: inputLogin.value,
                senha: inputSenha.value,
            },
            'Erro ao fazer seu login, por favor, tente novamente'
        );
        if (!resposta) {
            Loading.hide();
            return;
        }
        window.location.replace(resposta.dado.link);
    };
};

const loadingBuscar = () => {
    const botaoBuscar = $('#botao_buscar_usuario');
    const inputBuscar = $('#input_buscar');

    inputBuscar.focus();

    const PaginaAtivar = new Pagina('ativar-conta', LINK + '/login/ativar', { id: '123' }, true, false, loadingAtivar);
    botaoBuscar.addEventListener('click', () => {
        PaginaAtivar.abrir();
    });
};

const loadingAtivar = () => {
    const inputNome = $('#input_nome');
    inputNome.focus();
};

const PaginaLogin = new Pagina('login', LINK + '/login/login', {}, true, true, loadingLogin);
const PaginaBuscar = new Pagina('buscar-conta', LINK + '/login/buscar-conta', {}, true, true, loadingBuscar);

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
    const abrirPaginaLogin = () => {
        if (blocoMenuMobile.classList.contains('aberto')) {
            fecharMenu();
        }
        PaginaLogin.abrir();
    };
    botaoLogin.forEach(botao => {
        botao.addEventListener('click', abrirPaginaLogin);
    });
    /*
    |--------------------------------------------------------------------------
    | ATIVAR
    |--------------------------------------------------------------------------
    */
    const botaoAtivar = $('#botao_ativar_conta_home');
    botaoAtivar.addEventListener('click', () => {
        if (blocoMenuMobile.classList.contains('aberto')) {
            fecharMenu();
        }
        PaginaBuscar.abrir();
    });

    /*
    |--------------------------------------------------------------------------
    | BAIXAR APP
    |--------------------------------------------------------------------------
    */
    const botaoBaixarApp = $('#botao_baixar_app');
    const blocoBaixarApp = $('#bloco_baixar_app');
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
});
