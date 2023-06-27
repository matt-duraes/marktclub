$ = document.querySelector.bind(document);
$$ = document.querySelectorAll.bind(document);
ppe = console.log.bind(console);

const body = document.querySelector('body');
const variavelLocal = {};
const blocoMenuLista = $('#bloco_menu');

post = async (link, body, erro, opcao) => {
    return await fazerRequisicao(link, 'POST', body, erro, opcao);
};
fazerRequisicao = async (link, metodo, body, erro, opcao) => {
    if (opcao == undefined || !opcao instanceof Object) {
        opcao = {};
    }

    if (body != undefined && body instanceof Object && metodo == 'POST') {
        const dado = new FormData();
        Object.entries(body).forEach(valores => {
            const [indice, valor] = valores;
            dado.append(indice, valor);
        });
        opcao.body = dado;
    }
    opcao.method = metodo;

    const resposta = await fetch(link, opcao);
    const status = resposta.status;
    if (status == 204) {
        return true;
    }
    const mensagemErro = erro == undefined ? 'Erro a fazer a requisição, por favor, tente novamente.' : erro;
    let json;
    try {
        json = await resposta.json();
    } catch (e) {
        Alerta.notificacao(mensagemErro, false);
        return false;
    }
    if (!(json instanceof Object) || json.status == undefined) {
        Alerta.notificacao(mensagemErro, false);
        return false;
    } else if (json.status != 'sucesso') {
        Alerta.notificacao(
            json.erro != undefined && json.erro.mensagem != undefined ? json.erro.mensagem : mensagemErro,
            false
        );
        return false;
    }
    return json;
};
pegarElementoModelo = id => {
    const bloco = document.getElementById(id);
    bloco.removeAttribute('id');
    return bloco;
};
