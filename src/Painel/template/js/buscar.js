window.addEventListener('load', () => {
    const formGeral = document.querySelector('#form_geral');

    if (formGeral) {
        const headerPrincipal = document.querySelector('#header_template');
        const formInput = formGeral.querySelector('input');
        const formBg = formGeral.querySelector('.bg');

        formInput.addEventListener('focus', () => {
            formBg.style.display = 'block';
            headerPrincipal.classList.add('z_index');
            setTimeout(() => {
                formGeral.classList.add('animacao');
            }, 40);
        });
        formInput.addEventListener('blur', () => {
            if (formInput.value == '') {
                formGeral.classList.remove('animacao');
                setTimeout(() => {
                    headerPrincipal.classList.remove('z_index');
                    formBg.style.display = 'none';
                }, 320);
            }
        });
    }
});
