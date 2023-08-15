class ArquivoUpload {
    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */
    /**
     *
     * @param {string} grupo ID do grupo raiz que deseja usar
     * @param {object} body Objeto com indice e valor para passar na requisição
     * @param {bool} multiplo Se poderá escolher multiplos arquivos para usar
     */
    constructor(grupo, body, multiplo) {
        this._constructor(grupo, body, multiplo);
    }

    /*
    |--------------------------------------------------------------------------
    | BLOCO EDITAR
    |--------------------------------------------------------------------------
    */
    _abrirBlocoEditarGeral(acao, titulo, texto) {
        this._editarAcao = acao;
        this._hide(this._blocoHeaderMenu);
        this._show(this._blocoFormEditar);
        this._blocoFormEditarTitulo.innerText = titulo;
        this._blocoFormEditarTexto = texto;
        setTimeout(() => {
            this._blocoFormEditar.classList.add('fw_upload_form_editar_abrir');
        }, 20);
        setTimeout(() => {
            this._blocoFormEditarInput.focus();
        }, 300);
    }
    _fecharBlocoEditar() {
        this._blocoFormEditar.classList.remove('fw_upload_form_editar_abrir');
        setTimeout(() => {
            this._hide(this._blocoFormEditar);
            this._blocoFormEditarTitulo.innerText = '';
            this._blocoFormEditarTexto = '';
            this._blocoFormEditarInput.value = '';
            this._editarAcao = '';
        }, 300);
    }
    _escolherAcaoEditar() {
        if (this._editarAcao == 'renomar_arquivo') {
            this._editarNomeArquivo();
        } else if (this._editarAcao == 'renomar_diretorio') {
            this._editarNomeDiretorio();
        } else if (this._editarAcao == 'criar_diretorio') {
            this._criarDiretorio();
        }
    }
    /*
    |--------------------------------------------------------------------------
    | DIRETÓRIO
    |--------------------------------------------------------------------------
    */
    async _criarDiretorio() {
        const nome = this._blocoFormEditarInput.value.trim();

        if (nome == '') {
            Alerta.notificacao('Digite o nome para o novo diretório.', false);
            return;
        }

        this._loadingShow();

        const body = new FormData();
        body.append('nome', nome);
        body.append('grupo_inicial', this._grupoInicial);
        body.append('grupo_atual', this._grupoAtual);

        const resposta = await fetch(LINK + '/upload/criar-diretorio', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro ao criar o diretório.');
        this._loadingHide();

        if (false === json) {
            return;
        }
        await this._construtorPegarEstruturaDiretorio();
        this._adicionarEstruturaMover();
        this._fecharBlocoEditar();

        await this._adicionarNovoDiretorio(json.dado.id, json.dado.nome);
        const listaDiretorio = document.querySelectorAll('.fw_upload_arquivo_diretorio_pasta_novo');
        this._adicionarEventoAoDiretorio(listaDiretorio);
        this._mostrarBlocoBotao();
    }
    async _editarNomeDiretorio() {
        const nome = this._blocoFormEditarInput.value.trim();

        if (nome == '') {
            Alerta.notificacao('Digite um novo nome para o arquivo.', false);
            return;
        }

        this._loadingShow();

        const body = new FormData();
        body.append('nome', nome);
        body.append('grupo_inicial', this._grupoInicial);
        body.append('grupo_atual', this._grupoAtual);

        const resposta = await fetch(LINK + '/upload/renomear-diretorio', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro ao renomear o diretório.');
        this._loadingHide();

        if (false === json) {
            return;
        }

        await this._construtorPegarEstruturaDiretorio();
        this._adicionarEstruturaMover();
        this._fecharBlocoEditar();
        const pasta = this._blocoConfigDiretorio.querySelector('.fw_upload_config_diretorio_nome');
        if (pasta) {
            pasta.innerText = nome;
        }
        this._mostrarBlocoBotao();
    }

    /*
    |--------------------------------------------------------------------------
    | ARQUIVO
    |--------------------------------------------------------------------------
    */
    async _editarNomeArquivo() {
        const itemMarcado = this._blocoListaArquivo.querySelector('.fw_upload_arquivo_checked');
        if (!itemMarcado) {
            return;
        }
        const id = itemMarcado.getAttribute('data-id');
        const nome = this._blocoFormEditarInput.value.trim();

        if (nome == '') {
            Alerta.notificacao('Digite um novo nome para o arquivo.', false);
            return;
        }

        this._loadingShow();

        const body = new FormData();
        body.append('id', id);
        body.append('nome', nome);
        body.append('grupo_atual', this._grupoAtual);
        body.append('grupo_inicial', this._grupoInicial);

        const resposta = await fetch(LINK + '/upload/renomear', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorre um erro ao renomear o arquivo.');
        this._loadingHide();

        if (false === json) {
            return;
        }

        this._fecharBlocoEditar();
        const bloco = itemMarcado.querySelector('.fw_upload_nome');
        if (bloco) {
            bloco.innerText = nome;
        }
        const nomeListaArquivo = document.querySelector('.fw_arquivo_nome_' + id);
        if (nomeListaArquivo) {
            nomeListaArquivo.innerText = nome;
        }
        this._mostrarBlocoBotao();
    }
    async _deletarArquivo() {
        const itemMarcado = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');
        if (itemMarcado.length == 0) {
            return;
        }

        this._loadingShow();

        const body = new FormData();
        itemMarcado.forEach(item => {
            body.append('id[]', item.getAttribute('data-id'));
        });
        body.append('grupo_inicial', this._grupoInicial);
        body.append('grupo_atual', this._grupoAtual);

        const resposta = await fetch(LINK + '/upload/deletar', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorre um erro ao deletar os arquivos.');
        this._loadingHide();
        if (false === json) {
            return;
        }

        itemMarcado.forEach(item => {
            const idRemovido = item.getAttribute('data-id');
            const arquivoLista = document.querySelector('.fw_arquivo_' + idRemovido);
            if (arquivoLista) {
                arquivoLista.parentNode.removeChild(arquivoLista);
            }
            const arquivoUnico = document.querySelectorAll('.fw_form_imagem input[value="' + idRemovido + '"]');
            if (arquivoUnico.length > 0) {
                this._removerArquivoUnico(arquivoUnico);
            }
            item.parentNode.removeChild(item);
        });

        const zero =
            this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_imagem').length == 0 &&
            this._blocoListaDiretorio.querySelectorAll('.fw_upload_arquivo_diretorio_pasta').length == 0;

        if (zero && this._inputPesquisa.value == '') {
            this._show(this._blocoZero);
        } else if (zero && this._inputPesquisa.value != '') {
            this._show(this._blocoZeroBusca);
        }

        this._mostrarZeroItensSeExistirLista();
        this._mostrarBlocoBotao();
    }
    _mostrarZeroItensSeExistirLista() {
        const lista = document.querySelectorAll('.fw_form_arquivo_lista_lista');
        if (lista.length == 0) {
            return;
        }
        lista.forEach(bloco => {
            const arquivo = bloco.querySelectorAll('.fw_form_arquivo_lista_arquivo');
            const blocoZero = bloco.querySelector('.fw_form_arquivo_lista_zero');
            if (arquivo.length == 0 && blocoZero && blocoZero.classList.contains('fw_arquivo_lista_hide')) {
                blocoZero.classList.remove('fw_arquivo_lista_hide');
            }
        });
    }
    _removerArquivoUnico(lista) {
        lista.forEach(input => {
            const figure = input.closest('.fw_form_imagem');
            const blocoIcone = figure.querySelector('.fw_imagem_conteudo .fw_imagem_icone');
            const blocoFigure = figure.querySelector('.fw_imagem_conteudo .fw_imagem_figure');
            const botaoVisualizar = figure.querySelector('.fw_imagem_visualizar');
            const botaoDeletar = figure.querySelector('.fw_imagem_remover');

            input.value = '';
            blocoIcone.classList.remove('fw_imagem_hide');
            botaoDeletar.classList.add('fw_imagem_hide');
            botaoVisualizar.classList.add('fw_imagem_hide');
            blocoFigure.style.backgroundImage = '';
            GaleriaFormImagem.remover(figure);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */
    botao() {
        return new Promise(async resolve => {
            let i = 0;
            for (; i < 100; i++) {
                if (this._botaoUsarArquivo) {
                    resolve(this._botaoUsarArquivo);
                    break;
                }
                await new Promise(r => setTimeout(r, 1000));
            }
        });
    }
    arquivo() {
        const bloco = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');
        if (bloco.length == 1 && true !== this._multiplo) {
            return bloco[0].getAttribute('data-arquivo') || '';
        } else if (bloco.length == 0 || (bloco.length > 1 && true !== this._multiplo)) {
            return '';
        }
        const lista = [];
        bloco.forEach(item => {
            lista.push(item.getAttribute('data-arquivo') || '');
        });
        return lista;
    }
    id() {
        const bloco = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');
        if (bloco.length == 1 && true !== this._multiplo) {
            return bloco[0].getAttribute('data-id') || '';
        } else if (bloco.length == 0 || (bloco.length > 1 && true !== this._multiplo)) {
            return '';
        }
        const lista = [];
        bloco.forEach(item => {
            lista.push(item.getAttribute('data-id') || '');
        });
        return lista;
    }
    nome() {
        const bloco = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');
        if (bloco.length == 1 && true !== this._multiplo) {
            return bloco[0].getAttribute('data-nome') || '';
        } else if (bloco.length == 0 || (bloco.length > 1 && true !== this._multiplo)) {
            return '';
        }
        const lista = [];
        bloco.forEach(item => {
            lista.push(item.getAttribute('data-nome') || '');
        });
        return lista;
    }
    extensao() {
        const bloco = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');
        if (bloco.length == 1 && true !== this._multiplo) {
            return bloco[0].getAttribute('data-extensao') || '';
        } else if (bloco.length == 0 || (bloco.length > 1 && true !== this._multiplo)) {
            return '';
        }
        const lista = [];
        bloco.forEach(item => {
            lista.push(item.getAttribute('data-extensao') || '');
        });
        return lista;
    }

    /*
    |--------------------------------------------------------------------------
    | ABRIR E FECHAR BLOCO
    |--------------------------------------------------------------------------
    */
    async abrir() {
        this._manipularClassesParaAbrir();
        this._inputPesquisa.value = '';
        this._pagina = 1;

        await this._construtorPegarEstruturaDiretorio();
        this._adicionarEstruturaMover();
        this._carregarArquivo();
    }
    fechar() {
        this._fecharUpload();
    }

    _manipularClassesParaAbrir() {
        document.querySelector('body').classList.add('fw_upload_body');
        this._show(this._bloco);
        setTimeout(() => {
            this._bloco.classList.add('fw_upload_abrir');
        }, 20);
    }
    _fecharUpload() {
        this._bloco.classList.remove('fw_upload_abrir');
        setTimeout(() => {
            document.querySelector('body').classList.remove('fw_upload_body');
            this._hide(this._bloco);
            this._blocoListaDiretorio.innerHTML = '';
            this._blocoConfigDiretorio.innerHTML = '';
            this._blocoListaArquivo.innerHTML = '';
        }, 300);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR ARQUIVOS
    |--------------------------------------------------------------------------
    */
    async _carregarArquivo() {
        this._fecharBlocoDado();
        this._loadingShow();
        this._hide(this._botaoCarregarMais);

        const pagina = this._pagina;
        const pesquisa = this._inputPesquisa.value;

        if (pagina == 1) {
            this._rezetarDadosParaBuscar();
        }

        const body = new FormData();
        body.append('pesquisa', pesquisa);
        body.append('pagina', pagina);
        body.append('grupo_inicial', this._grupoInicial);
        body.append('grupo_atual', this._grupoAtual);
        if (this._body) {
            this._body.forEach((val, ind) => {
                body.append(ind, val);
            });
        }
        const resposta = await fetch(LINK + '/upload/buscar', {
            body,
            method: 'POST',
        });

        let json = await respostaJson(resposta);
        this._loadingHide();

        if (json.dado.pagina > pagina) {
            this._show(this._botaoCarregarMais);
        }

        const diretorioExiste = resposta.status == 200 && json.dado.diretorio && json.dado.diretorio.length > 0;
        if (diretorioExiste && pagina == 1) {
            this._adicionarDiretorio(json.dado.diretorio);
        }

        const headerExiste = resposta.status == 200 && json.dado.header && json.dado.header.length > 0;
        if (headerExiste && pagina == 1) {
            this._adicionarHeader(json.dado.header);
        }

        const arquivoExiste = resposta.status == 200 && json.dado.arquivo && json.dado.arquivo.length > 0;
        if (!arquivoExiste && !diretorioExiste && pesquisa == '' && pagina == 1) {
            this._show(this._blocoZero);
        } else if (!arquivoExiste && pesquisa != '' && pagina == 1) {
            this._show(this._blocoZeroBusca);
        }

        if (arquivoExiste) {
            await this._adicionarArquivo(json.dado.arquivo);
        }
        this._mostrarBlocoBotao();
    }
    _rezetarDadosParaBuscar() {
        this._hide(this._blocoZero);
        this._hide(this._blocoZeroBusca);

        this._hide(this._botaoHeaderVisualizar);

        this._hide(this._botaoConfigVoltar);
        this._blocoConfigDiretorio.innerHTML = '';

        this._blocoListaDiretorio.innerHTML = '';
        this._hide(this._blocoListaDiretorio);

        this._blocoListaArquivo.innerHTML = '';
        this._hide(this._blocoListaArquivo);

        this._hide(this._blocoHeaderMenu);
        this._hide(this._botaoHeaderDeletar);
        this._hide(this._botaoHeaderMover);
        this._hide(this._botaoHeaderRenomear);
        if (this._grupoAtual != this._grupoInicial) {
            this._show(this._botaoHeaderDiretorioDeletar);
            this._show(this._botaoHeaderDiretorioRenomear);
        } else {
            this._hide(this._botaoHeaderDiretorioDeletar);
            this._hide(this._botaoHeaderDiretorioRenomear);
        }

        if (this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_imagem').length > 0) {
            this._show(this._blocoHeaderConfigLinha);
        } else {
            this._hide(this._blocoHeaderConfigLinha);
        }
    }

    _adicionarHeader(header) {
        let quantidade = header.length;
        if (quantidade == 0) {
            return;
        }
        this._show(this._blocoConfigDiretorio);
        if (quantidade > 1) {
            this._show(this._botaoConfigVoltar);
        } else {
            this._hide(this._botaoConfigVoltar);
        }
        let i, html;
        for (i = 0; i < quantidade; ++i) {
            if (i == quantidade - 1) {
                html = '<div class="fw_upload_config_diretorio_nome">' + header[i].nome + '</div>';
            } else {
                html = `
                    <div class="fw_upload_config_diretorio_botao fw_upload_config_diretorio_botao_novo" data-id="${header[i].id}">${header[i].nome}</div>
                    <div class="fw_upload_config_diretorio_seta">></div>
                `;
            }
            this._blocoConfigDiretorio.insertAdjacentHTML('beforeend', html);
        }
        const lista = document.querySelectorAll('.fw_upload_config_diretorio_botao_novo');
        this._adicionarEventoAoDiretorio(lista);
    }
    _adicionarArquivo(arquivo) {
        return new Promise(resolve => {
            this._show(this._blocoListaArquivo);
            let bloco;
            arquivo.forEach(async data => {
                bloco = await this._htmlNovoArquivo();
                this._adicinarEventoNovoArquivo(bloco, data);
            });
            resolve(true);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DIRETORIO
    |--------------------------------------------------------------------------
    */
    _adicionarDiretorio(diretorio) {
        diretorio.forEach(data => {
            this._adicionarNovoDiretorio(data.id, data.nome);
        });
        const lista = document.querySelectorAll('.fw_upload_arquivo_diretorio_pasta_novo');
        this._adicionarEventoAoDiretorio(lista);
    }
    _adicionarNovoDiretorio(id, nome) {
        this._show(this._blocoListaDiretorio);
        this._hide(this._blocoZero);
        return new Promise(resolve => {
            this._blocoListaDiretorio.insertAdjacentHTML(
                'beforeend',
                `
                <div class="fw_upload_arquivo_diretorio_pasta fw_upload_arquivo_diretorio_pasta_novo" data-id="${id}">
                    <svg height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 32" style="enable-background:new 0 0 40 32;" xml:space="preserve"><g><path d="M36.1,29.5c-0.3,0-0.6,0-0.9,0c-0.8,0-1.6,0-2.4,0c-1.2,0-2.3,0-3.5,0c-1.4,0-2.8,0-4.3,0c-1.6,0-3.1,0-4.7,0 c-1.6,0-3.1,0-4.7,0c-1.4,0-2.9,0-4.3,0c-1.2,0-2.4,0-3.7,0c-0.9,0-1.7,0-2.6,0c-0.4,0-0.8,0-1.2,0c-0.1,0-0.3,0-0.4,0 c0.1,0,0.2,0,0.3,0c-0.2,0-0.4-0.1-0.7-0.2c0.1,0,0.2,0.1,0.3,0.1c-0.2-0.1-0.3-0.1-0.4-0.2c0,0-0.1,0-0.1-0.1 c-0.2-0.1,0.2,0.2,0.1,0.1c-0.1-0.1-0.1-0.1-0.2-0.2c-0.1,0-0.1-0.1-0.1-0.2c-0.1-0.2,0.1,0.2,0.1,0.1c0,0,0-0.1-0.1-0.1 c-0.1-0.1-0.2-0.3-0.2-0.5c0,0.1,0.1,0.2,0.1,0.3c-0.1-0.2-0.1-0.4-0.2-0.6c0,0.1,0,0.2,0,0.3c0-0.3,0-0.6,0-0.9 c0-0.6,0-1.2,0-1.8c0-1.9,0-3.9,0-5.8c0-2.4,0-4.8,0-7.1c0-2,0-4.1,0-6.1c0-1,0-1.9,0-2.9c0-0.1,0-0.2,0-0.4c0,0.1,0,0.2,0,0.3 c0-0.2,0.1-0.4,0.2-0.6c0,0.1-0.1,0.2-0.1,0.3C2.6,3.3,2.7,3.2,2.8,3c0,0,0-0.1,0.1-0.1C3,2.8,2.7,3.2,2.8,3 C2.8,3,2.9,2.9,2.9,2.9c0.1,0,0.1-0.1,0.2-0.1C3.3,2.6,2.9,2.9,3,2.8c0,0,0.1,0,0.1-0.1c0.1-0.1,0.3-0.2,0.5-0.2 c-0.1,0-0.2,0.1-0.3,0.1c0.2-0.1,0.4-0.1,0.7-0.2c-0.1,0-0.2,0-0.3,0c0.4,0,0.9,0,1.3,0c0.9,0,1.7,0,2.6,0c2,0,4,0,5.9,0 c0.5,0,0.9,0,1.4,0c-0.3-0.1-0.6-0.2-0.9-0.4c1.2,1.2,2.5,2.3,3.7,3.5C17.8,5.8,18,6,18.2,6.2c0.3,0.3,0.6,0.4,1,0.4 c0.4,0,0.9,0,1.3,0c1.9,0,3.8,0,5.6,0c2.2,0,4.3,0,6.5,0c1.1,0,2.3,0,3.4,0c0.1,0,0.3,0,0.4,0c-0.1,0-0.2,0-0.3,0 c0.2,0,0.4,0.1,0.7,0.2c-0.1,0-0.2-0.1-0.3-0.1c0.2,0.1,0.3,0.1,0.4,0.2c0,0,0.1,0,0.1,0.1c0.2,0.1-0.2-0.2-0.1-0.1 C36.9,6.9,37,6.9,37.1,7c0.1,0,0.1,0.1,0.1,0.2c0.1,0.2-0.1-0.2-0.1-0.1c0,0,0,0.1,0.1,0.1c0.1,0.1,0.2,0.3,0.2,0.5 c0-0.1-0.1-0.2-0.1-0.3c0.1,0.2,0.1,0.4,0.2,0.6c0-0.1,0-0.2,0-0.3c0,0.3,0,0.5,0,0.8c0,0.5,0,1,0,1.5c0,1.6,0,3.2,0,4.9 c0,2,0,4,0,5.9c0,1.7,0,3.4,0,5.1c0,0.8,0,1.6,0,2.4c0,0.1,0,0.2,0,0.4c0-0.1,0-0.2,0-0.3c0,0.2-0.1,0.4-0.2,0.6 c0-0.1,0.1-0.2,0.1-0.3c-0.1,0.2-0.1,0.3-0.2,0.4c0,0,0,0.1-0.1,0.1c-0.1,0.2,0.2-0.2,0.1-0.1c-0.1,0.1-0.1,0.1-0.2,0.2 c-0.1,0-0.1,0.1-0.2,0.1c-0.2,0.1,0.2-0.1,0.1-0.1c0,0-0.1,0-0.1,0.1c-0.1,0.1-0.3,0.2-0.5,0.2c0.1,0,0.2-0.1,0.3-0.1 C36.5,29.5,36.3,29.5,36.1,29.5c0.1,0,0.2,0,0.3,0C36.3,29.5,36.2,29.5,36.1,29.5c-0.7,0-1.3,0.6-1.3,1.2c0,0.7,0.6,1.2,1.3,1.2 c1.9,0,3.6-1.4,3.9-3.3c0-0.3,0-0.6,0-1c0-1.2,0-2.4,0-3.6c0-1.9,0-3.8,0-5.6c0-1.9,0-3.9,0-5.8c0-1.3,0-2.6,0-3.9 c0-0.2,0-0.5,0-0.7c0-1.7-1.1-3.1-2.7-3.6c-0.5-0.2-1.1-0.2-1.6-0.2c-0.5,0-1.1,0-1.6,0c-1.8,0-3.6,0-5.4,0c-2,0-3.9,0-5.9,0 c-1.2,0-2.3,0-3.5,0c-0.1,0-0.1,0-0.2,0c0.3,0.1,0.6,0.2,0.9,0.4c-1-1-2-1.9-3-2.8c-0.4-0.4-0.9-0.8-1.3-1.2 c-0.3-0.3-0.6-0.4-1-0.4c-0.2,0-0.3,0-0.5,0C12.6,0,11,0,9.4,0C7.8,0,6.2,0,4.6,0c-0.5,0-1,0-1.5,0.1C1.4,0.4,0.1,2,0,3.7 c0,0.5,0,1.1,0,1.6c0,1.3,0,2.6,0,3.9c0,1.8,0,3.5,0,5.3c0,1.9,0,3.7,0,5.6c0,1.6,0,3.2,0,4.7c0,1,0,1.9,0,2.9c0,0.1,0,0.3,0,0.4 c0,1.7,1.2,3.3,2.9,3.7C3.6,32,4.3,32,5,32c1.1,0,2.1,0,3.2,0c1.6,0,3.1,0,4.7,0c1.8,0,3.7,0,5.5,0c1.9,0,3.8,0,5.7,0 c1.7,0,3.5,0,5.2,0c1.4,0,2.7,0,4.1,0c0.8,0,1.6,0,2.3,0c0.1,0,0.2,0,0.3,0c0.7,0,1.3-0.6,1.3-1.2C37.3,30.1,36.8,29.5,36.1,29.5z "/></g></svg>
                    <div class="fw_upload_arquivo_diretorio_p">${nome}</div>
                </div>
            `
            );
            resolve(true);
        });
    }
    _adicionarEventoAoDiretorio(lista) {
        lista.forEach(diretorio => {
            diretorio.classList.remove('fw_upload_arquivo_diretorio_pasta_novo');
            diretorio.addEventListener('click', () => {
                this._pagina = 1;
                this._inputPesquisa.value = '';
                this._grupoAtual = diretorio.getAttribute('data-id');
                this._carregarArquivo();
            });
        });
    }
    _voltarParaDiretorioAnterior() {
        const diretorioLista = this._bloco.querySelectorAll('.fw_upload_config_diretorio_botao');
        const diretorio = diretorioLista[diretorioLista.length - 1];
        if (!diretorio) {
            return;
        }
        this._pagina = 1;
        this._inputPesquisa.value = '';
        this._grupoAtual = diretorio.getAttribute('data-id');
        this._carregarArquivo();
    }
    async _deletarDiretorio() {
        const body = new FormData();
        body.append('grupo_inicial', this._grupoInicial);
        body.append('grupo_atual', this._grupoAtual);

        this._loadingShow();

        const resposta = await fetch(LINK + '/upload/deletar-diretorio', {
            body,
            method: 'POST',
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro ao deletar diretório.');
        this._loadingHide();

        if (false === json) {
            return;
        }

        await this._construtorPegarEstruturaDiretorio();
        this._adicionarEstruturaMover();
        this._voltarParaDiretorioAnterior();
        this._mostrarBlocoBotao();
    }

    /*
    |--------------------------------------------------------------------------
    | MOVER
    |--------------------------------------------------------------------------
    */
    _abrirBlocoMover() {
        this._show(this._blocoListaMover);
        const botaoAtual = this._blocoListaMoverLista.querySelector('#fw_upload_diretorio_mover_' + this._grupoAtual);
        if (botaoAtual) {
            botaoAtual.classList.add('fw_upload_diretorio_mover_atual');
        }

        setTimeout(() => {
            this._blocoListaMover.classList.add('fw_upload_diretorio_mover_abrir');
        }, 20);
    }
    _fecharBlocoMover() {
        this._blocoListaMover.classList.remove('fw_upload_diretorio_mover_abrir');
        setTimeout(() => {
            this._hide(this._blocoListaMover);
            const itemMarcado = this._blocoListaMoverLista.querySelector('.fw_upload_diretorio_mover_selecionado');
            if (itemMarcado) {
                itemMarcado.classList.remove('fw_upload_diretorio_mover_selecionado');
            }
            this._blocoListaMoverInput.value = '';
            this._botaoListaMoverCriar.innerText = 'Mover';
            const botaoAtual = this._blocoListaMoverLista.querySelector('.fw_upload_diretorio_mover_atual');
            if (botaoAtual) {
                botaoAtual.classList.remove('fw_upload_diretorio_mover_atual');
            }
        }, 300);
    }
    _adicionarEstruturaMover() {
        const diretorio = this._estruturaDiretorio;
        const html = this._adicionarEstruturaMoverHtml(diretorio);
        this._show(this._blocoListaMoverLista);
        this._blocoListaMoverLista.innerHTML = html;
        this._adicionarEventoMover();
    }
    _adicionarEstruturaMoverHtml(diretorio) {
        let quantidade = diretorio.length;
        if (quantidade == 0) {
            return '';
        }

        let i = 0;
        let html = '';
        for (; i < quantidade; ++i) {
            html += `
                <li>
                    <div
                        class="botao fw_upload_diretorio_mover_novo fw_upload_diretorio_mover_${diretorio[i].id}"
                        data-id="${diretorio[i].id}"
                    >
                        ${diretorio[i].nome}
                    </div>
                    ${this._adicionarEstruturaMoverHtml(diretorio[i].lista)}
                </li>
            `;
        }
        if (html != '') {
            return `<ul>${html}</ul>`;
        }
        return '';
    }
    _adicionarEventoMover() {
        const botaoLista = this._blocoListaMoverLista.querySelectorAll('.fw_upload_diretorio_mover_novo');
        botaoLista.forEach(botao => {
            botao.classList.remove('fw_upload_diretorio_mover_novo');
            botao.addEventListener('click', () => {
                this._marcarDesmarcarItemMover(botao);
            });
        });
    }
    _marcarDesmarcarItemMover(item) {
        if (item.classList.contains('fw_upload_diretorio_mover_atual')) {
            return;
        }
        if (item.classList.contains('fw_upload_diretorio_mover_selecionado')) {
            item.classList.remove('fw_upload_diretorio_mover_selecionado');
            return;
        }
        const itemMarcado = this._blocoListaMoverLista.querySelector('.fw_upload_diretorio_mover_selecionado');
        if (itemMarcado) {
            itemMarcado.classList.remove('fw_upload_diretorio_mover_selecionado');
        }
        item.classList.add('fw_upload_diretorio_mover_selecionado');
    }
    async _moverItemMarcado() {
        const itemMarcado = this._blocoListaMoverLista.querySelector('.fw_upload_diretorio_mover_selecionado');
        const grupo = itemMarcado ? itemMarcado.getAttribute('data-id') : '';
        const nome = this._blocoListaMoverInput.value.trim();
        const selecionado = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');

        if (grupo == '') {
            Alerta.notificacao('Você deve escolher um diretório para mover ou criar uma nova pasta.', false);
            return;
        } else if (grupo == this._grupoAtual && nome == '') {
            Alerta.notificacao(
                `
                    Você não pode mover os arquivos para o mesmo diretório que eles estão no momento,
                    escolha um novo diretório ou crie uma nova pasta no diretório escolhido.
                `,
                false
            );
            return;
        } else if (selecionado.length == 0) {
            Alerta.notificacao('Você deve selecionar pelo menos um arquivo para mover.', false);
            return;
        }

        this._loadingShow();

        const body = new FormData();
        body.append('grupo_destino', grupo);
        body.append('grupo_inicial', this._grupoInicial);
        body.append('grupo_atual', this._grupoAtual);
        body.append('nome', nome);
        selecionado.forEach(item => {
            body.append('id[]', item.getAttribute('data-id'));
        });

        const resposta = await fetch(LINK + '/upload/mover', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro ao mover arquivos.');
        this._loadingHide();

        if (false === json) {
            return;
        }

        if (grupo == this._grupoAtual && json.dado.id != undefined) {
            await this._adicionarNovoDiretorio(json.dado.id, json.dado.nome);
            const listaDiretorio = document.querySelectorAll('.fw_upload_arquivo_diretorio_pasta_novo');
            this._adicionarEventoAoDiretorio(listaDiretorio);
        }
        if (json.dado.id != undefined) {
            await this._construtorPegarEstruturaDiretorio();
            this._adicionarEstruturaMover();
        }

        this._removerArquivosSelecionado();
        this._fecharBlocoMover();
        this._mostrarBlocoBotao();
    }

    /*
    |--------------------------------------------------------------------------
    | FAZER UPLOAD
    |--------------------------------------------------------------------------
    */
    _fazerUpload() {
        const input = this._botaoHeaderUpload;
        this._hide(this._blocoZero);
        this._hide(this._blocoZeroBusca);

        const quantidade = input.files.length;
        if (quantidade == 0) {
            input.value = '';
            return;
        }
        this._show(this._blocoListaArquivo);
        let i = 0;
        for (; i < quantidade; ++i) {
            this._enviarArquivoParaSalvar(input.files[i]);
        }
    }
    async _enviarArquivoParaSalvar(arquivo) {
        const input = this._botaoHeaderUpload;
        const body = new FormData();
        body.append('arquivo', arquivo);
        body.append('grupo_atual', this._grupoAtual);
        body.append('grupo_inicial', this._grupoInicial);

        const bloco = await this._htmlNovoArquivo(true);
        const resposta = await fetch(LINK + '/upload/salvar', {
            method: 'POST',
            body,
        });
        input.value = '';

        const json = await respostaJson(resposta, 'Ocorre um erro ao fazer o upload do arquivo.');
        if (false === json) {
            bloco.parentNode.removeChild(bloco);

            const zero =
                this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_imagem').length == 0 &&
                this._blocoListaDiretorio.querySelectorAll('.fw_upload_arquivo_diretorio_pasta').length == 0;

            if (zero && this._inputPesquisa.value == '') {
                this._show(this._blocoZero);
            } else if (zero && this._inputPesquisa.value != '') {
                this._show(this._blocoZeroBusca);
            }
        }

        bloco.classList.remove('fw_upload_arquivo_loading');
        if (json.dado.id != undefined) {
            this._show(this._botaoHeaderSelecionar);
            this._show(this._blocoHeaderConfigLinha);
            this._adicinarEventoNovoArquivo(bloco, json.dado);
        }
        this._mostrarBlocoBotao();
    }
    _adicinarEventoNovoArquivo(bloco, data) {
        bloco.setAttribute('data-id', data.id);
        bloco.setAttribute('data-dono', data.equipe.nome);
        bloco.setAttribute('data-nome', data.nome);
        bloco.setAttribute('data-extensao', data.extensao);
        bloco.setAttribute('data-tamanho', data.tamanho);
        bloco.setAttribute('data-largura', data.largura);
        bloco.setAttribute('data-altura', data.altura);
        bloco.setAttribute('data-arquivo', data.arquivo);
        bloco.setAttribute('data-data', data.data);
        bloco.querySelector('.fw_upload_nome').innerText = data.nome;
        if (
            data.extensao == 'jpg' ||
            data.extensao == 'jpeg' ||
            data.extensao == 'png' ||
            data.extensao == 'gif' ||
            data.extensao == 'svg'
        ) {
            const blocoImagem = bloco.querySelector('.fw_upload_imagem');
            this._show(blocoImagem);
            blocoImagem.style.backgroundImage = 'url(' + data.arquivo + ')';
        } else {
            const blocoExtensao = bloco.querySelector('.fw_upload_extensao');
            this._show(blocoExtensao);
            blocoExtensao.innerText = data.extensao;
        }
        bloco.addEventListener('click', e => {
            if (!e.target.classList.contains('fw_upload_visualizar') && !e.target.closest('.fw_upload_visualizar')) {
                this._marcarDesmarcarImagem(bloco);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ARQUIVOS
    |--------------------------------------------------------------------------
    */
    _marcarDesmarcarImagem(item) {
        item.classList.toggle('fw_upload_arquivo_checked');
        this._mostrarBlocoBotao();
    }
    _mostrarBlocoBotao() {
        const quantidadeMarcado = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked').length;
        const quantidade = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_imagem').length;

        if (quantidade > 0) {
            this._show(this._botaoHeaderSelecionar);
            this._show(this._blocoHeaderConfigLinha);
        } else {
            this._hide(this._botaoHeaderSelecionar);
            this._hide(this._blocoHeaderConfigLinha);
        }
        if (quantidadeMarcado > 0) {
            this._show(this._botaoHeaderDeletar);
            this._show(this._botaoHeaderMover);
        } else {
            this._hide(this._botaoHeaderDeletar);
            this._hide(this._botaoHeaderMover);
        }
        if (quantidadeMarcado == 1) {
            this._show(this._botaoHeaderRenomear);
            this._show(this._botaoHeaderVisualizar);
            this._show(this._botaoUsarArquivo);
        } else {
            this._hide(this._botaoHeaderVisualizar);
            this._hide(this._botaoHeaderRenomear);
            this._hide(this._botaoUsarArquivo);
        }
        if (quantidadeMarcado > 0 && true === this._multiplo) {
            this._show(this._botaoUsarArquivo);
        }
    }
    _abrirVisualizarImagem() {
        this._show(this._blocoVisualizar);
        this._blocoVisualizarImg.setAttribute('src', this._imagemUso);
        setTimeout(() => {
            this._blocoVisualizar.classList.add('fw_upload_visualizar_imagem_abrir');
        }, 20);
    }
    _fecharVisualizarImagem() {
        this._blocoVisualizar.classList.remove('fw_upload_visualizar_imagem_abrir');
        setTimeout(() => {
            this._blocoVisualizarImg.removeAttribute('src');
            this._hide(this._blocoVisualizar);
        }, 300);
    }
    _selecionarTodosOsArquivo() {
        const arquivo = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_imagem');
        const arquivoSelecionado = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');

        if (arquivo.length == arquivoSelecionado.length) {
            arquivo.forEach(item => {
                item.classList.remove('fw_upload_arquivo_checked');
            });
        } else {
            arquivo.forEach(item => {
                item.classList.add('fw_upload_arquivo_checked');
            });
        }

        this._hide(this._blocoHeaderMenu);
        this._mostrarBlocoBotao();
    }

    /*
    |--------------------------------------------------------------------------
    | BLOCO DE DADO
    |--------------------------------------------------------------------------
    */
    _abrirBlocoDado() {
        const item = this._blocoListaArquivo.querySelector('.fw_upload_arquivo_checked');
        if (!item) {
            return;
        }
        this._blocoDado.classList.add('fw_upload_dado_abrir');

        const dono = item.getAttribute('data-dono');
        const nome = item.getAttribute('data-nome');
        const extensao = item.getAttribute('data-extensao');
        const tamanho = item.getAttribute('data-tamanho');
        const arquivo = item.getAttribute('data-arquivo');
        const largura = item.getAttribute('data-largura');
        const altura = item.getAttribute('data-altura');
        const data = item.getAttribute('data-data');

        this._blocoDadoBg.style.backgroundImage = 'url(' + arquivo + ')';

        this._blocoDadoNome.innerText = nome;
        this._blocoDadoDono.innerText = dono;
        this._blocoDadoExtensao.innerText = extensao;
        if (largura != '' && altura != '') {
            this._show(this._blocoDadoBlocoDimensao);
            this._blocoDadoDimensao.innerText = largura + 'x' + altura + ' pixels';
        }
        if (tamanho != '') {
            this._show(this._blocoDadoBlocoTamanho);
            this._blocoDadoTamanho.innerText = tamanho + ' MB';
        }
        if (extensao == 'png' || extensao == 'jpg' || extensao == 'jpeg' || extensao == 'gif' || extensao == 'svg') {
            this._imagemUso = arquivo;
            this._show(this._botaoDadoVisualizar);
        } else {
            this._show(this._blocoDadoPreviewArquivo);
            this._blocoDadoPreviewArquivo.setAttribute('href', arquivo);
            this._blocoDadoPreviewArquivo.innerText = extensao;
        }
        this._blocoDadoData.innerText = data;
    }
    _fecharBlocoDado() {
        this._blocoDado.classList.remove('fw_upload_dado_abrir');
        this._imagemUso = '';
        setTimeout(() => {
            this._blocoDadoBg.style.backgroundImage = '';
            this._blocoDadoNome.innerText = '';
            this._blocoDadoDono.innerText = '';
            this._blocoDadoExtensao.innerText = '';
            this._blocoDadoTamanho.innerText = '';
            this._hide(this._blocoDadoBlocoTamanho);
            this._hide(this._blocoDadoBlocoDimensao);
            this._hide(this._botaoDadoVisualizar);
            this._blocoDadoDimensao.innerText = '';
            this._blocoDadoData.innerText = '';
            this._hide(this._blocoDadoPreviewArquivo);
            this._blocoDadoPreviewArquivo.innerText = '';
        }, 300);
    }

    /*
    |--------------------------------------------------------------------------
    | CLASSES GERAIS
    |--------------------------------------------------------------------------
    */
    _loadingShow() {
        this._show(this._loadingBarra);
        this._show(this._loadingGeral);
    }
    _loadingHide() {
        this._hide(this._loadingBarra);
        this._hide(this._loadingGeral);
    }
    _hide(bloco) {
        bloco.classList.add('fw_upload_hide');
    }
    _show(bloco) {
        bloco.classList.remove('fw_upload_hide');
    }

    _removerArquivosSelecionado() {
        const selecionado = this._blocoListaArquivo.querySelectorAll('.fw_upload_arquivo_checked');
        selecionado.forEach(item => {
            item.parentNode.removeChild(item);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | HTML
    |--------------------------------------------------------------------------
    */
    async _constructor(grupo, body, multiplo) {
        await this._construtorPropriedadeInicial(grupo, body, multiplo);
        await this._construtorPegarEstruturaDiretorio();

        const extensao = await this._construtorPegarExtensoes();
        if (await !extensao) {
            return;
        }

        await this._contrutorMontarHtml();
        await this._construtorSetaBlocos();
        this._construtorSetarEventosIniciais();
    }
    _construtorPropriedadeInicial(grupo, body, multiplo) {
        return new Promise(resolve => {
            this._grupoInicial = grupo;
            this._grupoAtual = grupo;
            this._editarAcao = '';
            this._pagina = 1;
            this._imagemUso = '';
            this._idNumero = 1;
            this._botaoUsarArquivo = null;
            this._multiplo = true === multiplo;

            this._body = body;
            resolve(true);
        });
    }
    _construtorPegarExtensoes() {
        return new Promise(async resolve => {
            const body = new FormData();
            body.append('grupo', this._grupoInicial);
            const resposta = await fetch(LINK + '/upload/extensao', {
                method: 'POST',
                body,
            });
            const json = await respostaJson(resposta);
            if (false === json) {
                resolve(false);
            }
            this._extensao = json.dado.extensao;
            resolve(true);
        });
    }
    _construtorPegarEstruturaDiretorio() {
        return new Promise(async resolve => {
            const body = new FormData();
            body.append('grupo', this._grupoInicial);

            const resposta = await fetch(LINK + '/upload/estrutura-diretorio', {
                method: 'POST',
                body,
            });

            let json = await respostaJson(resposta);
            if (false === json) {
                resolve(false);
                return;
            }
            this._estruturaDiretorio = json.dado.diretorio;
            resolve(true);
        });
    }

    _construtorSetaBlocos() {
        this._loadingGeral = this._bloco.querySelector('.fw_upload_loading_geral');
        this._loadingBarra = this._bloco.querySelector('.fw_upload_loading_barra');

        this._botaoConfigVoltar = this._bloco.querySelector('.fw_upload_config_voltar');
        this._blocoConfigDiretorio = this._bloco.querySelector('.fw_upload_config_diretorio');

        this._blocoListaDiretorio = this._bloco.querySelector('.fw_upload_lista_diretorio');
        this._blocoListaArquivo = this._bloco.querySelector('.fw_upload_lista_arquivo');

        this._botaoCarregarMais = this._bloco.querySelector('.fw_upload_botao_mais');

        this._inputPesquisa = this._bloco.querySelector('.fw_upload_pesquisa_input');
        this._botaoPesquisa = this._bloco.querySelector('.fw_upload_pesquisa_botao');

        this._blocoZero = this._bloco.querySelector('.fw_upload_zero');
        this._blocoZeroBusca = this._bloco.querySelector('.fw_upload_zero_busca');

        this._botaoHeaderUpload = this._bloco.querySelector('.fw_upload_upload_enviar_imagem');
        this._botaoHeaderFechar = this._bloco.querySelector('.fw_upload_header_fechar');
        this._botaoHeaderVisualizar = this._bloco.querySelector('.fw_upload_header_visualizar');
        this._botaoHeaderMenu = this._bloco.querySelector('.fw_upload_header_menu');
        this._blocoHeaderMenu = this._bloco.querySelector('.fw_upload_header_menu_bloco');
        this._blocoHeaderConfigLinha = this._bloco.querySelector('.fw_upload_header_config_linha');
        this._botaoHeaderDeletar = this._bloco.querySelector('.fw_upload_header_deletar');
        this._botaoHeaderRenomear = this._bloco.querySelector('.fw_upload_header_renomear');
        this._botaoHeaderSelecionar = this._bloco.querySelector('.fw_upload_header_selecionar');
        this._botaoHeaderMover = this._bloco.querySelector('.fw_upload_header_mover');
        this._botaoHeaderDiretorioCriar = this._bloco.querySelector('.fw_upload_header_diretorio_criar');
        this._botaoHeaderDiretorioDeletar = this._bloco.querySelector('.fw_upload_header_diretorio_deletar');
        this._botaoHeaderDiretorioRenomear = this._bloco.querySelector('.fw_upload_header_diretorio_renomear');

        this._blocoVisualizar = this._bloco.querySelector('.fw_upload_visualizar_imagem');
        this._botaoVisualizarFechar = this._bloco.querySelector('.fw_upload_visualizar_fechar');
        this._blocoVisualizarImg = this._bloco.querySelector('.fw_upload_visualizar_img');

        this._blocoDado = this._bloco.querySelector('.fw_upload_dado');
        this._blocoDadoNome = this._bloco.querySelector('.fw_upload_dado_nome');
        this._blocoDadoBg = this._bloco.querySelector('.fw_upload_dado_bg');
        this._blocoDadoDono = this._bloco.querySelector('.fw_upload_dado_dono');
        this._blocoDadoExtensao = this._bloco.querySelector('.fw_upload_dado_extensao');
        this._blocoDadoBlocoTamanho = this._bloco.querySelector('.fw_upload_dado_bloco_tamanho');
        this._blocoDadoTamanho = this._bloco.querySelector('.fw_upload_dado_tamanho');
        this._blocoDadoBlocoDimensao = this._bloco.querySelector('.fw_upload_dado_bloco_dimensao');
        this._blocoDadoDimensao = this._bloco.querySelector('.fw_upload_dado_dimensao');
        this._blocoDadoData = this._bloco.querySelector('.fw_upload_dado_data');
        this._blocoDadoPreviewArquivo = this._bloco.querySelector('.fw_upload_dado_preview_arquivo');

        this._botaoDadoFechar = this._bloco.querySelector('.fw_upload_dado_fechar');
        this._botaoDadoVisualizar = this._bloco.querySelector('.fw_upload_dado_visualizar');

        this._blocoListaMover = this._bloco.querySelector('.fw_upload_diretorio_mover');
        this._botaoListaMoverFechar = this._bloco.querySelector('.fw_upload_diretorio_mover_fechar');
        this._blocoListaMoverLista = this._bloco.querySelector('.fw_upload_diretorio_mover_lista');
        this._blocoListaMoverInput = this._bloco.querySelector('.fw_upload_diretorio_mover_input');
        this._botaoListaMoverCriar = this._bloco.querySelector('.fw_upload_diretorio_mover_botao');

        this._blocoFormEditar = this._bloco.querySelector('.fw_upload_form_editar');
        this._botaoFormEditarFechar = this._bloco.querySelector('.fw_upload_form_editar_fechar');
        this._blocoFormEditarTitulo = this._bloco.querySelector('.fw_upload_form_editar_h1');
        this._blocoFormEditarTexto = this._bloco.querySelector('.fw_upload_form_editar_p');
        this._blocoFormEditarInput = this._bloco.querySelector('.fw_upload_form_editar_input');
        this._botaoFormEditarSalvar = this._bloco.querySelector('.fw_upload_form_editar_botao');

        this._botaoUsarArquivo = this._bloco.querySelector('.fw_upload_usar_arquivo');
    }

    _construtorSetarEventosIniciais() {
        this._botaoHeaderFechar.addEventListener('click', () => {
            this._fecharUpload();
        });
        this._botaoHeaderUpload.addEventListener('change', () => {
            this._fazerUpload();
        });
        this._blocoVisualizar.addEventListener('click', e => {
            if (e.target.classList.contains('fw_upload_visualizar_imagem')) {
                this._fecharVisualizarImagem();
            }
        });
        this._botaoVisualizarFechar.addEventListener('click', () => {
            this._fecharVisualizarImagem();
        });
        this._botaoHeaderVisualizar.addEventListener('click', () => {
            this._abrirBlocoDado();
        });
        this._botaoDadoFechar.addEventListener('click', () => {
            this._fecharBlocoDado();
        });
        this._inputPesquisa.addEventListener('focus', e => {
            this._fecharBlocoDado();
        });
        this._botaoDadoVisualizar.addEventListener('click', () => {
            this._abrirVisualizarImagem();
        });
        this._botaoCarregarMais.addEventListener('click', () => {
            this._pagina++;
            this._carregarArquivo();
        });
        this._inputPesquisa.addEventListener('keydown', e => {
            if (e.key == 'Enter') {
                e.preventDefault();
            }
        });
        this._inputPesquisa.addEventListener('keyup', e => {
            if (e.key == 'Enter') {
                e.preventDefault();
                this._pagina = 1;
                this._carregarArquivo();
            }
        });
        this._botaoPesquisa.addEventListener('click', e => {
            e.preventDefault();
            this._pagina = 1;
            this._carregarArquivo();
        });
        this._botaoConfigVoltar.addEventListener('click', e => {
            this._voltarParaDiretorioAnterior();
        });

        this._botaoHeaderMenu.addEventListener('click', () => {
            this._show(this._blocoHeaderMenu);
        });
        this._botaoHeaderMover.addEventListener('click', () => {
            this._hide(this._blocoHeaderMenu);
            this._abrirBlocoMover();
        });
        this._botaoListaMoverFechar.addEventListener('click', () => {
            this._fecharBlocoMover();
        });
        this._blocoListaMoverInput.addEventListener('keyup', () => {
            const valor = this._blocoListaMoverInput.value.trim();
            if (valor == '') {
                this._botaoListaMoverCriar.innerText = 'Mover';
            } else {
                this._botaoListaMoverCriar.innerText = 'Criar';
            }
        });
        this._botaoListaMoverCriar.addEventListener('click', () => {
            this._moverItemMarcado();
        });
        this._bloco.addEventListener('click', e => {
            const target = e.target;
            if (
                !target.classList.contains('fw_upload_header_menu') &&
                !target.closest('.fw_upload_header_menu') &&
                !target.classList.contains('fw_upload_header_menu_bloco') &&
                !target.closest('.fw_upload_header_menu_bloco')
            ) {
                this._hide(this._blocoHeaderMenu);
            }
            if (
                !target.classList.contains('fw_upload_dado') &&
                !target.closest('.fw_upload_dado') &&
                !target.classList.contains('fw_upload_visualizar_imagem') &&
                !target.closest('.fw_upload_visualizar_imagem') &&
                !target.classList.contains('fw_upload_header_visualizar') &&
                !target.closest('.fw_upload_header_visualizar')
            ) {
                this._fecharBlocoDado();
            }
        });
        this._botaoHeaderDeletar.addEventListener('click', async () => {
            const mensagem = await Alerta.confirmar(
                'Deletar arquivo!',
                `
                    Tem certeza que deseja deletar os arquivos selecioandos?<br>
                    Qualquer arquivo que esteja sendo usada não irá mais aparecer. Continue apenas se tiver
                    certeza que deseja executar essa ação.
                `,
                '!'
            );
            if (mensagem) {
                this._deletarArquivo();
            }
        });
        this._botaoHeaderDiretorioDeletar.addEventListener('click', async () => {
            const blocoNome = this._blocoConfigDiretorio.querySelector('.fw_upload_config_diretorio_nome');
            const nomeHtml = blocoNome ? `<strong> ${blocoNome.innerText}</strong>` : '';
            const mensagem = await Alerta.confirmar(
                'Deletar diretório atual',
                `
                    Tem certeza que deseja deletar o diretório${nomeHtml}?<br>
                    Isso irá deletar todos os arquivos e subdiretórios, qualquer arquivo que esteja sendo usado
                    por esse diretório e/ou subdiretórios não irá mais aparecer. Continue apenas se tiver certeza
                    que deseja executar essa ação.
                `,
                '!'
            );
            if (mensagem) {
                this._deletarDiretorio();
            }
        });

        this._botaoFormEditarFechar.addEventListener('click', () => {
            this._fecharBlocoEditar();
        });
        this._botaoHeaderRenomear.addEventListener('click', () => {
            this._abrirBlocoEditarGeral('renomar_arquivo', 'RENOMEAR ARQUIVO', 'Digite o novo nome para o arquivo');
        });
        this._botaoHeaderDiretorioCriar.addEventListener('click', () => {
            this._abrirBlocoEditarGeral('criar_diretorio', 'CRIAR DIRETÓRIO', 'Digite o nome do novo diretório');
        });
        this._botaoHeaderDiretorioRenomear.addEventListener('click', () => {
            this._abrirBlocoEditarGeral(
                'renomar_diretorio',
                'RENOMEAR DIRETÓRIO',
                'Digite o novo nome para o diretório'
            );
        });

        this._botaoFormEditarSalvar.addEventListener('click', () => {
            this._escolherAcaoEditar();
        });
        this._blocoFormEditarInput.addEventListener('keyup', e => {
            if (e.key == 'Enter') {
                e.preventDefault();
                this._escolherAcaoEditar();
            }
        });
        this._botaoHeaderSelecionar.addEventListener('click', () => {
            this._selecionarTodosOsArquivo();
        });
    }
    _contrutorMontarHtml() {
        return new Promise(resolve => {
            const nome = Math.floor(Date.now() * Math.random()).toString(36);
            const id = `fw_upload_${nome}`;
            document.querySelector('body').insertAdjacentHTML(
                'beforeend',
                `
                <div class="fw_upload fw_upload_hide" id="${id}">
                    ${this._htmlVisualizarImagem()}
                    ${this._htmlRenomearCriar()}
                    <div class="fw_upload_loading_geral fw_upload_hide"></div>
                    ${this._htmlDiretorioLista()}
                    <div class="fw_upload_conteudo">
                        ${this._htmlHeader()}
                        ${this._htmlConfig()}
                        <div class="fw_upload_usar_arquivo fw_upload_hide">USAR ARQUIVO</div>
                        ${this._htmlImagemDado()}
                        ${this._htmlListaArquivo()}
                    </div>
                </div>
                `
            );
            setTimeout(() => {
                this._bloco = document.querySelector('#' + id);
                resolve(true);
            }, 100);
        });
    }
    _htmlRenomearCriar() {
        return `
            <div class="fw_upload_form_editar fw_upload_hide">
                <div class="fw_upload_form_editar_conteudo">
                    <div class="fw_upload_form_editar_h1">RENOMEAR</div>
                    <div class="fw_upload_form_editar_fechar"><svg height="14" version="1.1" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path d="M24,20L39.2,4.9c1.1-1.1,1.1-2.9,0-4c-1.1-1.1-2.9-1.1-4,0L20,16L4.9,0.8c-1.1-1.1-2.9-1.1-4,0c-1.1,1.1-1.1,2.9,0,4L16,20 L0.8,35.1c-1.1,1.1-1.1,2.9,0,4c0.6,0.6,1.3,0.8,2,0.8c0.7,0,1.5-0.3,2-0.8L20,24l15.1,15.1c0.6,0.6,1.3,0.8,2,0.8 c0.7,0,1.5-0.3,2-0.8c1.1-1.1,1.1-2.9,0-4L24,20z" /></svg></div>
                    <div class="fw_upload_form_editar_p">Digite o nome do novo diretório</div>
                    <input type="text" class="fw_upload_form_editar_input" placeholder="Digite um nome">
                    <div class="fw_upload_form_editar_botao">SALVAR</div>
                </div>
            </div>
        `;
    }

    _htmlVisualizarImagem() {
        return `
            <div class="fw_upload_visualizar_imagem fw_upload_hide">
                <div class="fw_upload_visualizar_fechar"><svg height="14" version="1.1" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path d="M24,20L39.2,4.9c1.1-1.1,1.1-2.9,0-4c-1.1-1.1-2.9-1.1-4,0L20,16L4.9,0.8c-1.1-1.1-2.9-1.1-4,0c-1.1,1.1-1.1,2.9,0,4L16,20 L0.8,35.1c-1.1,1.1-1.1,2.9,0,4c0.6,0.6,1.3,0.8,2,0.8c0.7,0,1.5-0.3,2-0.8L20,24l15.1,15.1c0.6,0.6,1.3,0.8,2,0.8 c0.7,0,1.5-0.3,2-0.8c1.1-1.1,1.1-2.9,0-4L24,20z" /></svg></div>
                <img src="" class="fw_upload_visualizar_img">
            </div>
        `;
    }

    _htmlDiretorioLista() {
        return `
            <div class="fw_upload_diretorio_mover fw_upload_hide">
                <div class="fw_upload_diretorio_mover_conteudo">
                    <div class="fw_upload_diretorio_mover_h1">MOVER ARQUIVO</div>
                    <div class="fw_upload_diretorio_mover_fechar"><svg width="12" height="12" version="1.1" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path d="M24,20L39.2,4.9c1.1-1.1,1.1-2.9,0-4c-1.1-1.1-2.9-1.1-4,0L20,16L4.9,0.8c-1.1-1.1-2.9-1.1-4,0c-1.1,1.1-1.1,2.9,0,4L16,20 L0.8,35.1c-1.1,1.1-1.1,2.9,0,4c0.6,0.6,1.3,0.8,2,0.8c0.7,0,1.5-0.3,2-0.8L20,24l15.1,15.1c0.6,0.6,1.3,0.8,2,0.8 c0.7,0,1.5-0.3,2-0.8c1.1-1.1,1.1-2.9,0-4L24,20z" /></svg></div>

                    <div class="fw_upload_diretorio_mover_lista"></div>

                    <div class="fw_upload_diretorio_mover_p">Digite um nome caso queira criar uma nova pasta do diretório escolhido.</div>
                    <div class="fw_upload_diretorio_mover_aceitar">
                        <input class="fw_upload_diretorio_mover_aceitar_input fw_upload_diretorio_mover_input" placeholder="Criar novo diretório">
                        <div class="fw_upload_diretorio_mover_aceitar_botao fw_upload_diretorio_mover_botao">Mover</div>
                    </div>
                </div>
            </div>
        `;
    }

    _htmlHeader() {
        return `
            <div class="fw_upload_header">
                <div class="fw_upload_header_upload">
                    <svg width="19" height="19" version="1.1" x="0px" y="0px" viewBox="0 0 80 80" style="enable-background:new 0 0 80 80;" xml:space="preserve"><g transform="translate(-1093 315)"><path d="M1133-295c0.7,0,1.4,0.3,1.9,0.8l12.5,15c0.3,0.3,0.4,0.8,0.4,1.2c0.1,1.3-1,2.8-2.4,2.8h-7.5v17.5c0,1.3-1.2,2.5-2.5,2.5 h-5c-0.2,0-0.3,0-0.4,0c-1.1-0.3-2.1-1.3-2-2.5v-17.5h-7.5c-1.8-0.1-3.1-2.6-1.9-4.1l12.5-15C1131.6-294.7,1132.3-295,1133-295 L1133-295z" /><path d="M1133-315c-22,0-40,17.9-40,40s17.9,40,40,40s40-17.9,40-40S1155-315,1133-315z M1133-307.6c18,0,32.6,14.6,32.6,32.6 s-14.6,32.6-32.6,32.6s-32.6-14.6-32.6-32.6S1115-307.6,1133-307.6z" /></g></svg>

                    <p class="fw_upload_upload_enviar_texto">Fazer Upload</p>
                    <input type="file" class="fw_upload_upload_enviar_imagem" multiple accept=".${this._extensao.join(
                        ',.'
                    )}">
                </div>

                <form action="/" method="get" class="fw_upload_header_form">
                    <input class="fw_upload_header_form_input fw_upload_pesquisa_input" type="search" placeholder="Buscar..." value="">
                    <div class="fw_upload_header_form_botao fw_upload_pesquisa_botao">
                        <svg width="15" height="15" version="1.1" x="0px" y="0px" viewBox="0 0 80 80" style="enable-background:new 0 0 80 80;" xml:space="preserve"><path d="M59.4,50.6c10-14.9,6.1-35-8.8-45s-35-6.1-45,8.8s-6.1,35,8.8,45c11,7.4,25.3,7.4,36.2,0l18.8,18.8c2.4,2.4,6.4,2.4,8.8,0 c2.4-2.4,2.4-6.4,0-8.8L59.4,50.6z M32.5,52.5c-11,0-20-8.9-20-20s8.9-20,20-20s20,8.9,20,20l0,0C52.5,43.5,43.5,52.5,32.5,52.5z" /></svg>
                    </div>
                </form>
                <div class="fw_upload_header_config">
                    <div class="fw_upload_header_botao fw_upload_hide fw_upload_header_visualizar"><svg height="15" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg></div>
                    <div class="fw_upload_header_botao fw_upload_header_menu"><svg height="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 10 40" style="enable-background:new 0 0 10 40;" xml:space="preserve"><g><path class="st0" d="M9.7,4.7c0,2.6-2.1,4.7-4.7,4.7S0.3,7.2,0.3,4.7C0.3,2.1,2.4,0,5,0S9.7,2.1,9.7,4.7"/><path class="st0" d="M9.7,19.5c0,2.6-2.1,4.7-4.7,4.7s-4.7-2.1-4.7-4.7c0-2.6,2.1-4.7,4.7-4.7S9.7,17,9.7,19.5"/><path class="st0" d="M9.7,35.3C9.7,37.9,7.6,40,5,40s-4.7-2.1-4.7-4.7c0-2.6,2.1-4.7,4.7-4.7S9.7,32.8,9.7,35.3"/></g></svg></div>
                    <div class="fw_upload_header_config_ul fw_upload_header_menu_bloco fw_upload_hide">
                        <div class="fw_upload_header_config_li fw_upload_hide fw_upload_header_selecionar">Marcar/Desmarcar todos</div>
                        <div class="fw_upload_header_config_li fw_upload_hide fw_upload_header_mover">Mover selecionados</div>
                        <div class="fw_upload_header_config_li fw_upload_hide fw_upload_header_renomear">Renomear Arquivo</div>
                        <div class="fw_upload_header_config_li fw_upload_header_config_li_deletar fw_upload_hide fw_upload_header_deletar">Deletar selecionados</div>
                        <div class="fw_upload_header_config_linha fw_upload_hide fw_upload_header_config_linha"></div>
                        <div class="fw_upload_header_config_li fw_upload_header_diretorio_criar">Criar diretório</div>
                        <div class="fw_upload_header_config_li fw_upload_hide fw_upload_header_diretorio_renomear">Renomear diretório</div>
                        <div class="fw_upload_header_config_li fw_upload_header_config_li_deletar fw_upload_hide fw_upload_header_diretorio_deletar">Deletar diretório</div>
                    </div>
                </div>
                <div class="fw_upload_header_botao fw_upload_header_fechar">
                    <svg width="12" height="12" version="1.1" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path d="M24,20L39.2,4.9c1.1-1.1,1.1-2.9,0-4c-1.1-1.1-2.9-1.1-4,0L20,16L4.9,0.8c-1.1-1.1-2.9-1.1-4,0c-1.1,1.1-1.1,2.9,0,4L16,20 L0.8,35.1c-1.1,1.1-1.1,2.9,0,4c0.6,0.6,1.3,0.8,2,0.8c0.7,0,1.5-0.3,2-0.8L20,24l15.1,15.1c0.6,0.6,1.3,0.8,2,0.8 c0.7,0,1.5-0.3,2-0.8c1.1-1.1,1.1-2.9,0-4L24,20z" /></svg>
                </div>
            </div>
        `;
    }
    _htmlConfig() {
        return `
            <div class="fw_upload_config">
                <div class="fw_upload_loading_linha fw_upload_hide fw_upload_loading_barra"></div>
                <div class="fw_upload_config_voltar fw_upload_hide"><svg height="12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 38" style="enable-background:new 0 0 50 38;" xml:space="preserve"><path d="M48.1,17.1H6.4L20,3.3c0.8-0.7,0.8-1.9,0.1-2.7c-0.7-0.8-1.9-0.8-2.7-0.1c0,0-0.1,0.1-0.1,0.1l-16.8,17 c-0.7,0.7-0.7,1.9,0,2.7l16.8,17c0.7,0.8,1.9,0.8,2.7,0.1c0.8-0.7,0.8-1.9,0.1-2.7c0,0-0.1-0.1-0.1-0.1L6.4,20.9h41.7 c1,0,1.9-0.9,1.9-1.9C50,17.9,49.2,17.1,48.1,17.1z"/></svg></div>
                <div class="fw_upload_config_diretorio">
                </div>
            </div>
        `;
    }
    _htmlImagemDado() {
        return `
            <div class="fw_upload_dado">
                <div class="fw_upload_dado_nome"></div>
                <div class="fw_upload_dado_fechar">
                    <svg width="12" height="12" version="1.1" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path d="M24,20L39.2,4.9c1.1-1.1,1.1-2.9,0-4c-1.1-1.1-2.9-1.1-4,0L20,16L4.9,0.8c-1.1-1.1-2.9-1.1-4,0c-1.1,1.1-1.1,2.9,0,4L16,20 L0.8,35.1c-1.1,1.1-1.1,2.9,0,4c0.6,0.6,1.3,0.8,2,0.8c0.7,0,1.5-0.3,2-0.8L20,24l15.1,15.1c0.6,0.6,1.3,0.8,2,0.8 c0.7,0,1.5-0.3,2-0.8c1.1-1.1,1.1-2.9,0-4L24,20z" /></svg>
                </div>

                <div class="fw_upload_dado_preview fw_upload_hide fw_upload_dado_visualizar">
                    <div class="fw_upload_dado_preview_grade"></div>
                    <div class="fw_upload_dado_preview_bg fw_upload_dado_bg"></div>
                </div>
                <a class="fw_upload_dado_preview_arquivo" target="_blank" href="">
                </a>

                <div class="fw_upload_dado_detalhe fw_upload_dado_detalhe_dono">
                    <strong>Dono:</strong> <p class="fw_upload_dado_dono"></p>
                </div>
                <div class="fw_upload_dado_detalhe">
                    <strong>Extensão:</strong> <p class="fw_upload_dado_extensao"></p>
                </div>
                <div class="fw_upload_dado_detalhe fw_upload_hide fw_upload_dado_bloco_tamanho">
                    <strong>Tamanho:</strong> <p class="fw_upload_dado_tamanho"></p>
                </div>
                <div class="fw_upload_dado_detalhe fw_upload_hide fw_upload_dado_bloco_dimensao">
                    <strong>Dimensão:</strong> <p class="fw_upload_dado_dimensao"></p>
                </div>
                <div class="fw_upload_dado_detalhe">
                    <strong>Data:</strong> <p class="fw_upload_dado_data"></p>
                </div>
            </div>
        `;
    }
    _htmlListaArquivo() {
        return `
            <div class="fw_upload_arquivo">
                <div class="fw_upload_arquivo_diretorio fw_upload_hide fw_upload_lista_diretorio">
                </div>
                <div class="fw_upload_arquivo_lista fw_upload_hide fw_upload_lista_arquivo">
                </div>
                <div class="fw_upload_zero fw_upload_zero_geral fw_upload_hide">
                    <div class="fw_upload_zero_i"><svg height="30" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></div>
                    <div class="fw_upload_zero_p">Sem arquivo no momento</div>
                </div>
                <div class="fw_upload_zero fw_upload_hide fw_upload_zero_busca">
                    <div class="fw_upload_zero_i"><svg height="30" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve"><path class="st0" fill="none" d="M12.97,3.17c-5.35,0-9.72,4.3-9.72,9.57s4.37,9.57,9.72,9.57s9.72-4.3,9.72-9.57S18.32,3.17,12.97,3.17z"/><path class="st1" d="M23.64,20.73c1.66-2.17,2.68-4.87,2.68-7.8C26.32,5.8,20.39,0,13.14,0S0,5.8,0,12.93s5.89,12.93,13.14,12.93 c2.71,0,5.25-0.83,7.35-2.23c0.03,0.03,0.03,0.03,0.07,0.07l5.76,5.67c0.41,0.4,0.98,0.63,1.52,0.63s1.12-0.2,1.52-0.63 c0.85-0.83,0.85-2.17,0-3L23.64,20.73z M13.14,21.63c-4.88,0-8.84-3.9-8.84-8.7s3.96-8.7,8.84-8.7s8.84,3.9,8.84,8.7 S18.02,21.63,13.14,21.63z"/></svg></div>
                    <div class="fw_upload_zero_p">Não foi encontrado<br>nenhuma arquivo pela busca</div>
                </div>
                <div class="fw_upload_mais fw_upload_hide fw_upload_botao_mais">CARREGAR MAIS</div>
            </div>
        `;
    }

    _htmlNovoArquivo(loading) {
        return new Promise(resolve => {
            const classe = 'id_fw_upload_' + this._idNumero;
            this._idNumero++;

            const classLoading = loading === true ? 'fw_upload_arquivo_loading' : '';

            const local = loading === true ? 'afterbegin' : 'beforeend';
            this._blocoListaArquivo.insertAdjacentHTML(
                local,
                `
                <div class="fw_upload_arquivo_imagem ${classLoading} ${classe}" >
                    <div class="fw_upload_figure">
                        <div class="fw_upload_bg"></div>
                        <div class="fw_upload_grade"></div>
                        <div class="fw_upload_imagem fw_upload_hide"></div>
                        <div class="fw_upload_extensao fw_upload_hide"></div>
                        <div class="fw_upload_check">
                            <div class="fw_upload_check_icone">
                                <svg height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="fw_upload_nome"></div>
                </div>
            `
            );

            resolve(this._blocoListaArquivo.querySelector('.' + classe));
        });
    }
}
