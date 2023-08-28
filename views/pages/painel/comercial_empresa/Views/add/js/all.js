// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputTipoPagamento = document.getElementById('input_tipo_pagamento');
    const inputValorMinimo = document.getElementById('input_contrato_valor_minimo');
    const inputUsuarioMinimo = document.getElementById('input_contrato_usuario_minimo');
    const inputFinalidadePrincipal = document.getElementById('input_finalidade_principal');
    const inputFinalidadeSecundaria = document.getElementById('input_finalidade_secundaria');
    const inputComunicacaoEmail = document.getElementById('input_comunicacao_email');
    const inputComunicacaoWhatsapp = document.getElementById('input_comunicacao_whatsapp');
    const inputComunicacaoRedeSocial = document.getElementById('input_comunicacao_rede_social');
    const inputDataEleicao = document.getElementById('input_data_eleicao');
    const inputProdutoClube = document.getElementById('input_produto_clube');
    const inputProdutoIos = document.getElementById('input_produto_ios');
    const inputProdutoAndroid = document.getElementById('input_produto_android');
    const inputProdutoWebview = document.getElementById('input_produto_webview');

    const blocoTipoSite = document.getElementById('bloco_tipo_site');
    const blocoDataEleicao = document.getElementById('bloco_data_eleicao');
    const blocoEmailDia = document.getElementById('bloco_email_dia');
    const blocoEmailDisparo = document.getElementById('bloco_email_disparo');
    const blocoWhatsappDia = document.getElementById('bloco_whatsapp_dia');
    const blocoRedeSocialDia = document.getElementById('bloco_rede_social_dia');
    const blocoValorMinimo = document.getElementById('bloco_valor_minimo');
    const blocoUsuarioMinimo = document.getElementById('bloco_usuario_minimo');

    const mudarDisplay = (input, bloco) => {
        const checked = input.checked;
        bloco.forEach(item => {
            if (checked) {
                item.classList.remove('display_none');
            } else {
                item.classList.add('display_none');
            }
        });
    };

    if (inputTipoPagamento) {
        inputTipoPagamento.addEventListener('formChange', () => {
            tipoPagamentoMudou();
        });
    }
    const tipoPagamentoMudou = () => {
        const valor = inputTipoPagamento.value;
        if (valor == 'misto') {
            blocoValorMinimo.classList.remove('display_none');
            blocoUsuarioMinimo.classList.remove('display_none');
            return;
        }
        formValue(inputValorMinimo, '');
        formValue(inputUsuarioMinimo, '');
        blocoValorMinimo.classList.add('display_none');
        blocoUsuarioMinimo.classList.add('display_none');
    };
    if (inputTipoPagamento) {
        tipoPagamentoMudou();
    }

    inputFinalidadePrincipal.addEventListener('formChange', () => {
        finalidadeMudou();
    });
    const finalidadeMudou = () => {
        const valor = inputFinalidadePrincipal.value;
        buscarListaFinalidadeSecundaria(valor);
        if (!blocoDataEleicao) {
            return;
        }
        if (valor == 'publica') {
            blocoDataEleicao.classList.remove('display_none');
            return;
        }
        blocoDataEleicao.classList.add('display_none');
        inputDataEleicao.value = '';
    };
    const buscarListaFinalidadeSecundaria = async (tipo, valor) => {
        formSelectLoading(inputFinalidadeSecundaria);
        const resposta = await ajaxPost(LINK + '/app/classe/comercial-empresa', {
            classe: 'finalidade_secundaria',
            tipo,
        });
        if (false === resposta) {
            formSelectOption(inputFinalidadeSecundaria, { '': 'Ocorreu um erro ao buscar lista' }, '');
            return;
        }
        formSelectOption(inputFinalidadeSecundaria, resposta.dado, valor);
    };
    if (inputFinalidadePrincipal.value != '') {
        buscarListaFinalidadeSecundaria(inputFinalidadePrincipal.value, inputFinalidadeSecundaria.value);
        if (inputFinalidadePrincipal.value == 'publica') {
            blocoDataEleicao.classList.remove('display_none');
        }
    }
    if (inputProdutoClube) {
        inputProdutoClube.addEventListener('change', () => {
            mudarDisplay(inputProdutoClube, [blocoTipoSite]);
        });
        if (inputProdutoClube.checked) {
            mudarDisplay(inputProdutoClube, [blocoTipoSite]);
        }
    }
    if (inputComunicacaoEmail) {
        inputComunicacaoEmail.addEventListener('change', () => {
            mudarDisplay(inputComunicacaoEmail, [blocoEmailDisparo, blocoEmailDia]);
        });
        if (inputComunicacaoEmail.checked) {
            mudarDisplay(inputComunicacaoEmail, [blocoEmailDisparo, blocoEmailDia]);
        }
    }
    if (inputComunicacaoWhatsapp) {
        inputComunicacaoWhatsapp.addEventListener('change', () => {
            mudarDisplay(inputComunicacaoWhatsapp, [blocoWhatsappDia]);
        });
        if (inputComunicacaoWhatsapp.checked) {
            mudarDisplay(inputComunicacaoWhatsapp, [blocoWhatsappDia]);
        }
    }
    if (inputComunicacaoRedeSocial) {
        inputComunicacaoRedeSocial.addEventListener('change', () => {
            mudarDisplay(inputComunicacaoRedeSocial, [blocoRedeSocialDia]);
        });
        if (inputComunicacaoRedeSocial.checked) {
            mudarDisplay(inputComunicacaoRedeSocial, [blocoRedeSocialDia]);
        }
    }
    if (inputProdutoIos) {
        inputProdutoIos.addEventListener('change', () => {
            if (!inputProdutoIos.checked) {
                return;
            }
            inputProdutoWebview.checked = false;
        });
    }
    if (inputProdutoAndroid) {
        inputProdutoAndroid.addEventListener('change', () => {
            if (!inputProdutoAndroid.checked) {
                return;
            }
            inputProdutoWebview.checked = false;
        });
    }
    if (inputProdutoWebview) {
        inputProdutoWebview.addEventListener('change', () => {
            if (!inputProdutoWebview.checked) {
                return;
            }
            inputProdutoIos.checked = false;
            inputProdutoAndroid.checked = false;
        });
    }
});
