// @template "login"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"

const botaoSimularConsignado = $('#botao_fazer_simulacao');
const botaoContratarConsignado = $('#enviar_solicitacao');
const botaoPopupRegulamento = $$('.abrirModalRegulamento');
const botaoFechar = $$('.botao_fechar_popup');
const botaoVoltar = $('.bloco_credito .botao_passa_passo_anterior');

function adicionarEventoSimularConsignado() {
    botaoSimularConsignado.addEventListener('click', async e => {
        e.preventDefault();
        const tipo = $('#formulario_emprestimo input[name=tipo_financiamento]').value;
        const valor = $('#formulario_emprestimo input[name=financiamento]').value.replace('.', '').replace(',', '.');
        const parcelas = $('#formulario_emprestimo input[name=parcela]').value.slice(0, 2);

        if (valor === '') {
            Alerta.mensagem('Campo obrigatório!', 'Digite o valor que deseja simular.');
            return false;
        } else if (parcelas === '') {
            Alerta.mensagem('Campo obrigatório!', 'Escolha a quantidade de parcelas que deseja simular.');
            return false;
        }

        const query = `&tipo=${tipo}&valor=${valor}&parcelas=${parcelas}`;

        const resposta = await ajaxGet('/credito/simulacao?operadora=sicoob' + query);
        if (!resposta) {
            return;
        }

        irParaProximoPasso(botaoSimularConsignado);
        $('.valor_emprestimo').innerHTML = resposta.dado.valor;
        $('.valor_prazo').innerHTML = resposta.dado.parcelas;
        $('#simulacao_valor').innerHTML = 'R$ ' + resposta.dado.valor_parcelas;
        Loading.hide();
    });
}

if (botaoVoltar) {
    botaoVoltar.addEventListener('click', () => {
        const itemLista = $$('.bloco_progresso .item');
        const itemAtual = $('.bloco_progresso .item.atual');
        const numero = parseInt(itemAtual.getAttribute('data-numero')) - 1;
        const novoNumero = parseInt(numero) + 1;
        const bloco = $('.bloco_passo_passo_geral');
        montarNovoItem(bloco, itemLista, numero, novoNumero);
    });
}

function adicionarEventoContratarConsignado() {
    botaoContratarConsignado.addEventListener('click', async e => {
        e.preventDefault();
        const tipo = $('input[name=tipo_financiamento]').value;
        const valor = $('input[name=financiamento]').value.replace('.', '').replace(',', '.');
        const parcelas = $('input[name=parcela]').value.slice(0, 2);

        const resposta = await ajaxPost(
            LINK + '/credito/salvar',
            {
                tipo: tipo,
                valor: valor,
                operadora: 'sicoob',
                parcelas: parcelas,
            },
            'Erro ao fazer a requisição, por favor, tente novamente.'
        );

        const dadosRecebidos = () => {
            return new Promise((resolve, reject) => {
                if (resposta) {
                    resolve();
                } else {
                    reject();
                }
            });
        };

        dadosRecebidos()
            .then(() => {
                Alerta.mensagem('Solicitação feita', 'Em breve entraremos em contato');
                setTimeout(() => {
                    location.href = LINK + '/credito/sicoob';
                }, 3000);
            })
            .catch(() => {
                mensagemErro('Não foi possível completar a sua solicitação, tente novamente, em breve.');
            });
    });
}

const redirecionarUsuario = () => {
    Alerta.mensagem('Sucesso', 'Sua simulação foi enviada, em breve entraremos em contato');
    location.href = LINK + '/credito/sicoob';
};

const mensagemErro = () => {};

function limparFormulario(formulario) {
    const inputs = formulario.querySelectorAll('input');

    inputs.forEach(input => {
        if (input.type !== 'submit' && input.type !== 'button') {
            input.value = '';
        }
    });
}

function adicionarEventoPopupRegulamento() {
    botaoPopupRegulamento.forEach(modelo => {
        const tipo = modelo.getAttribute('data-tipo');
        const PaginaDetalhe = new Pagina(tipo, '/sicoob-regulamento/' + tipo, {}, true, true, carregarFuncoesBusca);
        modelo.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });
}

function adicionarEventoFechar() {
    botaoFechar.forEach(fecha => {
        fecha.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    });
}

function carregarFuncoesBusca() {
    adicionarEventoFechar();
}

window.addEventListener('load', () => {
    adicionarEventoSimularConsignado();
    adicionarEventoContratarConsignado();
    adicionarEventoPopupRegulamento();
});
