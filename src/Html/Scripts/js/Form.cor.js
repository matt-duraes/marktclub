const bodyFormCor = document.querySelector('body');

const fwFormCorInArray = function (needle, haystack) {
    const tamanho = haystack.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
};

bodyFormCor.insertAdjacentHTML(
    'beforeend',
    `
    <div id="fw_form_cor">
        <div class="fw_form_cor_conteudo">
            <header class="fw_form_cor_header">
                <button class="fw_form_cor_fechar fw_form_cor_fechar_mobile"><svg height="12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 38" style="enable-background:new 0 0 50 38;" xml:space="preserve"><path d="M48.1,17.1H6.4L20,3.3c0.8-0.7,0.8-1.9,0.1-2.7c-0.7-0.8-1.9-0.8-2.7-0.1c0,0-0.1,0.1-0.1,0.1l-16.8,17 c-0.7,0.7-0.7,1.9,0,2.7l16.8,17c0.7,0.8,1.9,0.8,2.7,0.1c0.8-0.7,0.8-1.9,0.1-2.7c0,0-0.1-0.1-0.1-0.1L6.4,20.9h41.7 c1,0,1.9-0.9,1.9-1.9C50,17.9,49.2,17.1,48.1,17.1z"/></svg></button>
                <h1 class="fw_form_cor_h1">Escolha uma cor</h1>
                <button class="fw_form_cor_fechar"><svg height="12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></button>
            </header>
            <div class="fw_form_cor_scroll">
                <h2 class="fw_form_cor_h2">Escolha uma cor:</h2>
                <ul class="fw_form_cor_ul">
                    <li class="fw_form_cor_li" style="background-color: #000000" data-value="#000000"></li><li class="fw_form_cor_li" style="background-color: #696969" data-value="#696969"></li><li class="fw_form_cor_li" style="background-color: #DCDCDC" data-value="#DCDCDC"></li><li class="fw_form_cor_li" style="background-color: #6A5ACD" data-value="#6A5ACD"></li><li class="fw_form_cor_li" style="background-color: #483D8B" data-value="#483D8B"></li><li class="fw_form_cor_li" style="background-color: #000080" data-value="#000080"></li><li class="fw_form_cor_li" style="background-color: #0000FF" data-value="#0000FF"></li><li class="fw_form_cor_li" style="background-color: #6495ED" data-value="#6495ED"></li><li class="fw_form_cor_li" style="background-color: #00BFFF" data-value="#00BFFF"></li><li class="fw_form_cor_li" style="background-color: #87CEFA" data-value="#87CEFA"></li><li class="fw_form_cor_li" style="background-color: #ADD8E6" data-value="#ADD8E6"></li><li class="fw_form_cor_li" style="background-color: #00FFFF" data-value="#00FFFF"></li><li class="fw_form_cor_li" style="background-color: #00CED1" data-value="#00CED1"></li><li class="fw_form_cor_li" style="background-color: #40E0D0" data-value="#40E0D0"></li><li class="fw_form_cor_li" style="background-color: #20B2AA" data-value="#20B2AA"></li><li class="fw_form_cor_li" style="background-color: #008B8B" data-value="#008B8B"></li><li class="fw_form_cor_li" style="background-color: #7FFFD4" data-value="#7FFFD4"></li><li class="fw_form_cor_li" style="background-color: #00FA9A" data-value="#00FA9A"></li><li class="fw_form_cor_li" style="background-color: #7CFC00" data-value="#7CFC00"></li><li class="fw_form_cor_li" style="background-color: #9ACD32" data-value="#9ACD32"></li><li class="fw_form_cor_li" style="background-color: #008000" data-value="#008000"></li><li class="fw_form_cor_li" style="background-color: #DAA520" data-value="#DAA520"></li><li class="fw_form_cor_li" style="background-color: #8B4513" data-value="#8B4513"></li><li class="fw_form_cor_li" style="background-color: #A0522D" data-value="#A0522D"></li><li class="fw_form_cor_li" style="background-color: #BC8F8F" data-value="#BC8F8F"></li><li class="fw_form_cor_li" style="background-color: #F4A460" data-value="#F4A460"></li><li class="fw_form_cor_li" style="background-color: #7B68EE" data-value="#7B68EE"></li><li class="fw_form_cor_li" style="background-color: #8A2BE2" data-value="#8A2BE2"></li><li class="fw_form_cor_li" style="background-color: #4B0082" data-value="#4B0082"></li><li class="fw_form_cor_li" style="background-color: #A020F0" data-value="#A020F0"></li><li class="fw_form_cor_li" style="background-color: #FF00FF" data-value="#FF00FF"></li><li class="fw_form_cor_li" style="background-color: #EE82EE" data-value="#EE82EE"></li><li class="fw_form_cor_li" style="background-color: #FF1493" data-value="#FF1493"></li><li class="fw_form_cor_li" style="background-color: #FF69B4" data-value="#FF69B4"></li><li class="fw_form_cor_li" style="background-color: #DB7093" data-value="#DB7093"></li><li class="fw_form_cor_li" style="background-color: #FFB6C1" data-value="#FFB6C1"></li><li class="fw_form_cor_li" style="background-color: #F08080" data-value="#F08080"></li><li class="fw_form_cor_li" style="background-color: #DC143C" data-value="#DC143C"></li><li class="fw_form_cor_li" style="background-color: #800000" data-value="#800000"></li><li class="fw_form_cor_li" style="background-color: #B22222" data-value="#B22222"></li><li class="fw_form_cor_li" style="background-color: #FF6347" data-value="#FF6347"></li><li class="fw_form_cor_li" style="background-color: #FF0000" data-value="#FF0000"></li><li class="fw_form_cor_li" style="background-color: #FF8C00" data-value="#FF8C00"></li><li class="fw_form_cor_li" style="background-color: #FFD700" data-value="#FFD700"></li><li class="fw_form_cor_li" style="background-color: #FFFF00" data-value="#FFFF00"></li><li class="fw_form_cor_li" style="background-color: #F0E68C" data-value="#F0E68C"></li><li class="fw_form_cor_li" style="background-color: #B0E0E6" data-value="#B0E0E6"></li><li class="fw_form_cor_li" style="background-color: #E0FFFF" data-value="#E0FFFF"></li><li class="fw_form_cor_li" style="background-color: #F0FFF0" data-value="#F0FFF0"></li><li class="fw_form_cor_li" style="background-color: #FFFFFF" data-value="#FFFFFF"></li>
                </ul>
                <div class="fw_form_cor_padrao" id="fw_form_cor_padrao">
                    <div class="fw_form_cor_padrao_checkbox"><svg height="10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg></div>
                    <div class="fw_form_cor_padrao_titulo"></div>
                </div>
                <h2 class="fw_form_cor_h2">Ou digite manualmente em hexadecimal:</h2>
                <div class="fw_form_cor_bloco_input">
                    <div class="fw_form_cor_hashtag">#</div>
                    <input name="cor" placeholder="Cor" maxlength="6" inputmode="numeric" class="fw_form_cor_input" id="fw_form_cor_input" value="">
                    <button class="fw_form_cor_botao_confirmar" id="fw_form_cor_botao_confirmar">Salvar</button>
                    <div class="fw_form_cor_bloco_remover" id="fw_form_cor_bloco_remover">
                        <div class="fw_form_cor_ou">ou</div>
                        <button class="fw_form_cor_botao_remover" id="fw_form_cor_botao_remover">Remover cor atual</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `
);

