const demandaEditar = () => {
    const inputTitulo = $('#input_demanda_titulo');
    const inputEmpresa = $('#input_demanda_empresa');
    const inputEquipe = $('#input_demanda_equipe');
    const inputEntrega = $('#input_demanda_entrega');
    const inputTexto = $('#input_demanda_texto');

    const demandaTitulo = $('#input_demanda_editar_titulo');
    const demandaEmpresa = $('#input_demanda_editar_empresa');
    const demandaEquipe = $('#input_demanda_editar_equipe');
    const demandaComPrazo = $('#input_demanda_editar_com_prazo');
    const demandaEntrega = $('#input_demanda_editar_data_entrega');
    const demandaTexto = $('#input_demanda_editar_texto');

    const blocoData = $('.bloco_input_data_entrega');

    const blocoDemandaEntrega = $('#bloco_demanda_entrega_bloco');
    const blocoDemandaEntregaValor = $('#bloco_demanda_entrega_valor');

    const blocoDemandaTitulo = $('#bloco_demanda_titulo');
    const blocoDemandaTexto = $('#bloco_demanda_texto');

    formValue(demandaTitulo, inputTitulo.value);
    formValue(demandaEmpresa, inputEmpresa.value);
    formValue(demandaEquipe, inputEquipe.value);
    formValue(demandaTexto, inputTexto.value);

    blocoData.classList.add('display_none');
    demandaComPrazo.checked = false;
    demandaEntrega.value = '';
    if (inputEntrega.value != '') {
        blocoData.classList.remove('display_none');
        demandaComPrazo.checked = true;
        demandaEntrega.valor(inputEntrega.value);
    }
    demandaComPrazo.addEventListener('change', () => {
        demandaEntrega.value = '';
        if (demandaComPrazo.checked) {
            demandaEntrega.focus();
            blocoData.classList.remove('display_none');
            return;
        }
        blocoData.classList.add('display_none');
    });

    const botaoSalvar = $('#botao_editar_tarefa');
    if (botaoSalvar.attr('data-carregado') != 1) {
        botaoSalvar.attr('data-carregado', 1);
        botaoSalvar.addEventListener('click', async () => {
            if (!validarCamposParaEditar()) {
                return;
            }
            const titulo = demandaTitulo.value;
            const texto = demandaTexto.value;
            const empresa = demandaEmpresa.value;
            const equipe = demandaEquipe.value;
            const dataEntrega = demandaEntrega.value;
            Loading.show();
            const resposta = await ajaxPost(
                LINK + '/demanda/demanda-editar/' + idDemanda,
                {
                    titulo,
                    texto,
                    empresa,
                    dono: equipe,
                    /* eslint-disable */
                    com_prazo: demandaComPrazo.value,
                    data_entrega: dataEntrega,
                    /* eslint-enable */
                },
                'Erro ao editar demanda, por favor, tente novamente.'
            );
            Loading.hide();
            if (false === resposta) {
                return;
            }

            Alerta.notificacao('Demanda atualizada com sucesso.', true);
            mudarDadoDemanda(titulo, texto, empresa, equipe, dataEntrega);
            mudarDadoLinha(titulo, texto, dataEntrega);
            PopupDemandaEditar.fechar();
        });
    }

    const mudarDadoDemanda = (titulo, texto, empresa, equipe, dataEntrega) => {
        inputTitulo.value = titulo;
        inputTexto.value = texto;
        inputEmpresa.value = empresa;
        inputEquipe.value = equipe;
        inputEntrega.value = dataEntrega;
        blocoDemandaEntregaValor.texto(dataEntrega);
        if (!vazio(demandaEntrega.valor())) {
            blocoDemandaEntrega.aparecer();
        } else {
            blocoDemandaEntrega.sumir();
        }

        blocoDemandaTitulo.texto(titulo);
        blocoDemandaTexto.html(texto);
    };
    const mudarDadoLinha = (titulo, texto, dataEntrega) => {
        const blocoLinha = $('#id_demanda_' + idDemanda);
        const blocoTitulo = $('.tarefa_titulo', blocoLinha);
        if (blocoTitulo) {
            blocoTitulo.texto(titulo);
        }
        const blocoTexto = $('.tarefa_texto', blocoLinha);
        if (blocoTexto) {
            blocoTexto.html(texto);
        }
        const blocoDataEntrega = $('.bloco_data_entrega', blocoLinha);
        if (blocoDataEntrega && !vazio(dataEntrega)) {
            blocoDataEntrega.aparecer();
            $('.tarefa_data_entrega', blocoDataEntrega).texto(dataEntrega);
        } else if (blocoDataEntrega) {
            blocoDataEntrega.sumir();
        }
    };

    const validarCamposParaEditar = () => {
        let mensagem = '';
        if (demandaTitulo.value == '') {
            mensagem = 'Digite um título para a demanda para continuar.';
        } else if (demandaEmpresa.value == '') {
            mensagem = 'Escolha uma empresa para continuar.';
        } else if (demandaEquipe.value == '') {
            mensagem = 'Escolha um dono da demanda para continuar.';
        } else if (demandaComPrazo.checked && demandaEntrega.value == '') {
            mensagem = 'Demanda com prazo deve ter uma data para entrega.';
        }

        if (mensagem == '') {
            return true;
        }

        Alerta.notificacao(mensagem, false);
        return false;
    };
};
