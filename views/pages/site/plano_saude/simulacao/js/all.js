// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"

const blocoRegiao = $('#bloco_regiao');
const blocoPlano = $('#bloco_plano');
const blocoSimulacao = $('#bloco_simulacao');
const blocoResultado = $('#bloco_resultado');
const blocoAcomodacao = $('#bloco_acomodacao');
const blocoOperadora = $('#operadora');
const blocoListaDependente = $('#bloco_lista_dependente');
const botaoAdicionarDependente = $('#botao_adicionar_dependente');
const inputDataTitular = $('#input_data_titular');

const botaoSimulacaoContinuar = $('#botao_simulacao_continuar');
const botaoContratar = $('#botao_contratar');

const blocoValorLista = $('#bloco_valor_lista');
const blocoValorTotal = $('#bloco_valor_total');
const blocoValorTitular = $('#bloco_valor_titular');

const blocoDependentePadrao = $('#bloco_dependente_padrao');
blocoDependentePadrao.removeAttribute('id');
const blocoResultadoTitularPadrao = $('#bloco_resultado_titular_padrao');
blocoResultadoTitularPadrao.removeAttribute('id');
const blocoResultadoDependentePadrao = $('#bloco_resultado_dependente_padrao');
blocoResultadoDependentePadrao.removeAttribute('id');

const botaoVoltar = $$('.botao_geral_voltar');
window.addEventListener('load', () => {
    if (blocoRegiao) {
        carregarRegiao();
    }
    if (blocoPlano) {
        carregarPlano();
    }
    if (blocoAcomodacao) {
        carregarAcomodacao();
    }
});
$('body').addEventListener('keydown', e => {
    if (e.key == 'Tab') {
        e.preventDefault();
        if (e.target.tagName.toUpperCase() == 'INPUT') {
            e.target.blur();
        }
    }
});

/*
|--------------------------------------------------------------------------
| BOTAO VOLTAR
|--------------------------------------------------------------------------
*/
botaoVoltar.forEach(botao => {
    botao.addEventListener('click', () => {
        const blocoAtual = pegarBlocoPassoAtual();
        const blocoAnterior = pegarBlocoPassoAnterior();
        if (blocoAnterior == blocoRegiao) {
            limparRegiao();
        }
        if (blocoAtual == blocoPlano) {
            limparBlocoPlano();
            limparBotaoPlano();
        }
        if (blocoAnterior == blocoPlano) {
            limparBotaoPlano();
        }
        if (blocoAtual == blocoAcomodacao) {
            limparBlocoAcomodacao();
            limparBotaoAcomodacao();
        }
        if (blocoAnterior == blocoAcomodacao) {
            limparBotaoAcomodacao();
            limparBlocoAcomodacao();
        }
        if (blocoAtual == blocoSimulacao) {
            limparSimulacao();
        }
        irParaPassoAnterior();
    });
});

/*
|--------------------------------------------------------------------------
| ACOMODAÇÃO
|--------------------------------------------------------------------------
*/

const limparBlocoAcomodacao = () => {
    adicionarClassLista($$('.bloco_escolher_acomodacao'), 'display_none');
};
const limparBotaoAcomodacao = () => {
    removerClassLista($$('.botao_escolher_acomodacao'), 'ativo');
};
const carregarAcomodacao = () => {
    const lista = $$('.botao_escolher_acomodacao');
    lista.forEach(botao => {
        botao.addEventListener('click', () => {
            escolherAcomodacao(botao, botao.getAttribute('data-acomodacao'));
        });
    });
};
const escolherAcomodacao = (botao, acomodacao) => {
    const blocoProximo = pegarBlocoProximoPasso();
    if (blocoProximo) {
        botao.classList.add('ativo');
        abrirBlocoSimulacao(acomodacao);
    }
};

/*
|--------------------------------------------------------------------------
| REGIAO
|--------------------------------------------------------------------------
*/
const limparRegiao = () => {
    removerClassLista($$('.botao_escolher_regiao'), 'ativo');
};
const carregarRegiao = () => {
    const lista = $$('.botao_escolher_regiao');
    lista.forEach(botao => {
        botao.addEventListener('click', () => {
            escolherRegiao(botao, botao.getAttribute('data-regiao'));
        });
    });
};
const escolherRegiao = (botao, regiao) => {
    const blocoProximo = pegarBlocoProximoPasso();
    if (blocoProximo == blocoPlano) {
        botao.classList.add('ativo');
        abrirBlocoPlano(regiao);
    }
};

const abrirBlocoRegiao = acomodacao => {
    const blocoEscolhido = blocoAcomodacao.querySelector('.' + acomodacao);
    if (!blocoEscolhido) {
        Alerta.notificacao('Erro ao escolher acomodação, por favor, tente novamente.');
        return;
    }
    blocoEscolhido.classList.remove('display_none');
    irParaProximoPasso();
};
/*
|--------------------------------------------------------------------------
| PLANO
|--------------------------------------------------------------------------
*/
const limparBlocoPlano = () => {
    adicionarClassLista($$('.bloco_escolher_plano'), 'display_none');
};
const limparBotaoPlano = () => {
    removerClassLista($$('.botao_escolher_plano'), 'ativo');
};
const carregarPlano = () => {
    const lista = $$('.botao_escolher_plano');
    lista.forEach(botao => {
        botao.addEventListener('click', () => {
            escolherPlano(botao);
        });
    });
};
const escolherPlano = botao => {
    botao.classList.add('ativo');
    if (pegarBlocoProximoPasso() == blocoSimulacao) {
        abrirBlocoSimulacao();
    }
};
const abrirBlocoPlano = regiao => {
    const blocoEscolhido = blocoPlano.querySelector('.' + regiao);
    if (!blocoEscolhido) {
        Alerta.notificacao('Erro ao escolher região, por favor, tente novamente.');
        return;
    }
    blocoEscolhido.classList.remove('display_none');
    irParaProximoPasso();
};

