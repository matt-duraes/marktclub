const demandaEditar = () => {
    const idDemanda = document.getElementById('input_id_demanda').value;
    const inputTitulo = document.getElementById('input_titulo');
    const inputDono = document.getElementById('input_dono');
    const inputEmpresa = document.getElementById('input_empresa');
    const inputComPrazo = document.getElementById('input_com_prazo');
    const inputDataEntrega = document.getElementById('input_data_entrega');

    const blocoDataEntrega = document.querySelector('.bloco_input_data_entrega');

    const botaoFechar = document.getElementById('botao_cancelar_edicao');
    const botaoSalvar = document.getElementById('botao_editar_tarefa');

    Calendario.init({
        input: '#input_data_entrega',
    });

    const PaginaDemanda = new Pagina(
        'demanda-' + idDemanda,
        LINK + '/demanda/demanda/' + idDemanda,
        {},
        true,
        false,
        demandaDetalhe
    );

    botaoFechar.addEventListener('click', () => {
        PaginaDemanda.abrir();
    });
    botaoSalvar.addEventListener('click', async () => {
        if (!validarCamposParaEditar()) {
            return;
        }
        const body = montarBodyParaEditar();
        const resposta = await fetch(LINK + '/demanda/demanda-editar/' + idDemanda, {
            method: 'POST',
            body,
        });

        const json = await respostaJson(
            resposta,
            'Ocorreu um erro ao atualizar sua demanda, por favor, tente novamente.'
        );
        if (false === json) {
            return;
        }

        Alerta.notificacao('Demanda atualizada com sucesso.', true);
        PaginaDemanda.abrir();
    });

    const validarCamposParaEditar = () => {
        let mensagem = '';
        if (inputTitulo.value == '') {
            mensagem = 'Digite um título para a demanda para continuar.';
        } else if (inputEmpresa.value == '') {
            mensagem = 'Escolha uma empresa para continuar.';
        } else if (inputDono.value == '') {
            mensagem = 'Escolha um dono da demanda para continuar.';
        } else if (inputComPrazo.checked && inputDataEntrega.value == '') {
            mensagem = 'Demanda com prazo deve ter uma data para entrega.';
        }

        if (mensagem == '') {
            return true;
        }

        Alerta.notificacao(mensagem, false);
        return false;
    };
    const montarBodyParaEditar = () => {
        const body = new FormData();
        body.append('titulo', inputTitulo.value);
        body.append('empresa', inputEmpresa.value);
        body.append('dono', inputDono.value);
        body.append('com_prazo', inputComPrazo.value);
        body.append('data_entrega', inputDataEntrega.value);
        return body;
    };

    inputComPrazo.addEventListener('change', () => {
        if (inputComPrazo.checked) {
            blocoDataEntrega.classList.add('ativo');
            inputDataEntrega.focus();
            return;
        }
        blocoDataEntrega.classList.remove('ativo');
        inputDataEntrega.value = '';
    });
};
