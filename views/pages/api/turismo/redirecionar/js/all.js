window.addEventListener('load', () => {
    const usuario = document.querySelector('#USUARIO').value;
    const linkBase = document.querySelector('#LINK').value;
    setTimeout(() => {
        const memoria = navigator.deviceMemory;
        const link = linkBase + '/turismo/abrir/' + usuario + '/' + memoria;
        window.location.replace(link);
    }, 1000);
});
