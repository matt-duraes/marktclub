window.addEventListener('load', () => {
    const fwDocumentoCodigo = pre => {
        const code = pre.querySelector('code');
        const codigo = code.innerHTML;
        const codigoExplode = codigo.split('\n');
        let quantidade = codigoExplode.length + 1;
        if (quantidade == 0) {
            pre.parentNode.removeChild(pre);
            return;
        }
        pre.setAttribute('tabindex', '-1');

        if (codigoExplode[quantidade - 2] == '') {
            quantidade--;
        }

        let html = '';
        let i = 1;
        codigoExplode.forEach(() => {
            html += `${i}\n`;
            i++;
        });
        pre.insertAdjacentHTML('afterbegin', `<div class="bloco_linha">${html}</div>`);

        pre.insertAdjacentHTML(
            'afterbegin',
            `
            <div class="bloco_copiar">
                <svg height="25" xmlns="http://www.w3.org/2000/svg" data-name="Layer 4" viewBox="0 0 64 64" x="0px" y="0px"><path d="M33.553,51.6a5.507,5.507,0,0,0,5.5-5.5v-2.51h5.479a5.506,5.506,0,0,0,5.5-5.5V24.132a5.506,5.506,0,0,0-5.5-5.5H30.572a5.506,5.506,0,0,0-5.5,5.5v2.509h-5.48a5.507,5.507,0,0,0-5.5,5.5V46.1a5.507,5.507,0,0,0,5.5,5.5ZM28.072,24.132a2.5,2.5,0,0,1,2.5-2.5h13.96a2.5,2.5,0,0,1,2.5,2.5V38.091a2.5,2.5,0,0,1-2.5,2.5H39.053v-8.45a5.507,5.507,0,0,0-5.5-5.5H28.072ZM17.092,46.1V32.141a2.5,2.5,0,0,1,2.5-2.5H33.553a2.5,2.5,0,0,1,2.5,2.5V46.1a2.5,2.5,0,0,1-2.5,2.5H19.592A2.5,2.5,0,0,1,17.092,46.1Z"/></svg>
            </div>
        `
        );
        setTimeout(() => {
            const copiar = pre.querySelector('.bloco_copiar');
            if (copiar) {
                copiar.addEventListener('click', () => {
                    navigator.clipboard.writeText(code.innerText);
                    Alerta.notificacao('Código copiado com sucesso!', true);
                });
            }
        }, 1000);
    };

    const fwDocumentoCodigoLista = document.querySelectorAll('.fw_documento pre');
    fwDocumentoCodigoLista.forEach(pre => {
        fwDocumentoCodigo(pre);
    });

    const fwDocumentoDestaqueLista = document.querySelectorAll('.fw_documento .bloco_destaque');
    if (fwDocumentoDestaqueLista.length > 0) {
        let fwDocumentoDestaqueItem = [];
        const fwDocumentoWindowHeight = window.innerHeight / 4;
        fwDocumentoDestaqueLista.forEach(destaque => {
            fwDocumentoDestaqueItem.push([destaque.offsetTop - fwDocumentoWindowHeight, destaque]);
        });

        const fwDocumentoDestaqueAtivar = () => {
            const scrollTopo = document.querySelector('html').scrollTop;
            if (fwDocumentoDestaqueItem.length == 0) {
                document.removeEventListener('scroll', fwDocumentoDestaqueAtivar);
                return;
            }
            fwDocumentoDestaqueItem.forEach((item, indice) => {
                if (scrollTopo > item[0]) {
                    item[1].classList.add('ativar');
                    fwDocumentoDestaqueItem.splice(indice, 1);
                }
            });
        };

        document.addEventListener('scroll', fwDocumentoDestaqueAtivar);
        fwDocumentoDestaqueAtivar();
    }
});