/*
|--------------------------------------------------------------------------
| SIMULAÇÃO
|--------------------------------------------------------------------------
*/
const limparSimulacao = () => {
    blocoListaDependente.innerHTML = '';
    formValue(inputDataTitular, '');
};
const abrirBlocoSimulacao = () => {
    limparSimulacao();
    irParaProximoPasso();
};
const validarSimulacao = async () => {
    if (inputDataTitular.value == '') {
        Alerta.notificacao('Digite a sua data de nascimento para continuar.', false);
        return;
    }
    const listaDependete = $$('#bloco_lista_dependente .bloco_input input');
    let alertaDependente = false;
    listaDependete.forEach(input => {
        if (input.value == '') {
            alertaDependente = true;
        }
    });
    if (
        alertaDependente &&
        !(await Alerta.confirmar(
            'Campo vazio',
            'Um ou mais dependentes estão sem data de nascimento, gostaria de continuar sem fazer a cotação pra esses dependentes?',
            '!'
        ))
    ) {
        return;
    }
    buscarValorSimulacao();
};
if (botaoSimulacaoContinuar) {
    botaoSimulacaoContinuar.addEventListener('click', validarSimulacao);
}
const buscarValorSimulacao = async () => {
    const operadora = blocoOperadora.getAttribute('data-operadora');
    const body = {
        titular: inputDataTitular.value,
        regiao: pegarRegiao(),
        plano: pegarPlano(),
        acomodacao: pegarAcomodacao(),
        dependentes: [],
        operadora: operadora,
    };
    const dependente = [];
    $$('#bloco_lista_dependente .bloco_input input').forEach(input => {
        if (input.value != '') {
            dependente.push(input.value);
        }
    });
    if (dependente.length > 0) {
        body.dependentes = dependente;
    }
    // const resposta = { dado: '' };
    Loading.show();
    const resposta = await ajaxPost(
        LINK + '/saude/realizar-simulacao',
        body,
        'Erro ao fazer a simulação, por favor, tente novamente.'
    );
    Loading.hide();
    if (!resposta) {
        return;
    }
    if (pegarBlocoProximoPasso() == blocoResultado) {
        abrirBlocoResultado(resposta.dado);
    }
};
const pegarRegiao = () => {
    if (!blocoRegiao) {
        return '';
    }
    const bloco = blocoRegiao.querySelector('.botao_escolher_regiao.ativo');
    if (!bloco) {
        return '';
    }
    return bloco.getAttribute('data-regiao');
};
const pegarPlano = () => {
    if (!blocoPlano) {
        return '';
    }
    const bloco = blocoPlano.querySelector('.botao_escolher_plano.ativo');
    if (!bloco) {
        return '';
    }
    return bloco.getAttribute('data-plano');
};
const pegarAcomodacao = () => {
    if (!blocoAcomodacao) {
        return '';
    }
    const bloco = blocoAcomodacao.querySelector('.botao_escolher_acomodacao.ativo');
    if (!bloco) {
        return '';
    }
    return bloco.getAttribute('data-acomodacao');
};

/*
|--------------------------------------------------------------------------
| RESULTADO
|--------------------------------------------------------------------------
*/
const limparResultado = () => {
    //
};
const abrirBlocoResultado = dado => {
    limparResultado();
    irParaProximoPasso();
    let dependentes = Object.values(dado.dependentes);
    dependentes.forEach(dependente => {
        let valor = dependente.valor;
        let dataNascimento = dependente.data_nascimento;
        adicionarLinhaValor(blocoResultadoDependentePadrao, dataNascimento, valor);
    });
    adicionarLinhaValor(blocoResultadoTitularPadrao, dado.titular, dado.valor_titular);
    blocoValorTotal.innerText = `${dado.valor_total}`;
    botaoContratar.setAttribute('href', `${dado.id}`);
};
const adicionarLinhaValor = (bloco, data, valor) => {
    const clone = bloco.cloneNode(true);
    clone.querySelector('.data').innerText = data;
    clone.querySelector('.valor').innerText = valor;

    blocoValorLista.prepend(clone);
};

/*
|--------------------------------------------------------------------------
| DEPENDENTE
|--------------------------------------------------------------------------
*/
botaoAdicionarDependente.addEventListener('click', () => {
    const clone = blocoDependentePadrao.cloneNode(true);
    blocoListaDependente.prepend(clone);
    clone.querySelector('input').focus();
    fwMascaraLoading(blocoListaDependente);
});
if (blocoListaDependente) {
    blocoListaDependente.addEventListener('click', e => {
        if (e.target.classList.contains('.remover') || e.target.closest('.remover')) {
            const item = e.target.closest('.linha_dependente');
            item.parentNode.removeChild(item);
        }
    });
}

/*
|--------------------------------------------------------------------------
| GERAL
|--------------------------------------------------------------------------
*/
const removerClassLista = (lista, classe) => {
    lista.forEach(item => {
        item.classList.remove(classe);
    });
};
const adicionarClassLista = (lista, classe) => {
    lista.forEach(item => {
        item.classList.add(classe);
    });
};
