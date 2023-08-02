// @template "login"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"
// @system "Pagina"
// @resource "site/login/slide"
// @import "index"

const loadingLogin = () => {
    const inputLogin = $('#input_login');
    const inputSenha = $('#input_senha');

    const botaoAtivar = $('#botao_ativar_conta');
    const botaoFazerLogin = $('#botao_fazer_login');

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
        Loading.hide();
        if (!resposta) {
            return;
        }
        window.location.replace(resposta.dado.link);
    };
};

const loadingBuscar = () => {
    const botaoBuscar = $('#botao_buscar_usuario');
    const PaginaAtivar = new Pagina('ativar-conta', LINK + '/login/ativar', { id: '123' }, true, false, loadingAtivar);

    botaoBuscar.addEventListener('click', () => {
        PaginaAtivar.abrir();
    });
};

const loadingAtivar = () => {
    //
};

const PaginaLogin = new Pagina('login', LINK + '/login/login', {}, true, true, loadingLogin);
const PaginaBuscar = new Pagina('buscar-conta', LINK + '/login/buscar-conta', {}, true, true, loadingBuscar);

window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    const botaoLogin = $$('.botao_fazer_login');
    const abrirPaginaLogin = () => {
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
        PaginaBuscar.abrir();
    });
});
