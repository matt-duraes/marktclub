// @template "painel"
// @system "Icone"

window.addEventListener('load', () => {
    const blocoAcao = document.querySelector('#TABELA_ACAO');
    const acao = blocoAcao.value;
    blocoAcao.parentNode.removeChild(blocoAcao);

    const inputUpload = document.querySelector('#input_upload');
    const botaoAnalisar = document.querySelector('#botao_analisar');

    const blocoHeader = document.querySelector('#bloco_header');
    const blocoTotal = document.querySelector('#bloco_total_upload');
    const blocoFinalizado = document.querySelector('#bloco_tabela_finalizado');
    const blocoAjuda = document.querySelector('#bloco_tabela_ajuda');
    const blocoErro = document.querySelector('#bloco_tabela_erro');
    const blocoSucesso = document.querySelector('#bloco_tabela_sucesso');

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
            blocoErro.innerHTML = '';
            blocoErro.classList.remove('show');
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

        blocoAjuda.classList.remove('show');
        blocoErro.classList.remove('show');
        blocoSucesso.classList.remove('show');

        const body = new FormData();
        body.append('arquivo', arquivo[0]);

        const resposta = await fetch(LINK + '/tabela/analisar-' + acao, {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        inputUpload.value = '';
        botaoAnalisar.classList.remove('ativo');

        if (resposta.status == 200 && json.status == 'sucesso' && json.dado != undefined) {
            carregarMensagemSucesso(json.dado);
            blocoTotal.classList.add('show');
            const quantidadeUsuario = json.dado.length;
            const quantidadeUsuarioTexto = quantidadeUsuario > 1 ? 'usuários' : 'usuario';
            const confirmar = await Alerta.confirmar(
                'Tabela analisada',
                `Sua tabela foi analisada com sucesso e tem ${quantidadeUsuario} ${quantidadeUsuarioTexto}. Deseja enviar?`,
                true
            );
            if (confirmar) {
                acaoAoClicarBotaoEnviar();
            }
            return;
        } else if (resposta.status == 400 && json.status == 'erro' && json.lista != undefined) {
            carregarMensagemErro(json.lista);
            const quantidadeErro = json.lista.length;
            const quantidadeErroTexto = quantidadeErro > 1 ? 'erros' : 'erro';
            Alerta.mensagem(
                'Tabela analisada',
                `Sua tabela foi analisada e foi encontrado ${quantidadeErro} ${quantidadeErroTexto}. Verifique os erros para poder continuar.`,
                false
            );
            return;
        }

        Alerta.notificacao(
            json.status == 'erro' && json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao analisar tabela.',
            false
        );
    });

    /*
    |--------------------------------------------------------------------------
    | ADD LISTA DE SUCESSO
    |--------------------------------------------------------------------------
    */
    const carregarMensagemSucesso = lista => {
        let html = `
            <div class="linha titulo">
                <div class="id">Nº</div>
                <div class="mensagem">Usuário</div>
            </div>
        `;
        lista.forEach(item => {
            html += `
                <div class="linha dado" data-id="${item.hash}">
                    <div class="id">${item.linha}</div>
                    <div class="mensagem">${item.titulo}</div>
                    <i>${Icone.fechar(10)}</i>
                </div>
            `;
        });
        blocoSucesso.classList.add('show');
        blocoSucesso.innerHTML = html;

        blocoHeader.classList.remove('show');
        blocoComando.classList.add('show');
        botaoEnviar.classList.add('show');
        botaoReiniciar.classList.add('show');
        inputUpload.value = '';
    };

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
                    <i class="botao_remover">${Icone.fechar(8)}</i>
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
        if (target.classList.add('botao_remover') || target.closest('.botao_remover')) {
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
    | BOTOES DE ACAO
    |--------------------------------------------------------------------------
    */
    botaoReiniciar.addEventListener('click', () => {
        botaoEnviar.classList.remove('show');
        botaoContinuar.classList.remove('show');
        botaoReiniciar.classList.remove('show');
        blocoComando.classList.remove('show');
        blocoSucesso.classList.remove('show');
        blocoFinalizado.classList.remove('show');
        blocoHeader.classList.add('show');
        botaoAnalisar.classList.remove('ativo');
        blocoTotal.classList.remove('show');

        totalNovo = 0;
        totalAtualizado = 0;
        totalSucesso = 0;
        totalErro = 0;
        blocoTotalNovo.innerText = totalNovo;
        blocoTotalAtualizado.innerText = totalAtualizado;
        blocoTotalSucesso.innerText = totalSucesso;
        blocoTotalErro.innerText = totalErro;

        abortar = false;
    });

    botaoEnviar.addEventListener('click', () => {
        acaoAoClicarBotaoEnviar();
    });
    botaoContinuar.addEventListener('click', () => {
        acaoAoClicarBotaoEnviar();
    });
    const acaoAoClicarBotaoEnviar = () => {
        botaoEnviar.classList.remove('show');
        botaoContinuar.classList.remove('show');
        botaoReiniciar.classList.remove('show');
        botaoCancelar.classList.add('show');
        enviarUsuarioParaSalvar();
    };

    let abortar = false;
    const enviarUsuarioParaSalvar = async () => {
        if (abortar) {
            return;
        }

        const usuario = blocoSucesso.querySelectorAll('.linha.dado');
        const quantidadeUsuario = usuario.length;
        if (quantidadeUsuario == 0) {
            return;
        }

        const body = new FormData();
        const total = quantidadeUsuario > 15 ? 15 : quantidadeUsuario;
        let i, item, hash;
        for (i = 0; i < total; ++i) {
            item = usuario[i];
            item.classList.add('loading');
            hash = item.getAttribute('data-id');
            body.append('hash[]', hash);
        }

        const resposta = await fetch(LINK + '/tabela/' + acao, {
            body,
            method: 'POST',
        });

        let json;
        try {
            json = await resposta.json();
        } catch (erro) {
            return erroNoEnvio();
        }

        tratarResposta(json.dado.retorno);
    };
    const erroNoEnvio = () => {
        const lista = blocoSucesso.querySelectorAll('.linha.loading');
        lista.forEach(item => {
            item.classList.add('erro');
            item.classList.remove('dado');
            item.classList.remove('loading');
        });

        botaoReiniciar.classList.add('show');
        botaoCancelar.classList.remove('show');
    };

    const blocoTotalNovo = document.querySelector('#total_novo');
    const blocoTotalAtualizado = document.querySelector('#total_atualizado');
    const blocoTotalSucesso = document.querySelector('#total_sucesso');
    const blocoTotalErro = document.querySelector('#total_erro');

    let totalNovo = 0;
    let totalAtualizado = 0;
    let totalSucesso = 0;
    let totalErro = 0;

    const tratarResposta = lista => {
        const quantidade = lista.length;
        if (quantidade == 0) {
            return;
        }
        const loading = blocoSucesso.querySelectorAll('.linha.dado.loading');
        let i;
        for (i = 0; i < quantidade; ++i) {
            if (lista[i][0] == true) {
                totalSucesso++;
                if (lista[i][1] == 204) {
                    totalAtualizado++;
                } else if (lista[i][1] == 201) {
                    totalNovo++;
                }
                loading[i].parentNode.removeChild(loading[i]);
            } else if (lista[i][0] == false) {
                totalErro++;
                loading[i].classList.add('erro');
                loading[i].classList.remove('dado');
                loading[i].classList.remove('loading');
                loading[i].querySelector('.mensagem').insertAdjacentHTML('beforeend', ' - ' + lista[i][1]);
            }
        }

        blocoTotalNovo.innerText = totalNovo;
        blocoTotalAtualizado.innerText = totalAtualizado;
        blocoTotalSucesso.innerText = totalSucesso;
        blocoTotalErro.innerText = totalErro;

        const quantidadeErro = blocoSucesso.querySelectorAll('.linha.erro').length;
        const quantidadeDado = blocoSucesso.querySelectorAll('.linha.dado').length;
        if (quantidadeDado == 0 && quantidadeErro > 0) {
            botaoReiniciar.classList.add('show');
            botaoCancelar.classList.remove('show');
            return;
        } else if (quantidadeDado == 0) {
            blocoSucesso.innerHTML = '';
            blocoSucesso.classList.remove('show');
            botaoCancelar.classList.remove('show');
            botaoReiniciar.classList.add('show');
            blocoFinalizado.classList.add('show');
            return;
        }
        enviarUsuarioParaSalvar();
    };

    botaoCancelar.addEventListener('click', () => {
        abortar = true;
        botaoCancelar.classList.remove('show');
        Alerta.mensagem(
            'Envio cancelado',
            'O envio dos dados foi cancelado, aguarde o envio do usuário atual para escolher o que deseja fazer.',
            true
        );
    });
});
