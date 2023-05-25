// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"

window.addEventListener('load', () => {
const botaoBusca = document.querySelector('.conteudo_formulario #busca_personalizada');
const opcaoLocal = document.querySelectorAll('.opcao_estado .input_radio_botao');
const blocoEstado = document.querySelector('.bloco_estado');
const blocoMundo = document.querySelector('.bloco_mundo');
const botaoFooter = document.querySelector('.botao_footer');
const listaEstado = document.querySelector('.lista_estado');
const blocoSelecionaEstado = document.querySelector('#bloco_seleciona_estado');
const botaoAvancar = document.querySelector('#botaoAvancar'); // Substitua "seuBotaoAvancar" pelo seletor correto do seu botão de avançar

const buscaPersonalizada = () => {
    document.querySelector('#primeiro').style.display = 'none';
    blocoSelecionaEstado.style.display = 'block';
};

const escolhaMundo = () => {
    botaoFooter.style.display = 'flex';
    listaEstado.style.display = 'none';
    blocoEstado.classList.remove('marcado');
    blocoMundo.classList.add('marcado');
    avancar();
};

const escolhaEstado = () => {
    botaoFooter.style.display = 'flex';
    listaEstado.style.display = 'flex';
    blocoMundo.classList.remove('marcado');
    blocoEstado.classList.add('marcado');
    selecaoEstado();
};

const selecaoEstado = () => {
    listaEstado.addEventListener('click', event => {
        const estadoClicado = event.target.closest('.estado');
        if (!estadoClicado) return;
        const estados = document.querySelectorAll('.lista_estado .estado');
        estados.forEach(estado => {
            if (estado !== estadoClicado) {
                estado.classList.remove('marcado');
            }
        });
        estadoClicado.classList.add('marcado');
        avancar(estadoClicado);
    });
};

const avancar = (estado = '') => {
    botaoAvancar.removeEventListener('click', avancar); // Remove o manipulador de eventos avancar
    botaoAvancar.addEventListener('click', event => {
        event.preventDefault();
        const estadoId = estado.id;
        selecaoCategoria();
        busca(estadoId);
    });
};

const selecaoCategoria = () => {
    blocoSelecionaEstado.style.display = 'none';
    document.querySelector('.teste').style.display = 'flex';
};

const busca = estadoId => {
    const elementos = document.querySelectorAll('.opcao_categoria a');
    elementos.forEach(elemento => {
        const url = elemento.href;
        elemento.href = url + `&estado=${estadoId}`;
    });
};

botaoBusca.addEventListener('click', buscaPersonalizada);

opcaoLocal.forEach(opcoesLocal => {
    opcoesLocal.addEventListener('click', local => {
        const opcoes = document.getElementsByName('opcao');
        if (local.target.className === 'estado') {
            escolhaEstado();
        } else if (local.target.className === 'mundo') {
            escolhaMundo();
        }
    });
});

