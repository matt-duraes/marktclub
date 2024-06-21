const historicoLoad = () => {
    const inputApp = $('#input_historico_app');
    if (!inputApp) {
        return;
    }
    const app = inputApp.valor();
    const relacionado = document.querySelector('#input_historico_relacionado').value;

    const inputHistorico = document.querySelector('#input_historico_mensagem textarea');
    const inputPesquisa = document.querySelector('#input_historico_pesquisa');
    const inputDataDe = document.querySelector('#input_historico_data_de');
    const inputDataAte = document.querySelector('#input_historico_data_ate');

    const usuarioNome = $('#USUARIO_NOME') ? $('#USUARIO_NOME').value : '';
    const usuarioImagem = $('#USUARIO_IMAGEM') ? $('#USUARIO_IMAGEM').value : '';

    const historicoTitulo = $('#input_historico_titulo') ? $('#input_historico_titulo').value : '';
    const historicoLink = $('#input_historico_link') ? $('#input_historico_link').value : '';
    const historicoNotificar = $('#input_historico_notificar') ? $('#input_historico_notificar').value : '';
    const historicoLista = document.querySelector('#bloco_historico_lista');

    const botaoBuscar = document.querySelector('#botao_buscar_historico');
    const botaoCarregarMais = document.querySelector('#botao_historico_carregar_mais');

    const botaoDownload = document.querySelector('#botao_download_historico');
    const botaoEnviarDownload = document.querySelector('#botao_enviar_download');
    const downloadDataDe = document.querySelector('#input_data_de');
    const downloadDataAte = document.querySelector('#input_data_ate');

    const botaoUpload = $('#botao_historico_upload');
    const previaPadrao = $('#bloco_previa_item_padrao');
    const blocoPreviaLista = $('#bloco_previa_lista');
    const arquivoSalvar = {};

    if (inputDataDe) {
        Calendario.init({
            de: 'input_historico_data_de',
            ate: 'input_historico_data_ate',
        });
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR MENSAGEM
    |--------------------------------------------------------------------------
    */
    if (inputPesquisa) {
        inputPesquisa.addEventListener('keyup', e => {
            if (e.key == 'Enter') {
                buscarMensagem(1);
            }
        });
    }
    if (inputDataDe) {
        inputDataDe.addEventListener('keyup', e => {
            if (e.key == 'Enter') {
                buscarMensagem(1);
            }
        });
    }
    if (inputDataAte) {
        inputDataAte.addEventListener('keyup', e => {
            if (e.key == 'Enter') {
                buscarMensagem(1);
            }
        });
    }
    if (botaoBuscar) {
        botaoBuscar.addEventListener('click', () => {
            buscarMensagem(1);
        });
    }

    let paginaAtual;
    let existeBusca;
    const buscarMensagem = async pagina => {
        existeBusca = false;
        if (pagina == 1) {
            historicoLista.innerHTML = '<div class="zero">Carregando mensagens</div>';
        }
        paginaAtual = pagina;

        let query = `?pagina=${pagina}&app=${app}&relacionado=${relacionado}`;
        if (inputDataDe.value != '') {
            existeBusca = true;
            query += '&data_de=' + inputDataDe.value;
        }
        if (inputDataAte.value != '') {
            existeBusca = true;
            query += '&data_ate=' + inputDataAte.value;
        }
        if (inputPesquisa.value != '') {
            existeBusca = true;
            query += '&pesquisa=' + inputPesquisa.value;
        }

        const resposta = await fetch(LINK + '/historico' + query, {
            method: 'GET',
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        if (resposta.status == 200 && json.dado.lista != undefined) {
            carregarListaMensagem(pagina, json.dado.lista);
            if (json.dado.pagina > pagina) {
                botaoCarregarMais.classList.add('display_flex');
            }
            return;
        }
    };
    if (inputPesquisa) {
        buscarMensagem(1);
    }

    const carregarListaMensagem = (pagina, lista) => {
        if (pagina == 1 && Object.keys(lista).length == 0 && existeBusca) {
            historicoLista.innerHTML = '<div class="zero">Sem mensagens para a busca realizada</div>';
            return;
        } else if (pagina == 1 && Object.keys(lista).length == 0) {
            adicionarBlocoSemMensagem(historicoLista);
            return;
        } else if (pagina == 1) {
            historicoLista.innerHTML = '';
        }

        let classe;
        lista.forEach(item => {
            if (item.tipo == 'hoje' && !historicoLista.querySelector(`.bloco_historico_data_${item.hash}`)) {
                historicoLista.insertAdjacentHTML(
                    'beforeend',
                    `<div id="bloco_historico_hoje" class="item_geral bloco_historico_data bloco_historico_data_${item.hash}">Hoje</div>`
                );
                adicionarTextoAjuda(historicoLista.querySelector(`.bloco_historico_data_${item.hash}`), item.data);
            } else if (item.tipo == 'data' && !historicoLista.querySelector(`.bloco_historico_data_${item.hash}`)) {
                historicoLista.insertAdjacentHTML(
                    'beforeend',
                    `<div class="item_geral bloco_historico_data bloco_historico_data_${item.hash}">${item.social}</div>`
                );
                adicionarTextoAjuda(historicoLista.querySelector(`.bloco_historico_data_${item.hash}`), item.data);
            } else if (item.tipo == 'mensagem') {
                classe = item.minha_mensagem ? 'minha_mensagem' : 'outra_mensagem';
                let arquivoHtml = '';
                let arquivoQuantidade = item.arquivo.length;
                let arquivoI = 1;
                for (const linkArquivo of item.arquivo) {
                    arquivoHtml += `<figure class="imagem_total_${arquivoQuantidade} imagem_${arquivoI}" style="background-image: url(${linkArquivo})"></figure>`;
                    arquivoI++;
                }
                if (arquivoQuantidade > 0) {
                    arquivoHtml += '<div class="linha"></div>';
                }
                historicoLista.insertAdjacentHTML(
                    'beforeend',
                    `
                    <div class="item item_geral ${classe}">
                        <figure class="perfil" style="background-image: url(${item.imagem});"></figure>
                        <div class="dado">
                            <h2>${item.nome}</h2>
                            <div class="data">${item.hora}</div>
                        </div>
                        <div class="fixar"><svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.828 1.686l8.486 8.486-1.415 1.414-.707-.707-4.242 4.242-.707 3.536-1.415 1.414-4.242-4.243-4.95 4.95-1.414-1.414 4.95-4.95-4.243-4.242 1.414-1.415L8.88 8.05l4.242-4.242-.707-.707 1.414-1.415zm.708 3.536l-4.671 4.67-2.822.565 6.5 6.5.564-2.822 4.671-4.67-4.242-4.243z"/></svg></div>
                        <div class="mensagem">${arquivoHtml}<p>${item.mensagem}</p></div>
                    </div>
                    `
                );
            }
        });
    };

    /*
    |--------------------------------------------------------------------------
    | CARREGAR MAIS
    |--------------------------------------------------------------------------
    */
    if (botaoCarregarMais) {
        botaoCarregarMais.addEventListener('click', () => {
            botaoCarregarMais.classList.remove('display_flex');
            buscarMensagem(paginaAtual + 1);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR HISTÓRICO
    |--------------------------------------------------------------------------
    */
    const listaAppSalvar = $$('.input_app_salvar_visivel');
    const salvarNovoHistorico = async () => {
        const mensagem = inputHistorico.value.trim();
        if (mensagem == '') {
            Alerta.notificacao('Você deve enviar uma mensagem para salvar o histórico.', false);
            return;
        } else if (
            listaAppSalvar.length > 0 &&
            !(await Alerta.confirmar(
                'Salvar histórico',
                'Tem certeza que marcous todos os locais que o comentário deve aparecer?',
                '!'
            ))
        ) {
            return;
        }

        Loading.show();

        const body = new FormData();
        body.append('app', JSON.stringify($$('.input_app_salvar:checked').valor()));
        body.append('relacionado', relacionado);
        body.append('mensagem', mensagem);
        body.append('titulo', historicoTitulo);
        body.append('link', historicoLink);
        body.append('notificar', historicoNotificar);
        const indiceArquivo = Object.keys(arquivoSalvar);
        let i = 1;
        for (const item of indiceArquivo) {
            body.append('arquivo_' + i, arquivoSalvar[item]);
            i++;
        }

        const resposta = await fetch(LINK + '/historico', {
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
        if (resposta.status != 201) {
            Alerta.notificacao(
                json.erro != undefined && json.erro.mensagem != undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao enviar seu histórico.',
                false
            );
            return;
        }

        listaAppSalvar.marcar(false);
        inputHistorico.value = '';
        inputHistorico.style.height = 25 + 'px';

        Alerta.notificacao('Histórico salvo com sucesso.', true);

        if (historicoLista) {
            adicionarNovaMensagem(
                json.dado.id,
                json.dado.mensagem,
                json.dado.arquivo,
                usuarioImagem,
                usuarioNome,
                true,
                historicoLista
            );
        }

        blocoPreviaLista.html('');
        blocoPreviaLista.sumir();
        for (const item of indiceArquivo) {
            if (item in arquivoSalvar) {
                delete arquivoSalvar[item];
            }
        }
    };

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    const adicionarTextoAjuda = (bloco, texto) => {
        bloco.addEventListener('mouseover', () => {
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    };

    /*
    |--------------------------------------------------------------------------
    | MARCAR EQUIPE
    |--------------------------------------------------------------------------
    */
    const blocoMarcar = document.querySelector('#bloco_historico_marcacao_equipe');

    const marcacaoEquipe = [];
    const listaUsuarioParaMarcar = blocoMarcar ? blocoMarcar.querySelectorAll('li') : null;
    if (listaUsuarioParaMarcar) {
        listaUsuarioParaMarcar.forEach(usuario => {
            marcacaoEquipe.push(usuario.getAttribute('data-usuario'));
            usuario.addEventListener('click', () => {
                executarMarcacaoEquipe(usuario);
            });
            usuario.addEventListener('mouseover', () => {
                colocarHoverUsuario(usuario);
            });
        });
    }
    const colocarHoverUsuario = usuario => {
        const hover = blocoMarcar.querySelector('li.hover');
        if (hover) {
            hover.classList.remove('hover');
        }
        usuario.classList.add('hover');
    };

    let marcacaoAtiva = false;
    let marcacaoNome = '';
    let marcacaoPosicaoInicial;
    let marcacaoPosicaoFinal;
    if (inputHistorico) {
        inputHistorico.addEventListener('focus', () => {
            fecharBlocoMarcar();
        });
        inputHistorico.addEventListener('blur', () => {
            setTimeout(() => {
                fecharBlocoMarcar();
            }, 200);
        });
        inputHistorico.addEventListener('click', () => {
            fecharBlocoMarcar();
        });
        inputHistorico.addEventListener('keydown', e => {
            const tecla = e.key;
            if (marcacaoAtiva && !e.shiftKey && e.key == 'Enter' && blocoMarcar.classList.contains('ativo')) {
                e.preventDefault();
                executarMarcacaoEquipe(blocoMarcar.querySelector('li.hover'));
                fecharBlocoMarcar();
            } else if (!e.shiftKey && e.key == 'Enter') {
                e.preventDefault();
                salvarNovoHistorico();
            } else if (tecla == '@') {
                marcacaoAtiva = true;
                marcacaoNome = '';
                blocoMarcar.classList.remove('ativo');
            } else if (!marcacaoAtiva) {
                return;
            } else if (tecla == 'ArrowDown' && blocoMarcar.classList.contains('ativo')) {
                e.preventDefault();
                selecionarProximoUsuario();
            } else if (tecla == 'ArrowUp' && blocoMarcar.classList.contains('ativo')) {
                e.preventDefault();
                selecionarUsuarioAnterior();
            } else if (
                (tecla == 'Backspace' && marcacaoNome.length == 0) ||
                (e.shiftKey && tecla == 'Enter') ||
                tecla == ' ' ||
                tecla == 'ArrowLeft' ||
                tecla == 'ArrowRight' ||
                tecla == 'ArrowDown' ||
                tecla == 'ArrowUp'
            ) {
                fecharBlocoMarcar();
            } else if (tecla == 'Enter') {
                e.preventDefault();
            } else if (/^[a-z0-9\.]{1}$/.test(tecla)) {
                marcacaoNome += tecla;
                buscarListaUsuario();
            } else if (tecla == 'Backspace') {
                marcacaoNome = marcacaoNome.slice(0, -1);
                buscarListaUsuario();
            }
        });
    }

    const buscarListaUsuario = () => {
        const nomeExistente = [];
        const valor = inputHistorico.value;
        marcacaoEquipe.find(nome => {
            const regNome = new RegExp('@' + nome);
            if (regNome.test(valor)) {
                return;
            }

            const nomeComperacao = converterNome(marcacaoNome);
            const item = blocoMarcar.querySelector('li[data-usuario="' + nome + '"]');

            item.classList.remove('hover');
            item.classList.remove('ativo');
            if (nomeComperacao != '' && nome.startsWith(nomeComperacao)) {
                nomeExistente.push(nome);
                item.classList.add('ativo');
            }
        });
        if (nomeExistente.length > 0) {
            blocoMarcar.querySelector('li.ativo').classList.add('hover');
            blocoMarcar.classList.add('ativo');
            posicionarBlocoMarcar();
        } else {
            blocoMarcar.classList.remove('ativo');
        }
        marcacaoPosicaoInicial = inputHistorico.selectionStart;
        marcacaoPosicaoFinal = inputHistorico.selectionEnd;
    };
    const posicionarBlocoMarcar = () => {
        const posicaoHistorico = inputHistorico.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        if (posicaoHistorico.top + posicaoHistorico.height > windowHeight / 2) {
            blocoMarcar.classList.remove('top');
            blocoMarcar.classList.add('bottom');
        } else {
            blocoMarcar.classList.add('top');
            blocoMarcar.classList.remove('bottom');
        }
    };
    const converterNome = nome => {
        return nome
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase();
    };

    const selecionarProximoUsuario = () => {
        processarAlteracaoUsuario('mais');
    };
    const selecionarUsuarioAnterior = () => {
        processarAlteracaoUsuario('menos');
    };
    const processarAlteracaoUsuario = acao => {
        const lista = blocoMarcar.querySelectorAll('li.ativo');
        const quantidade = lista.length - 1;
        const jaExisteAtivo = blocoMarcar.querySelector('li.hover');
        if (!jaExisteAtivo) {
            const adicionarPrimeiro = acao == 'mais' ? 0 : quantidade;
            lista[adicionarPrimeiro].classList.add('hover');
            return;
        }

        let i = acao == 'mais' ? 0 : quantidade;
        let proximo;
        for (;;) {
            if (!lista[i].classList.contains('hover')) {
                i = acao == 'mais' ? i + 1 : i - 1;
                continue;
            }

            lista[i].classList.remove('hover');
            if (acao == 'mais' && i == quantidade) {
                proximo = lista[0];
            } else if (acao == 'mais') {
                proximo = lista[i + 1];
            } else if (acao != 'mais' && i - 1 < 0) {
                proximo = lista[quantidade];
            } else if (acao != 'mais') {
                proximo = lista[i - 1];
            }
            proximo.classList.add('hover');
            break;
        }
    };

    const fecharBlocoMarcar = () => {
        marcacaoNome = '';
        marcacaoAtiva = false;
        blocoMarcar.classList.remove('ativo');

        const lista = blocoMarcar.querySelectorAll('li');
        lista.forEach(item => {
            item.classList.remove('ativo');
            item.classList.remove('hover');
        });
    };

    const executarMarcacaoEquipe = item => {
        if (!item) {
            item = blocoMarcar.querySelector('li.ativo');
        }
        const nomeParcial = marcacaoNome;
        const usuario = item.getAttribute('data-usuario');
        const nomeFinal = usuario.slice(nomeParcial.length, usuario.length);

        const valorTemporario = inputHistorico.value;
        const valorTemporarioTamanho = valorTemporario.length;

        const valor =
            valorTemporario.substr(0, marcacaoPosicaoInicial + 1) +
            nomeFinal +
            ' ' +
            valorTemporario.substr(marcacaoPosicaoInicial + 1, valorTemporarioTamanho);

        const posicaoFinal = marcacaoPosicaoFinal + nomeFinal.length + 2;

        inputHistorico.value = valor;
        inputHistorico.focus();
        inputHistorico.setSelectionRange(posicaoFinal, posicaoFinal);
    };

    /*
    |--------------------------------------------------------------------------
    | ADICIONAR MENSAGEM
    |--------------------------------------------------------------------------
    */
    let contadorNovaMensagem = 0;
    const adicionarNovaMensagem = (id, mensagem, arquivo, usuarioImagem, usuarioNome, podeDeletar) => {
        const blocoSemMensagem = historicoLista.querySelector('.sem_mensagem');
        if (blocoSemMensagem) {
            blocoSemMensagem.parentNode.removeChild(blocoSemMensagem);
        }

        contadorNovaMensagem++;
        const idMensagem = 'bloco_nova_mensagem_' + contadorNovaMensagem;
        mensagem = mensagem
            .replace(
                /((https?:\/\/)[a-zA-Z\.\:0-9\/\-\_\?\=\&]{1,})/gm,
                `<a href="${LINK}/app/redirecionar?url=$1" target="_blank" rel="noopener noreferrer">$1</a>`
            )
            .replace(/\n/g, '<br>');
        let htmlDeletar = '';
        if (podeDeletar) {
            htmlDeletar = `
            <div class="deletar">
                <div class="loading"></div>
                ${Icone.deletar()}
            </div>
        `;
        }
        let arquivoHtml = '';
        let arquivoQuantidade = arquivo.length;
        let arquivoI = 1;
        for (const item of arquivo) {
            arquivoHtml += `<figure class="imagem_total_${arquivoQuantidade} imagem_${arquivoI}" style="background-image: url(${item})"></figure>`;
            arquivoI++;
        }
        if (arquivoQuantidade > 0) {
            arquivoHtml += '<div class="linha"></div>';
        }
        const html = `
            <div class="item item_geral minha_mensagem" id="${idMensagem}">
                <figure class="perfil" style="background-image: url(${usuarioImagem});"></figure>
                <div class="dado">
                    <h2>${usuarioNome}</h2>
                    <div class="data">Agora</div>
                </div>
                <div class="fixar"><svg height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.828 1.686l8.486 8.486-1.415 1.414-.707-.707-4.242 4.242-.707 3.536-1.415 1.414-4.242-4.243-4.95 4.95-1.414-1.414 4.95-4.95-4.243-4.242 1.414-1.415L8.88 8.05l4.242-4.242-.707-.707 1.414-1.415zm.708 3.536l-4.671 4.67-2.822.565 6.5 6.5.564-2.822 4.671-4.67-4.242-4.243z"/></svg></div>
                ${htmlDeletar}
                <div class="mensagem">${arquivoHtml}<p>${mensagem}</p></div>
            </div>
        `;

        let blocoHoje = historicoLista.querySelector('#bloco_historico_hoje');
        if (!blocoHoje) {
            historicoLista.insertAdjacentHTML(
                'afterbegin',
                '<div class="item_geral bloco_historico_data" id="bloco_historico_hoje">Hoje</div>'
            );
            blocoHoje = historicoLista.querySelector('#bloco_historico_hoje');
        }
        blocoHoje.insertAdjacentHTML('afterend', html);

        const blocoMensagem = historicoLista.querySelector('#' + idMensagem);
        const botaoDeletar = blocoMensagem.querySelector('.deletar');
        if (botaoDeletar) {
            botaoDeletar.addEventListener('click', () => {
                deletarHistoricoEnviado(blocoMensagem, id, historicoLista);
            });
            setTimeout(() => {
                botaoDeletar.parentNode.removeChild(botaoDeletar);
            }, 20000);
        }
    };

    /*
    |--------------------------------------------------------------------------
    | DELETAR MENSAGEM
    |--------------------------------------------------------------------------
    */
    const deletarHistoricoEnviado = async (bloco, id) => {
        bloco.classList.add('display_none');
        const resposta = await fetch(LINK + '/historico/' + id, {
            method: 'DELETE',
        });
        if (resposta.status != 204) {
            Alerta.notificacao('Ocorreu um erro ao deletar a mensagem.', false);
            bloco.classList.remove('display_none');
            return;
        }

        bloco.parentNode.removeChild(bloco);
        if (historicoLista.querySelectorAll('.item').length == 0) {
            adicionarBlocoSemMensagem(historicoLista);
            return;
        }
        const itemGeral = historicoLista.querySelectorAll('.item_geral');
        if (
            itemGeral[0].classList.contains('bloco_historico_data') &&
            itemGeral[1].classList.contains('bloco_historico_data')
        ) {
            itemGeral[0].parentNode.removeChild(itemGeral[0]);
        }
    };
    const adicionarBlocoSemMensagem = () => {
        historicoLista.innerHTML = '<div class="zero sem_mensagem">Sem mensagens no momento</div>';
    };

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD HISTORICO
    |--------------------------------------------------------------------------
    */
    if (botaoDownload) {
        const PopupDownload = new Popup('Popup Download', 'bloco_download', true, true);

        botaoDownload.addEventListener('click', () => {
            PopupDownload.abrir();

            downloadDataDe.value = inputDataDe.value;
            downloadDataAte.value = inputDataAte.value;
        });

        botaoEnviarDownload.addEventListener('click', () => {
            if (!downloadDataDe.value || !downloadDataDe.value) {
                Alerta.notificacao(
                    'Você deve preencher as datas de início e fim para fazer o download do histórico.',
                    false
                );
                return;
            }
            enviarDownload();
        });

        async function enviarDownload() {
            Loading.show();
            const resposta = await ajaxPost(
                LINK + '/historico/download',
                {
                    /* eslint-disable */
                    data_de: downloadDataDe.value,
                    data_ate: downloadDataAte.value,
                    /* eslint-enable */
                    app: app,
                    relacionado: relacionado,
                    pesquisa: inputPesquisa.value,
                },
                'Ocorreu um erro ao iniciar o download do histórico'
            );
            Loading.hide();

            if (resposta.status == 'sucesso') {
                PopupDownload.fechar();
                Alerta.notificacao('Download do histórico iniciado', true);
                return;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD DE IMAGEM
    |--------------------------------------------------------------------------
    */
    if (botaoUpload) {
        botaoUpload.evento('change', () => {
            const quantidade = botaoUpload.files.length;
            if (quantidade == 0) {
                botaoUpload.value = '';
                return;
            }

            blocoPreviaLista.aparecer();
            let i = 0;
            for (; i < quantidade; ++i) {
                if (Object.keys(arquivoSalvar).length >= 4) {
                    Alerta.notificacao('Você só pode subir 4 imagens por comentário.', false);
                    return;
                }
                adicionarArquivoPrevio(botaoUpload.files[i]);
            }
            inputHistorico.focus();
            botaoUpload.value = '';
        });
        blocoPreviaLista.evento('click', e => {
            if (!e.target.classe('arquivo_pervia_remover', '?') && !e.target.closest('.arquivo_pervia_remover')) {
                return;
            }
            const bloco = e.target.closest('.arquivo_previa_item');
            const indice = bloco.attr('data-id');
            if (indice in arquivoSalvar) {
                delete arquivoSalvar[indice];
            }
            bloco.remove();
            console.log(arquivoSalvar);
        });
    }
    const adicionarArquivoPrevio = arquivo => {
        const indice = `${arquivo.name}-${arquivo.lastModified}`;
        if (indice in arquivoSalvar) {
            return;
        }
        const clone = previaPadrao.clonar();
        $('.arquivo_previa_titulo', clone).texto(arquivo.name);
        clone.attr('data-id', indice);
        arquivoSalvar[indice] = arquivo;
        blocoPreviaLista.final(clone);
    };
};
