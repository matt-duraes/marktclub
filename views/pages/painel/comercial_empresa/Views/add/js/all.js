// @template "painel"
window.addEventListener('load', () => {
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

    formSelectChange = funcao => {
        if (funcao == 'finalidadePrincipal') {
            finalidadeMudou();
        }
    };

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

    const finalidadeMudou = () => {
        const valor = inputFinalidadePrincipal.value;
        buscarListaFinalidadeSecundaria(valor);
        if (valor == 'publica') {
            blocoDataEleicao.classList.remove('display_none');
            return;
        }
        blocoDataEleicao.classList.add('display_none');
        inputDataEleicao.value = '';
    };
    const buscarListaFinalidadeSecundaria = async tipo => {
        formSelectLoading(inputFinalidadeSecundaria);
        const body = new FormData();
        body.append('classe', 'finalidade_secundaria');
        body.append('tipo', tipo);

        const resposta = await fetch(LINK + '/app/classe/comercial-empresa', {
            method: 'POST',
            body,
        });
        const json = await respostaJson(resposta, 'Ocorreu um erro ao pegar lista de finalidade secundária.');
        if (false === json) {
            formSelectOption(inputFinalidadeSecundaria, { '': 'Ocorreu um erro ao buscar lista' }, '');
            return;
        }
        formSelectOption(inputFinalidadeSecundaria, json.dado, '');
    };

    inputProdutoClube.addEventListener('change', () => {
        mudarDisplay(inputProdutoClube, [blocoTipoSite]);
    });
    inputComunicacaoEmail.addEventListener('change', () => {
        mudarDisplay(inputComunicacaoEmail, [blocoEmailDisparo, blocoEmailDia]);
    });
    inputComunicacaoWhatsapp.addEventListener('change', () => {
        mudarDisplay(inputComunicacaoWhatsapp, [blocoWhatsappDia]);
    });
    inputComunicacaoRedeSocial.addEventListener('change', () => {
        mudarDisplay(inputComunicacaoRedeSocial, [blocoRedeSocialDia]);
    });
    inputProdutoIos.addEventListener('change', () => {
        if (!inputProdutoIos.checked) {
            return;
        }
        inputProdutoWebview.checked = false;
    });
    inputProdutoAndroid.addEventListener('change', () => {
        if (!inputProdutoAndroid.checked) {
            return;
        }
        inputProdutoWebview.checked = false;
    });
    inputProdutoWebview.addEventListener('change', () => {
        if (!inputProdutoWebview.checked) {
            return;
        }
        inputProdutoIos.checked = false;
        inputProdutoAndroid.checked = false;
    });
});
