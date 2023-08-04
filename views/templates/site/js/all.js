// @system "Funcao"
// @system "Pagina"
// @system "Loading"
// @system "Form"
// @system "SwipeEvent"
// @system "Alerta"
// @system "Loading"
// @import "menu_principal"
// @import "menu_perfil"
// @import "ajuda"

const LINK = document.querySelector('#LINK').value || '';
const body = $('body');

// const blocoScrollTop = $('#bloco_scroll_top');
// if (blocoScrollTop) {
//     const scrollTopAtual = document.documentElement.scrollTop || document.body.scrollTop;
//     const blocoScrollDiferencaTopo = document.documentElement.clientWidth > 1250 ? 35 : 100;
//     if (scrollTopAtual == 0) {
//         window.scrollTo({
//             top: blocoScrollTop.offsetTop - blocoScrollDiferencaTopo,
//             behavior: 'smooth',
//         });
//     }
// }
