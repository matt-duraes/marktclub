// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"

window.addEventListener('load', () => {
    const botaoBusca = document.querySelector('.conteudo_formulario #busca_personalizada');
    const opcaoLocal = document.querySelectorAll('.opcao_estado .input_radio_botao');
    const blocoEstado = document.querySelector('.bloco_estado');
    const blocoMundo = document.querySelector('.bloco_mundo');
    const botaoFooter = document.querySelector('.botao_footer');

    botaoBusca.addEventListener('click', buscaPersonalizada);

    opcaoLocal.forEach(opcoesLocal => {
        opcoesLocal.addEventListener('click', local => {
            const opcoes = document.getElementsByName('opcao');
            let param = { blocoEstado, botaoFooter, blocoMundo };
            if (local.target.className == 'estado') {
                escolhaEstado(param);
            } else if (local.target.className == 'mundo') {
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
    let listaEstado = document.querySelector('.lista_estado');
    botaoFooter.style.display = 'flex';
    listaEstado.style.display = 'none';
    blocoEstado.classList.remove('marcado');
    blocoMundo.classList.add('marcado');
    avancar();
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
            let estadoClicado = event.currentTarget;
            estados.forEach(estado => {
                if (estado !== estadoClicado) {
                    estado.classList.remove('marcado');
                }
            });
            estadoClicado.classList.add('marcado');
            avancar(estadoClicado);
        });
    });
};

const avancar = (estado = '') => {
    botaoAvancar.addEventListener('click', event => {
        event.preventDefault();
        let estadoId = estado.id;
        selecaoCategoria(estadoId);
        busca(estadoId);
    });
};

const selecaoCategoria = () => {
    document.querySelector('#bloco_seleciona_estado').style.display = 'none';
    document.querySelector('.teste').style.display = 'flex';
};

const busca = estadoId => {
    const elementos = document.querySelectorAll('.opcao_categoria a');
    elementos.forEach(elemento => {
        const url = elemento.href;
        elemento.href = url + `&estado=${estadoId}`;
    });
};
