// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"
// @system "Pagina"
// @import "senha"
// @import "login"
// @import "ativar"

const PaginaLogin = new Pagina('login', LINK + '/login/login', undefined, true, true, loadingLogin);
const PaginaSenha = new Pagina('pagina-senha', LINK + '/login/senha', undefined, true, true, loadingSenha);
const PaginaAtivar = new Pagina(
    'buscar-conta',
    LINK + '/login/ativar-buscar',
    undefined,
    true,
    true,
    loadingAtivarBuscar
);

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
            PaginaLogin.abrir();
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
