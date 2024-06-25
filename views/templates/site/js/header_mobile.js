let scrollAtual = window.scrollY;
let scrollUltimaAcao = window.scrollY;
const headerPrincipalFixo = $('#header_principal_fixo');
const scrollMenuPrincipal = () => {
    const topo = window.scrollY;
    const aparecido = headerPrincipalFixo.classe('aparecer', '?');
    const diferenca = topo > scrollUltimaAcao ? topo - scrollUltimaAcao : scrollUltimaAcao - topo;
    const podeMudar = diferenca > 30;
    if ((scrollAtual < topo || topo < 80) && aparecido && podeMudar) {
        headerPrincipalFixo.classe('aparecer', false);
        scrollUltimaAcao = topo;
    } else if (scrollAtual >= topo && topo > 80 && !aparecido && podeMudar) {
        headerPrincipalFixo.classe('aparecer', true);
        scrollUltimaAcao = topo;
    }
    scrollAtual = topo;
};
window.addEventListener('scroll', function () {
    scrollMenuPrincipal();
});
