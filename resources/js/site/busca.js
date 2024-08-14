window.addEventListener('load', () => {
    const formBuscar = $('#form_buscar');
    if (!formBuscar) {
        return;
    }
    const botaoBuscarAbrir = $$('.botao_buscar_abrir');
    const botaoBuscarFechar = $$('.botao_buscar_fechar');
    const blocoBuscarTemplate = $('#bloco_template_buscar');

    blocoBuscarTemplate.appendChild(formBuscar);

    const abrirBlocoBusca = e => {
        const target = e.target;
        let id = '';
        if (target.classList.contains('botao_buscar_input') || target.closest('.botao_buscar_input')) {
            const bloco = target.classList.contains('botao_buscar_input')
                ? target
                : target.closest('.botao_buscar_input');
            id = bloco.getAttribute('id').replace(/\_fake(_texto){0,1}$/, '');
        }
        BODY.classList.add('body_scroll_hidden');
        formBuscar.classList.remove('display_none');
        setTimeout(() => {
            formBuscar.classList.add('ativo');
        }, 40);
        setTimeout(() => {
            if (id != '') {
                formFocus($('#' + id));
            }
        }, 340);
    };
    botaoBuscarAbrir.forEach(botao => {
        botao.addEventListener('click', e => {
            abrirBlocoBusca(e);
        });
    });

    formBuscar.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'form_buscar') {
            fecharBusca();
        }
    });
    botaoBuscarFechar.forEach(botao => {
        botao.addEventListener('click', () => {
            fecharBusca();
        });
    });

    const fecharBusca = () => {
        formBuscar.classList.remove('ativo');
        setTimeout(() => {
            BODY.classList.remove('body_scroll_hidden');
            formBuscar.classList.add('display_none');
        }, 300);
    };

    // BUSCAR POR CIDADE
    const inputMapa = $('#input_mapa input');
    const inputEstado = $('input[name=estado]', formBuscar);
    const inputCidade = $('input[name=cidade]', formBuscar);
    const inputEstabelecimento = $('input[name=estabelecimento]', formBuscar);
    const inputLatitude = $('input[name=latitude]', formBuscar);
    const inputLongitude = $('input[name=longitude]', formBuscar);
    const inputOrdem = $('input[name=ordem]', formBuscar);
    if (inputMapa) {
        inputMapa.addEventListener('change', () => {
            formSelectOption(inputCidade, { '': 'Escolha um estado primeiro' });
            formValue(inputEstabelecimento, '');
            formValue(inputOrdem, '');
            formBuscar.classList.toggle('busca_mapa');
            if (inputMapa.checked && (inputLatitude.value == '' || inputLongitude.value == '')) {
                buscarGeolocalizacao();
            }
            if (inputMapa.checked && inputEstado.value != '') {
                buscarCidadePeloEstado(inputCidade, inputEstado.value, inputCidade.value, 'Escolha uma cidade');
            }
            if (!inputMapa.checked) {
                inputLatitude.value = '';
                inputLongitude.value = '';
            }
        });
    }

    const buscarGeolocalizacao = () => {
        Loading.show();
        navigator.geolocation.getCurrentPosition(
            position => {
                Loading.hide();
                inputLatitude.value = position.coords.latitude;
                inputLongitude.value = position.coords.longitude;
            },
            e => {
                Loading.hide();
                if (e.message == 'User denied Geolocation') {
                    Alerta.mensagem(
                        'Localização bloqueada',
                        'Você bloqueou a geolocalização, para poder mostrar as lojas próximas a você, precisamos que desbloquei sua localização e tente novamente.',
                        '!'
                    );
                    return;
                }
                Alerta.mensagem(
                    'Erro na localização',
                    'Ocorreu um erro ao pegar sua localização, verifique suas permissões no navegador e tente novamente.',
                    '!'
                );
            }
        );
    };

    // Botão buscar
    const botaoBuscar = $('#botao_buscar_loja');
    const fazerBusca = e => {
        if (inputMapa && inputMapa.checked && !vazio(inputEstado.valor()) && vazio(inputCidade.valor())) {
            e.preventDefault();
            Alerta.notificacao('Escolha uma cidade para continuar com sua busca.', false);
            return;
        }
        Loading.show();
    };
    if (botaoBuscar) {
        botaoBuscar.evento('click', e => {
            fazerBusca(e);
        });
    }

    // Buscar cidade
    if (inputEstado) {
        inputEstado.evento('formChange', () => {
            buscarCidadePeloEstado(inputCidade, inputEstado.value, '', 'Escolha uma cidade');
        });
        if (inputEstado.value != '') {
            buscarCidadePeloEstado(inputCidade, inputEstado.value, inputCidade.value, 'Escolha uma cidade');
        }
    }
});
