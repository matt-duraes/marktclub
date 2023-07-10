const blocoTrabalhoHeader = document.getElementById('bloco_trabalho_header');
const blocoTrabalho = document.getElementById('bloco_trabalho');
const blocoTrabalhoMinutoTrabalhado = document.getElementById('bloco_trabalho_minuto_trabalhado');
const blocoTrabalhoMinutoTotal = document.getElementById('bloco_trabalho_minuto_total');

const botaoTrabalhoHeader = blocoTrabalhoHeader.querySelector('.botao');
const botaoTrabalhoMinimizar = document.getElementById('botao_trabalho_minimizar');
const botaoTrabalhoParar = document.getElementById('botao_trabalho_parar');
const botaoTrabalhoConcluir = document.getElementById('botao_trabalho_concluir');

let tarefaIdTarefa, tarefaIdTrabalho;

let trabalhoMinutoTrabalhado = 0;
let trabalhoIntervaloTempo;
const trabalhoSetarIntervalo = () => {
    trabalhoIntervaloTempo = setInterval(() => {
        trabalhoMinutoTrabalhado++;
        blocoTrabalhoMinutoTrabalhado.innerText = trabalhoMinutoTrabalhado;
        fetch(LINK + '/demanda/trabalho-atualizar/' + tarefaIdTrabalho);
    }, 60000);
};

const trabalhoSetarValorInicialTrabalho = (data, tempo) => {
    const dataInicial = new Date(data).getTime();
    const dataFinal = new Date().getTime();
    tempo = tempo != undefined ? tempo : 0;

    trabalhoMinutoTrabalhado = Math.ceil(tempo + (dataFinal - dataInicial) / 1000 / 60);
    blocoTrabalhoMinutoTrabalhado.innerText = trabalhoMinutoTrabalhado;
};

const trabalhoAbrirBlocoTrabalho = (id, tarefa, data, tempo, total, aberto) => {
    tarefaIdTarefa = tarefa;
    tarefaIdTrabalho = id;

    blocoTrabalhoMinutoTotal.innerText = total;

    trabalhoSetarValorInicialTrabalho(data, tempo);
    trabalhoSetarIntervalo();

    if (true === aberto) {
        return;
    }

    blocoTrabalho.classList.remove('display_none');
    setTimeout(() => {
        blocoTrabalho.classList.add('ativo');
    }, 40);
    blocoTrabalhoHeader.classList.remove('ativo');
};

if (blocoTrabalho.classList.contains('ativo') || blocoTrabalhoHeader.classList.contains('ativo')) {
    const tempoAtualTrabalho = blocoTrabalho.getAttribute('data-tempo');
    trabalhoAbrirBlocoTrabalho(
        blocoTrabalho.getAttribute('data-id'),
        blocoTrabalho.getAttribute('data-tarefa'),
        blocoTrabalho.getAttribute('data-data'),
        tempoAtualTrabalho == '' ? 0 : parseInt(tempoAtualTrabalho),
        parseInt(blocoTrabalho.getAttribute('data-total')),
        true
    );
}

botaoTrabalhoMinimizar.addEventListener('click', () => {
    blocoTrabalho.classList.remove('ativo');
    setTimeout(() => {
        blocoTrabalho.classList.add('display_none');
    }, 300);
    blocoTrabalhoHeader.classList.add('ativo');
    fetch(LINK + '/demanda/trabalho-minimizar/sim');
});
botaoTrabalhoHeader.addEventListener('click', () => {
    blocoTrabalho.classList.remove('display_none');
    setTimeout(() => {
        blocoTrabalho.classList.add('ativo');
    }, 40);
    blocoTrabalhoHeader.classList.remove('ativo');
    fetch(LINK + '/demanda/trabalho-minimizar/nao');
});

botaoTrabalhoParar.addEventListener('click', async () => {
    Loading.show();
    const resposta = await fetch(LINK + '/demanda/trabalho-parar/' + tarefaIdTrabalho);
    const json = await respostaJson(resposta, 'Erro ao parar tarefa, por favor, tente novamente.');
    Loading.hide();

    if (false === json) {
        return;
    }

    trabalhoTarefaConcluida();
});

botaoTrabalhoConcluir.addEventListener('click', async () => {
    if (await Alerta.confirmar('Finalizar tarefa', 'Tem certeza que deseja finalizar essa tarefa?', '!')) {
        trabalhoConcluirTarefa();
    }
});
const trabalhoConcluirTarefa = async () => {
    Loading.show();
    const resposta = await fetch(LINK + '/demanda/trabalho-concluir/' + tarefaIdTrabalho);
    const json = respostaJson(resposta, 'Erro ao concluir tarefa, por favor, tente novamente.');

    if (false === json) {
        Loading.hide();
        return;
    }

    if (document.querySelector('#bloco_demanda_index')) {
        window.location.reload();
        return;
    }
    Loading.hide();
    trabalhoTarefaConcluida();
};
const trabalhoTarefaConcluida = () => {
    blocoTrabalho.classList.remove('ativo');
    setTimeout(() => {
        blocoTrabalho.classList.add('display_none');
    }, 300);
    blocoTrabalhoHeader.classList.remove('ativo');
    clearInterval(trabalhoIntervaloTempo);
    tarefaIdTarefa = '';
    tarefaIdTrabalho = '';
    trabalhoMinutoTrabalhado = 0;
};