const fwFormCor = document.getElementById('fw_form_cor');
const fwFormCorLi = fwFormCor.querySelectorAll('.fw_form_cor_li');
const fwFormCorInput = fwFormCor.querySelector('#fw_form_cor_input');
const fwFormCorBotaoConfirmar = fwFormCor.querySelector('#fw_form_cor_botao_confirmar');
const fwFormCorBotaoRemover = fwFormCor.querySelector('#fw_form_cor_botao_remover');
const fwFormCorBlocoRemover = fwFormCor.querySelector('#fw_form_cor_bloco_remover');
const fwFormCorBotaoFechar = fwFormCor.querySelectorAll('.fw_form_cor_fechar');
const fwFormCorBotaoPadrao = fwFormCor.querySelector('#fw_form_cor_padrao');

let inputCorAtual;
fwFormCorBotaoFechar.forEach(botaoFechar => {
    botaoFechar.addEventListener('click', () => {
        fwFormCorBoxFechar();
    });
});

fwFormCorLi.forEach(li => {
    li.addEventListener('click', () => {
        const valor = li.getAttribute('data-value');
        fwFormCorBoxFechar(valor);
    });
});

fwFormCorInput.addEventListener('keydown', e => {
    const tecla = e.key;
    const valor = fwFormCorInput.value;
    const controlKey = e.metaKey || e.ctrlKey;
    const objetoSelecionado = document.getSelection();
    const textoSelecionado = objetoSelecionado.toString() || '';
    const posicaoCursorInicial = fwFormCorInput.selectionStart;
    const posicaoCursorFinal = fwFormCorInput.selectionEnd;
    const valorSelecionado = textoSelecionado || posicaoCursorInicial != posicaoCursorFinal;

    if (
        fwFormCorInArray(tecla, fwFormKeyGeral) ||
        (controlKey && fwFormCorInArray(tecla, fwFormKeyControl)) ||
        (/^[a-fA-F0-9]{1}$/.test(tecla) && (valor.length < 6 || valorSelecionado))
    ) {
        return true;
    } else if (tecla == 'Enter' && /^[a-fA-F0-9]{6}$/.test(valor)) {
        return fwFormCorBoxFechar('#' + valor);
    }
    e.preventDefault();
});

