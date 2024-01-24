// @template "painel"
// @system "Icone"
// @system "Popup"

window.addEventListener('load', () => {
    const blocoTipo = document.querySelector('#TABELA_TIPO');
    const tipo = blocoTipo.value;
    blocoTipo.parentNode.removeChild(blocoTipo);

    const inputUpload = document.querySelector('#input_upload');
    const botaoAnalisar = document.querySelector('#botao_analisar');
    const blocoHeader = document.querySelector('#bloco_header');

    const blocoTotal = document.querySelector('#bloco_total_upload');
    const blocoFinalizado = document.querySelector('#bloco_tabela_finalizado');
    const blocoZero = document.querySelector('#bloco_zero');
    const blocoErro = document.querySelector('#bloco_erros');

    const blocoComando = document.querySelector('#bloco_botao_comando');
    const botaoEnviar = document.querySelector('#botao_enviar');
    const botaoContinuar = document.querySelector('#botao_continuar');
    const botaoCancelar = document.querySelector('#botao_cancelar');
    const botaoReiniciar = document.querySelector('#botao_reiniciar');

    /*
    |--------------------------------------------------------------------------
    | ANALISAR ARQUIVO
    |--------------------------------------------------------------------------
    */
    inputUpload.addEventListener('change', () => {
        if (inputUpload.files.length == 1) {
            botaoAnalisar.classList.add('ativo');
            return;
        }
        botaoAnalisar.classList.remove('ativo');
    });
    botaoAnalisar.addEventListener('click', async () => {
        const arquivo = inputUpload.files[0];
        const quantidade = arquivo.length;

        if (quantidade == 0) {
            inputUpload.value = '';
            botaoAnalisar.classList.remove('ativo');
            Alerta.notificacao('Adicione uma tabela para analisar.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('arquivo', arquivo);
        body.append('tipo', tipo);

        const resposta = await fetch(LINK + '/tabela/analisar', {
            method: 'POST',
            body,
        });
        Loading.hide();

        try {
            tratarRespostaAnalisar(resposta, arquivo);
        } catch (error) {
            Alerta.notificacao('Erro ao analisar tabela.', false);
        }

        inputUpload.value = '';
        botaoAnalisar.classList.remove('ativo');
    });

    const tratarRespostaAnalisar = async (resposta, arquivo) => {
        const json = await resposta.json();

        if (resposta.status == 200 && json.status == 'sucesso' && json.dado != undefined) {
            const quantidadeUsuario = json.dado.length;

            const confirmar = await Alerta.confirmar(
                'Tabela analisada',
                `Sua tabela foi analisada com sucesso e tem ${quantidadeUsuario} ${quantidadeUsuario > 1 ? 'usuários' : 'usuario'}. Deseja enviar?`,
                true
            );
            if (confirmar) {
                acaoAoClicarBotaoEnviar(arquivo);
            }

            return;
        }

        if (resposta.status == 400 && json.status == 'erro' && json.lista != undefined) {
            blocoZero.classList.remove('show');
            carregarMensagemErro(json.lista);
            const quantidadeErro = json.lista.length;

            return Alerta.mensagem(
                'Tabela analisada',
                `Sua tabela foi analisada e foi encontrado ${quantidadeErro} ${quantidadeErro > 1 ? 'erros' : 'erro'}. Verifique os erros para poder continuar.`,
                false
            );
        }

        Alerta.notificacao(
            json.status == 'erro' && json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao analisar tabela.',
            false
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADD LISTA DE ERRO
    |--------------------------------------------------------------------------
    */
    const carregarMensagemErro = lista => {
        let html = `
            <div class="linha titulo">
                <div class="id">Linha</div>
                <div class="mensagem">Erro</div>
            </div>
        `;

        lista.forEach(item => {
            html += `
                <div class="linha dado">
                    <div class="id">${item.linha}</div>
                    <div class="mensagem">${item.mensagem}</div>
                    <button class="bloco_botao_remover">
                        <i class="botao_remover">${Icone.fechar(8)}</i>
                    </button>
                </div>
            `;
        });
        blocoErro.classList.add('show');
        blocoErro.innerHTML = html;
    };

    /*
    |--------------------------------------------------------------------------
    | BOTÃO PARA REMOVER ERRO
    |--------------------------------------------------------------------------
    */
    blocoErro.addEventListener('click', e => {
        const target = e.target;
        if (target.classList.contains('bloco_botao_remover') ||
            target.classList.contains('botao_remover') ||
            target.closest('.bloco_botao_remover') ||
            target.closest('.botao_remover')
        ) {
            removerLinhaDeErro(target.closest('.linha'));
        }
    });

    const removerLinhaDeErro = linha => {
        if (!linha) {
            return;
        }

        linha.parentNode.removeChild(linha);
        if (blocoErro.querySelectorAll('.linha.dado').length > 0) {
            return;
        }

        blocoErro.innerHTML = `
            <div class="zero">
                <i>${Icone.check(40)}</i>
                <p>Todos os erros foram removidos</p>
            </div>
        `;

        blocoErro.classList.add('show');
    };

    /*
    |--------------------------------------------------------------------------
    | ACAO BOTAO ENVIAR TABELA
    |--------------------------------------------------------------------------
    */
    const acaoAoClicarBotaoEnviar = async (arquivo) => {
        Loading.show();

        const body = new FormData();
        body.append('arquivo', arquivo);
        body.append('tipo', tipo);

        const resposta = await fetch(LINK + '/tabela/salvar', {
            method: 'POST',
            body,
        });
        Loading.hide();

        try {
            tratarRespostaEnviar(resposta);
        } catch (error) {
            Alerta.notificacao('Erro ao enviar tabela.', false);
        }
    };

    const tratarRespostaEnviar = async (resposta) => {
        const json = await resposta.json();

        if (resposta.status == 200 && json.status == 'sucesso') {
            Alerta.mensagem(
                'Tabela enviada com sucesso!',
                `Sua tabela foi enviada com sucesso e está sendo processada. Você pode ver o status da tabela na página de histórico.`,
                true
            );
            return;
        }

        Alerta.notificacao(
            json.status == 'erro' && json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao enviar tabela.',
            false
        );
    }
});
