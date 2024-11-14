window.addEventListener('scroll', function () {
    const headerPrincipalFixo = $('#header_principal_fixo');
    if (!headerPrincipalFixo) {
        return;
    }
    let scrollAtual = window.scrollY;
    let scrollUltimaAcao = window.scrollY;
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
    scrollMenuPrincipal();
});