fwFormCorBotaoConfirmar.addEventListener('click', () => {
    const valor = fwFormCorInput.value;
    if (/^[a-fA-F0-9]{6}$/.test(valor)) {
        return fwFormCorBoxFechar('#' + valor);
    }
});
fwFormCorBotaoRemover.addEventListener('click', () => {
    const input = inputCorAtual.querySelector('input');
    const blocoCor = inputCorAtual.querySelector('.input_cor_bg');
    if (input) {
        input.value = '';
        blocoCor.style.backgroundColor = '';
    }
    fwFormCorBoxFechar();
});

fwFormCorBotaoPadrao.addEventListener('click', () => {
    const checked = fwFormCorBotaoPadrao.classList.contains('fw_form_cor_padrao_checked');
    const input = inputCorAtual.querySelector('input');
    const blocoCor = inputCorAtual.querySelector('.input_cor_bg');

    if (checked) {
        fwFormCorBotaoPadrao.classList.remove('fw_form_cor_padrao_checked');
        input.value = '';
        blocoCor.style.backgroundColor = '';
    }
});

const fwFormCorBoxFechar = cor => {
    bodyFormCor.classList.remove('fw_body_hidden');
    fwFormCor.classList.remove('fw_form_cor_abrir');
    setTimeout(() => {
        fwFormCorInput.value = '';
        fwFormCor.style.display = 'none';
    }, 300);
    if (!cor) {
        return true;
    }
    const input = inputCorAtual.querySelector('input');
    const blocoCor = inputCorAtual.querySelector('.input_cor_bg');
    if (!input) {
        return;
    }
    input.value = cor;
    if (cor == 'padrao') {
        blocoCor.style.backgroundImage =
            'linear-gradient(45deg, #2c67c7, #2c67c7 25%, #ab1829 50%, #0ec94d 75%, #e07809)';
        blocoCor.style.backgroundSize = '50px 45px';
        return;
    }
    blocoCor.style.backgroundColor = cor;
};
const fwFormCorBoxAbrir = bloco => {
    bodyFormCor.classList.add('fw_body_hidden');
    inputCorAtual = bloco;
    const input = bloco.querySelector('input');
    const valor = input.value;
    if (valor == '') {
        fwFormCorBlocoRemover.style.display = 'none';
    } else {
        fwFormCorBlocoRemover.style.display = 'flex';
    }

    fwFormCor.style.display = 'flex';
    setTimeout(() => {
        fwFormCor.classList.add('fw_form_cor_abrir');
    }, 20);
};

fwFormLoadingCor = bloco => {
    const inputCorLista = bloco.querySelectorAll('.input_cor');
    if (inputCorLista.length > 0) {
        inputCorLista.forEach(blocoCor => {
            const botao = blocoCor.querySelector('.input_cor_conteudo');
            botao.addEventListener('click', () => {
                fwFormCorBoxAbrir(blocoCor);
            });
        });
    }
};
