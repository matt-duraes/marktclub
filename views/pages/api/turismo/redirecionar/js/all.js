window.addEventListener('load', () => {
    const usuario = $('#USUARIO').value;
    setTimeout(() => {
        const memoria = navigator.deviceMemory;
        const link = LINK + '/turismo/abrir/' + usuario + '/' + memoria;
        window.location.replace(link);
    }, 1000);
});
