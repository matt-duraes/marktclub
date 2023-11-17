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
        PopupDemandaEditar.abrir();
    });
    // botaoSalvar.addEventListener('click', async () => {
    //     if (!validarCamposParaEditar()) {
    //         return;
    //     }
    //     const body = montarBodyParaEditar();
    //     const resposta = await fetch(LINK + '/demanda/demanda-editar/' + idDemanda, {
    //         method: 'POST',
    //         body,
    //     });

    //     const json = await respostaJson(
    //         resposta,
    //         'Ocorreu um erro ao atualizar sua demanda, por favor, tente novamente.'
    //     );
    //     if (false === json) {
    //         return;
    //     }

    //     Alerta.notificacao('Demanda atualizada com sucesso.', true);
    //     PaginaDemanda.abrir();
    // });

    // const validarCamposParaEditar = () => {
    //     let mensagem = '';
    //     if (inputTitulo.value == '') {
    //         mensagem = 'Digite um título para a demanda para continuar.';
    //     } else if (inputEmpresa.value == '') {
    //         mensagem = 'Escolha uma empresa para continuar.';
    //     } else if (inputDono.value == '') {
    //         mensagem = 'Escolha um dono da demanda para continuar.';
    //     } else if (inputComPrazo.checked && inputDataEntrega.value == '') {
    //         mensagem = 'Demanda com prazo deve ter uma data para entrega.';
    //     }

    //     if (mensagem == '') {
    //         return true;
    //     }

    //     Alerta.notificacao(mensagem, false);
    //     return false;
    // };
    // const montarBodyParaEditar = () => {
    //     const body = new FormData();
    //     body.append('titulo', inputTitulo.value);
    //     body.append('empresa', inputEmpresa.value);
    //     body.append('dono', inputDono.value);
    //     body.append('com_prazo', inputComPrazo.value);
    //     body.append('data_entrega', inputDataEntrega.value);
    //     return body;
    // };

    // inputComPrazo.addEventListener('change', () => {
    //     if (inputComPrazo.checked) {
    //         blocoDataEntrega.classList.add('ativo');
    //         inputDataEntrega.focus();
    //         return;
    //     }
    //     blocoDataEntrega.classList.remove('ativo');
    //     inputDataEntrega.value = '';
    // });
};
