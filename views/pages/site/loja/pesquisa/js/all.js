// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"

window.addEventListener('load', () => {
    const botaoBusca = document.querySelector('#busca_personalizada');
    const opcaoLocal = document.querySelectorAll('.opcao_estado .input_radio_botao');
    const blocoEstado = document.querySelector('.bloco_estado');
    const blocoMundo = document.querySelector('.bloco_mundo');
    const botaoFooter = document.querySelector('.botao_footer');

    botaoBusca.addEventListener('click', buscaPersonalizada);

    opcaoLocal.forEach(elements => {
        elements.addEventListener('click', element => {
            let param = { blocoEstado, botaoFooter, blocoMundo };
            if (element.target.className == 'estado') {
                escolhaEstado(param);
            } else if (element.target.className == 'mundo') {
                escolhaMundo(param);
            }
        });
    });
});

const buscaPersonalizada = () => {
    document.querySelector('#primeiro').style.display = 'none';
    document.querySelector('#bloco_seleciona_estado').style.display = 'block';
};

const escolhaMundo = param => {
    let { blocoEstado, botaoFooter, blocoMundo } = param;
    botaoFooter.style.display = 'flex';
    blocoEstado.classList.remove('marcado');
    blocoMundo.classList.add('marcado');
};

const escolhaEstado = param => {
    let listaEstado = document.querySelector('.lista_estado');
    let { blocoEstado, botaoFooter, blocoMundo } = param;
    botaoFooter.style.display = 'flex';
    listaEstado.style.display = 'flex';
    blocoMundo.classList.remove('marcado');
    blocoEstado.classList.add('marcado');
    selecaoEstado();
};

const selecaoEstado = () => {
    let estados = document.querySelectorAll('.lista_estado .estado');
    estados.forEach(estado => {
        estado.addEventListener('click', event => {
            let estado = event.currentTarget;
            estado.classList.remove('marcado');
            estado.classList.add('marcado');
            avancar(estado);
        });
    });
};

const avancar = estado => {
    botaoAvancar.addEventListener('click', event => {
        event.preventDefault();
        let estadoId = estado.id;
        selecaoCategoria(estadoId);
        teste(estadoId);
    });
};

const selecaoCategoria = () => {
    document.querySelector('#bloco_seleciona_estado').style.display = 'none';
    document.querySelector('.teste').style.display = 'flex';
};

const teste = estadoId => {
    const elements = document.querySelectorAll('.opcao_categoria a');
    elements.forEach(element => {
        const url = element.href;
        element.href = url + `&estado=${estadoId}`;
    });
};
