// @template "painel"
// @system "Icone"
// @system "Popup"

window.addEventListener('load', () => {
    const blocoAcao = document.querySelector('#TABELA_ACAO');
    const acao = blocoAcao.value;
    blocoAcao.parentNode.removeChild(blocoAcao);

    const inputUpload = document.querySelector('#input_upload');
    const botaoAnalisar = document.querySelector('#botao_analisar');
    const blocoHeader = document.querySelector('#bloco_header');

    const blocoTotal = document.querySelector('#bloco_total_upload');
    const blocoFinalizado = document.querySelector('#bloco_tabela_finalizado');
    const blocoZero = document.querySelector('#bloco_zero');
    const blocoErro = document.querySelector('#bloco_erros');
    const PopupErro = new Popup('lista-erros', 'popup_erros', true, true);

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
        const arquivo = inputUpload.files;
        const quantidade = arquivo.length;

        if (quantidade == 0) {
            inputUpload.value = '';
            botaoAnalisar.classList.remove('ativo');
            Alerta.notificacao('Adicione uma tabela para analisar.', false);
            return;
        }

        Loading.show();

        const body = new FormData();
        body.append('arquivo', arquivo[0]);
        body.append('tipo', acao);

        const resposta = await fetch(LINK + '/tabela/analisar', {
            method: 'POST',
            body,
        });

        Loading.hide();

        inputUpload.value = '';
        botaoAnalisar.classList.remove('ativo');

        try {
            tratarRespostaAnalisar(resposta);
        } catch (error) {
            Alerta.notificacao('Erro ao analisar tabela.', false);
        }
    });

    const tratarRespostaAnalisar = async (resposta) => {
        const json = await resposta.json();

        if (resposta.status == 200 && json.status == 'sucesso' && json.dado != undefined) {
            const quantidadeUsuario = json.dado.length;

            const confirmar = await Alerta.confirmar(
                'Tabela analisada',
                `Sua tabela foi analisada com sucesso e tem ${quantidadeUsuario} ${quantidadeUsuario > 1 ? 'usuários' : 'usuario'}. Deseja enviar?`,
                true
            );
            if (confirmar) {
                acaoAoClicarBotaoEnviar();
            }

            return;
        }

        if (resposta.status == 400 && json.status == 'erro' && json.lista != undefined) {
            carregarMensagemErro(json.lista);
            return PopupErro.abrir();
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
});
