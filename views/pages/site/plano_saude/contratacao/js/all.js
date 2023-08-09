// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"

document.addEventListener('keydown', function (e) {
    if (e.key === 'Tab') {
        const elemento = e.target;
        if (!elemento.classList.contains('input_geral')) {
            e.preventDefault();
        }
    }
});

// autopreenchimento
$$('input[type=checkbox][name=responsavel]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        if (this.checked) {
            $('input[name=responsavel_nome]').value = $('input[name=nome]').value;
            $('input[name=responsavel_cpf]').value = $('input[name=cpf]').value;
            $('input[name=responsavel_rg]').value = $('input[name=rg]').value;
            $('input[name=responsavel_orgao_expedidor]').value = $('input[name=orgao_expedidor]').value;
        } else {
            document
                .querySelectorAll('#seguro-contratacao .formulario-contratacao .elemento-formulario.responsavel input')
                .forEach(function (input) {
                    input.value = '';
                });
        }
    });
});

/** TESTANDO */

function inicializarPassoAPasso() {
    const listaGeral = $$('.bloco_passo_passo_geral');
    if (listaGeral.length == 0) {
        return;
    }

    listaGeral.forEach((bloco, i) => {
        const blocoConteudo = bloco.querySelector('.bloco_conteudo');
        const conteudoLista = bloco.querySelectorAll('.bloco_conteudo .conteudo');
        const quantidadeConteudo = conteudoLista.length - 1;
        const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
        blocoConteudo.classList.add('bloco_conteudo_item_' + conteudoLista.length);

        conteudoLista.forEach((conteudo, i2) => {
            let id = conteudo.getAttribute('id') || '';
            if (id == '') {
                id = 'id_passo_passo_' + i + '_' + i2;
                conteudo.setAttribute('id', id);
            }
            const item = itemLista[i2];
            item.setAttribute('data-id', id);
            item.setAttribute('data-numero', i2);

            let linha;
            if (i2 == 0) {
                linha = '</div><div class="linha_direita"></div>';
                item.classList.add('atual');
                const bola = item.querySelector('.bola');
                const numero = item.querySelector('span');
                const texto = item.querySelector('p');
                bola.classList.add('cor_border');
                numero.classList.add('cor_color');
                texto.classList.add('cor_color');
            } else if (i2 == quantidadeConteudo) {
                linha = '<div class="linha_esquerda">';
            } else {
                linha = '<div class="linha_esquerda"></div><div class="linha_direita"></div>';
            }
            item.insertAdjacentHTML('afterbegin', linha);
        });
        bloco.classList.add('carregado');
    });

    // Restante do código...
}

inicializarPassoAPasso();

// ANTERIOR
const botaoAnterior = $$('.botao_passa_passo_anterior');
if (botaoAnterior.length > 0) {
    botaoAnterior.forEach(botao => {
        botao.addEventListener('click', () => {
            irParaPassoAnterior(botao);
        });
    });
}

const irParaPassoAnterior = botao => {
    const bloco = botao.closest('.bloco_passo_passo_geral');
    const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
    const itemAtual = bloco.querySelector('.bloco_progresso .item.atual');
    const numero = parseInt(itemAtual.getAttribute('data-numero')) - 1;
    const novoNumero = parseInt(numero) + 1;

    montarNovoItem(bloco, itemLista, numero, novoNumero);
};

// PROXIMO
const acaoProximo = botaoProximo => {
    botaoProximo.forEach(botao => {
        botao.addEventListener('click', () => {
            verificarPreenchidos(botao);
        });
    });
};
const botaoProximo = $$('.botao_passa_passo_proximo');

if (botaoProximo.length > 0) {
    acaoProximo(botaoProximo);
}

const verificarDados = (dados, botao) => {
    let campos = 0;
    dados.forEach(dado => {
        if (dado.value != '') {
            campos += 1;
        }
    });

    if (campos < dados.length) {
        Alerta.notificacao('Preencha os campos obrigatórios', false);
        return;
    }
    irParaProximoPasso(botao);
};

const verificarPreenchidos = botao => {
    let dados = $$('#dados .input_obrigatorio');
    let responsavel = $$('#responsavel .input_obrigatorio');
    let contato = $$('#contato .input_obrigatorio');
    let endereco = $$('#endereco .input_obrigatorio');
    let pagina = $('#formulario_contratacao');

    if (pagina.classList.contains('passo_1')) {
        verificarDados(dados, botao);
    } else if (pagina.classList.contains('passo_2')) {
        verificarDados(responsavel, botao);
    } else if (pagina.classList.contains('passo_3')) {
        verificarDados(contato, botao);
    } else if (pagina.classList.contains('passo_4')) {
        verificarDados(endereco, botao);
    } else {
        verificarDados(dados, botao);
    }
};

