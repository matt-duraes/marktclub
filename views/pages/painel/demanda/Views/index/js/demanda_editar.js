const demandaEditar = () => {
    const inputTitulo = $('#input_demanda_titulo');
    const inputEmpresa = $('#input_demanda_empresa');
    const inputEquipe = $('#input_demanda_equipe');
    const inputEntrega = $('#input_demanda_entrega');

    const demandaTitulo = $('#input_demanda_editar_titulo');
    const demandaEmpresa = $('#input_demanda_editar_empresa');
    const demandaEquipe = $('#input_demanda_editar_equipe');
    const demandaComPrazo = $('#input_demanda_editar_com_prazo');
    const demandaEntrega = $('#input_demanda_editar_data_entrega');
    const blocoData = $('.bloco_input_data_entrega');
    formValue(demandaTitulo, inputTitulo.value);
    formValue(demandaEmpresa, inputEmpresa.value);
    formValue(demandaEquipe, inputEquipe.value);

    blocoData.classList.add('display_none');
    demandaComPrazo.checked = false;
    demandaEntrega.value = '';
    if (inputEntrega.value != '') {
        blocoData.classList.remove('display_none');
        demandaComPrazo.checked = true;
        formValue(demandaEntrega, inputEntrega.value);
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

    const botaoFechar = $('#botao_cancelar_edicao');
    const botaoSalvar = $('#botao_editar_tarefa');

    botaoFechar.addEventListener('click', () => {
        PopupDemandaEditar.fechar();
    });
    botaoSalvar.addEventListener('click', async () => {
        if (!validarCamposParaEditar()) {
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/demanda/demanda-editar/' + idDemanda,
            {
                titulo: demandaTitulo.value,
                empresa: demandaEmpresa.value,
                dono: demandaEquipe.value,
                /* eslint-disable */
                com_prazo: demandaComPrazo.value,
                data_entrega: demandaEntrega.value,
                /* eslint-enable */
            },
            'Erro ao editar demanda, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }

        Alerta.notificacao('Demanda atualizada com sucesso.', true);
        PopupDemandaEditar.fechar();
    });

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
