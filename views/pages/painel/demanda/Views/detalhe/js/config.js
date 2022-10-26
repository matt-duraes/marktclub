const loadingConfig = () => {
    const idDemanda = document.getElementById('input_id_demanda').value;
    const hashConfig = document.querySelector('#form_config input[name=form_system_hash]').value;

    const bodyGeral = document.querySelector('body');
    const botaoConfig = document.getElementById('botao_config');
    const blocoSeta = document.getElementById('bloco_config_seta');
    const blocoMenu = document.getElementById('bloco_config_menu');
    const botaoArquivar = document.getElementById('botao_tarefa_arquivar');

    if (botaoConfig) {
        botaoConfig.addEventListener('click', () => {
            abrirBlocoConfig();
        });

        bodyGeral.addEventListener('click', e => {
            const target = e.target;
            if (
                target.getAttribute('id') != 'bloco_config_menu' &&
                !target.closest('#bloco_config_menu') &&
                target.getAttribute('id') != 'bloco_config_seta' &&
                !target.closest('#bloco_config_seta') &&
                target.getAttribute('id') != 'botao_config' &&
                !target.closest('#botao_config')
            ) {
                fecharBlocoConfig();
            }
        });

        botaoArquivar.addEventListener('click', async () => {
            if (
                await Alerta.confirmar(
                    'Arquivar tarefa!',
                    'Tem certeza que deseja arquivar essa tarefa? Essa ação não poderá ser desfeita.',
                    '!'
                )
            ) {
                arquivarTarefa();
            }
        });
    }

    const abrirBlocoConfig = () => {
        blocoSeta.style.display = 'block';
        blocoMenu.style.display = 'flex';
    };

    const fecharBlocoConfig = () => {
        blocoSeta.style.display = 'none';
        blocoMenu.style.display = 'none';
    };

    const arquivarTarefa = async () => {
        const body = new FormData();
        body.append('id', idDemanda);
        body.append('form_system_hash', hashConfig);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/demanda/arquivar', {
            method: 'PUT',
            body,
        });

        if (response.status == 204) {
            Alerta.notificacao('Tarefa arquivada com sucesso.', true);
            Pagina.staticFechar();
            const bloco = document.getElementById('tarefa_' + idDemanda);
            if (bloco) {
                const blocoLista = bloco.closest('.bloco_coluna');
                if (blocoLista) {
                    verificarSeAcabouTarefas(blocoLista);
                }
                bloco.parentNode.removeChild(bloco);
            }
            return;
        }

        const json = await response.json();
        const mensagem = json.mensagem != undefined ? json.mensagem : 'Ocorreu um erro ao arquivar a tarefa.';
        Alerta.notificacao(mensagem, false);
    };

    const verificarSeAcabouTarefas = bloco => {
        const quantidade = bloco.querySelectorAll('article').length;
        if (quantidade > 1) {
            return;
        }
        const blocoMais = bloco.querySelector('.botao_encolher');
        if (blocoMais) {
            blocoMais.classList.add('hide');
        }
        const blocoZero = bloco.querySelector('.tarefa_zero');
        if (blocoZero) {
            blocoZero.classList.remove('hide');
        }
    };
};