const irParaProximoPasso = botao => {
    const bloco = botao.closest('.bloco_passo_passo_geral');
    const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
    const itemAtual = bloco.querySelector('.bloco_progresso .item.atual');
    const numero = parseInt(itemAtual.getAttribute('data-numero')) + 1;
    const novoNumero = parseInt(numero) + 1;

    montarNovoItem(bloco, itemLista, numero, novoNumero);
};

const montarNovoItem = (bloco, lista, numero, novoNumero) => {
    const atual = lista[numero];

    let jaFoiAtual = false;
    let blocoBola, blocoNumero, blocoTexto, blocoLinhaEsquerda, blocoLinhaDireita;
    lista.forEach(item => {
        blocoBola = item.querySelector('.bola');
        blocoNumero = item.querySelector('.bola span');
        blocoTexto = item.querySelector('p');
        blocoLinhaEsquerda = item.querySelector('.linha_esquerda');
        blocoLinhaDireita = item.querySelector('.linha_direita');

        if (item == atual) {
            item.classList.add('atual');
            item.classList.remove('concluido');

            blocoBola.classList.add('cor_border');
            blocoNumero.classList.add('cor_color');
            blocoTexto.classList.add('cor_color');
            if (blocoLinhaEsquerda) {
                blocoLinhaEsquerda.classList.add('cor_bg');
            }
            if (blocoLinhaDireita) {
                blocoLinhaDireita.classList.remove('cor_bg');
            }
            jaFoiAtual = true;
        } else if (jaFoiAtual) {
            item.classList.remove('atual');
            item.classList.remove('concluido');

            blocoBola.classList.remove('cor_border');
            blocoNumero.classList.remove('cor_color');
            blocoTexto.classList.remove('cor_color');
            if (blocoLinhaEsquerda) {
                blocoLinhaEsquerda.classList.remove('cor_bg');
            }
            if (blocoLinhaDireita) {
                blocoLinhaDireita.classList.remove('cor_bg');
            }
        } else {
            item.classList.add('concluido');
            item.classList.remove('atual');

            blocoBola.classList.add('cor_border');
            blocoNumero.classList.add('cor_color');
            blocoTexto.classList.add('cor_color');
            if (blocoLinhaEsquerda) {
                blocoLinhaEsquerda.classList.add('cor_bg');
            }
            if (blocoLinhaDireita) {
                blocoLinhaDireita.classList.add('cor_bg');
            }
        }
    });
    const blocoScroll = bloco.querySelector('.bloco_scroll');
    blocoScroll.className = 'bloco_scroll passo_' + novoNumero;
};

// Faz a simulação
const idSimulacao = document.getElementById('simulacao').value;
const form = document.getElementById('formulario_contratacao');
const enviarSimulacao = $('#enviarSimulacao');

form.addEventListener('submit', async event => {
    event.preventDefault();
    const formData = new FormData(event.target);
    const resposta = await fetch(LINK + '/saude/contratacao/' + idSimulacao, {
        method: 'POST',
        body: formData,
    });

    if (false === resposta) {
        return;
    }
    Loading.show();
    if (resposta === false) {
        Alerta.notificacao('Formulário não enviado', false);
        return;
    }
    setTimeout(mensagemSucesso, 3000);
});

const mensagemSucesso = () => {
    Loading.hide();
    Alerta.notificacao('Dados enviados para contratação', true);
    setTimeout(() => {
        window.location.assign(LINK + '/saude');
    }, 5000);
};

let cepAtual = '';

const atualizarEnderecoPeloCep = endereco => {
    document.querySelector('#formulario_contratacao input[name=logradouro]').value = `${endereco.logradouro}`;
    document.querySelector('#formulario_contratacao input[name=cidade]').value = `${endereco.cidade}`;
    document.querySelector('#formulario_contratacao input[name=bairro]').value = `${endereco.bairro}`;
    document.querySelector('#formulario_contratacao input[name=estado]').value = `${endereco.estado}`;
};

const inputCep = document.querySelector('#input_cep');

inputCep.addEventListener('blur', () => {
    const cep = inputCep.value;
    if (cep == cepAtual || cep == '') {
        return;
    }
    cepAtual = cep;
    Loading.show();
    buscarEnderecoPeloCep(cep);
    Loading.hide();
});

async function buscarEnderecoPeloCep(cep) {
    let body = new FormData();
    body.append('cep', cep.replace(/[^0-9]/g, ''));

    const resposta = await fetch(LINK + '/saude/buscar-cep', {
        method: 'POST',
        body,
    });

    let json;
    try {
        json = await resposta.json();
    } catch (error) {
        json = {};
    }
    if (json.status == 'erro') {
        Alerta.notificacao('CEP inválido. Endereço não encontrado.', false);
        return;
    }
    atualizarEnderecoPeloCep(json.dado);
}
