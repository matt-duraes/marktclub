window.addEventListener('load', () => {
    const blocoPagina = document.querySelector('#bloco_fw_pagina');

    blocoPagina.addEventListener('scroll', e => {
        const header = blocoPagina.querySelector('.header_pagina_popup');
        if (blocoPagina.scrollTop >= 20 && !header.classList.contains('fixed')) {
            header.classList.add('fixed');
        } else if (blocoPagina.scrollTop < 20 && header.classList.contains('fixed')) {
            header.classList.remove('fixed');
        }
    });
});
