// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const urlAtual = new URL(window.location.href);

    if (urlAtual.pathname.startsWith("/app/editar/solicitacao-loja/")) {
        urlAtual.pathname = urlAtual.pathname.replace("/editar/", "/visualizar/");
        window.location.href = urlAtual.toString();
    }
});
