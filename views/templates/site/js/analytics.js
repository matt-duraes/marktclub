window.addEventListener('load', () => {
    const blocoVinculo = $('#input_analytics_vinculo');
    const vinculo = blocoVinculo ? blocoVinculo.value : '';
    const body = new FormData();
    body.append('uri', window.location.pathname);
    body.append('vinculo', vinculo);
    fetch(LINK + '/a/pagina', {
        method: 'POST',
        body,
    });
});
