class Galeria {
    /**
     * Inicia uma galeria
     *
     * @param {elemento} bloco Elemento onde vai ficar a galeria
     * @param {elemento} figure Qual vai ser o elemento que terá o bloco da foto
     * @param {elemento} botao Qual vai ser o elemento que abrirá a foto, caso não informe, será o figure
     * @param {string} download Link para download da imagem caso queira abilitar essa opção
     */
    constructor(bloco, figure, botao, download) {
        this._bloco = this._pegarElemento(bloco);
        if (!this._bloco) {
            return;
        }
        this._figure = figure;
        this._botao = botao;
        this._download = download == undefined ? '' : download;
        this._transicao = false;
        this._figureAtual;
        this._figureLista;
        this._figureAberta;

        this._body = document.querySelector('body');
        this._montarDadosDaGaleria();
    }

    reordenar() {
        this._figureLista = this._bloco.querySelectorAll(this._figure);
    }

    /**
     * Adiciona um novo item a galeria
     *
     * @param {element} figure Elemento que deseja adicionar
     * @param {object} option Objeto com os campos id, imagem, titulo, link, linkTitulo ou linkTarget (Opcioanl)
     */
    add(figure, option) {
        if (!(option instanceof Object)) {
            option = {};
        }
        if (option.id != undefined) {
            figure.setAttribute('data-galeria-id', option.id);
        }
        if (option.imagem != undefined) {
            figure.setAttribute('data-galeria-imagem', option.imagem);
        }
        if (option.titulo != undefined) {
            figure.setAttribute('data-galeria-titulo', option.titulo);
        }
        if (option.link != undefined) {
            figure.setAttribute('data-galeria-link', option.link);
        }
        if (option.linkTitulo != undefined) {
            figure.setAttribute('data-galeria-link-titulo', option.linkTitulo);
        }
        if (option.linkTarget != undefined) {
            figure.setAttribute('data-galeria-link-target', option.linkTarget);
        }

        figure.classList.add(this._figure.replace(/^\./, ''));
        this._adicionarEventoAoClicarParaAbrir(figure);
        this.reordenar();
    }
    /**
     *
     * @param {element} figure A Figure que deseja remover
     */
    remover(figure) {
        figure.classList.remove(this._figure.replace(/^\./, ''));
        figure.removeAttribute('data-galeria');
        figure.removeAttribute('data-galeria-id');
        figure.removeAttribute('data-galeria-imagem');
        figure.removeAttribute('data-galeria-titulo');
        figure.removeAttribute('data-galeria-link');
        figure.removeAttribute('data-galeria-link-titulo');
        figure.removeAttribute('data-galeria-link-target');
        this.reordenar();
    }
    /**
     * Atualiza as informações de uma figure
     *
     * @param {element} figure A figure que deseja atualizar
     * @param {object} option Objeto com os campos id, imagem, titulo, link, linkTitulo ou linkTarget;
     */
    atualizar(figure, option) {
        if (!(option instanceof Object)) {
            option = {};
        }

        if (option.id != undefined) {
            figure.setAttribute('data-galeria-id', option.id);
        } else {
            figure.removeAttribute('data-galeria-id');
        }
        if (option.imagem != undefined) {
            figure.setAttribute('data-galeria-imagem', option.imagem);
        } else {
            figure.removeAttribute('data-galeria-imagem');
        }
        if (option.titulo != undefined) {
            figure.setAttribute('data-galeria-titulo', option.titulo);
        } else {
            figure.removeAttribute('data-galeria-titulo');
        }
        if (option.link != undefined) {
            figure.setAttribute('data-galeria-link', option.link);
        } else {
            figure.removeAttribute('data-galeria-link');
        }
        if (option.linkTitulo != undefined) {
            figure.setAttribute('data-galeria-link-titulo', option.linkTitulo);
        } else {
            figure.removeAttribute('data-galeria-link-titulo');
        }
        if (option.linkTarget != undefined) {
            figure.setAttribute('data-galeria-link-target', option.linkTarget);
        } else {
            figure.removeAttribute('data-galeria-link-target');
        }
    }

    _pegarElemento(elemento) {
        if (elemento instanceof Object) {
            return elemento;
        } else if (!elemento instanceof String) {
            return false;
        }
        elemento = document.querySelector(elemento);
        if (elemento) {
            return elemento;
        }
        return false;
    }

    async _montarDadosDaGaleria() {
        const primeiraGaleria = await this._montarHtml();
        if (primeiraGaleria) {
            await this._setarElementosDoBlocoDaGaleria();
            await this._setarEventosDaGaleria();
        }
        this._buscarListaDeFigures();
    }

    _montarHtml() {
        return new Promise(resolve => {
            let fwBloco = document.querySelector('#fw_galeria');
            if (fwBloco) {
                resolve(false);
            }
            document.querySelector('body').insertAdjacentHTML(
                'beforeend',
                `
                    <div id="fw_galeria">
                        <div id="fw_galeria_header">
                            <div id="fw_galeria_h1"></div>
                            <div id="fw_galeria_grow"></div>
                            <a href="" target="_blank" download class="fw_galeria_icone" id="fw_galeria_download"><svg height="22" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34 40" style="enable-background:new 0 0 34 40;" xml:space="preserve"><path d="M15.7,1.3c0,3.1,0,6.2,0,9.3c0,4.9,0,9.9,0,14.8c0,1.1,0,2.2,0,3.4c0,0.7,0.6,1.4,1.3,1.3s1.3-0.6,1.3-1.3 c0-3.1,0-6.2,0-9.3c0-4.9,0-9.9,0-14.8c0-1.1,0-2.2,0-3.4C18.3,0.6,17.7,0,17,0C16.3,0,15.7,0.6,15.7,1.3L15.7,1.3z"/><path d="M6.1,18.4c1.1,1.4,2.3,2.7,3.4,4.1c1.8,2.2,3.6,4.3,5.4,6.5c0.4,0.5,0.8,1,1.2,1.5c0.4,0.5,1.4,0.5,1.9,0 c1.1-1.4,2.3-2.7,3.4-4.1c1.8-2.2,3.6-4.3,5.4-6.5c0.4-0.5,0.8-1,1.2-1.5c0.5-0.6,0.5-1.4,0-1.9c-0.5-0.5-1.4-0.6-1.9,0 c-1.1,1.4-2.3,2.7-3.4,4.1c-1.8,2.2-3.6,4.3-5.4,6.5c-0.4,0.5-0.8,1-1.2,1.5c0.6,0,1.2,0,1.9,0c-1.1-1.4-2.3-2.7-3.4-4.1 c-1.8-2.2-3.6-4.3-5.4-6.5c-0.4-0.5-0.8-1-1.2-1.5c-0.5-0.6-1.4-0.5-1.9,0C5.6,17.1,5.6,17.8,6.1,18.4L6.1,18.4z"/><path d="M1.3,40c1,0,2.1,0,3.1,0c2.5,0,5,0,7.5,0c3,0,6,0,9.1,0c2.6,0,5.2,0,7.8,0c1.3,0,2.5,0,3.8,0c0,0,0,0,0.1,0 c0.7,0,1.3-0.6,1.3-1.3s-0.6-1.3-1.3-1.3c-1,0-2.1,0-3.1,0c-2.5,0-5,0-7.5,0c-3,0-6,0-9.1,0c-2.6,0-5.2,0-7.8,0 c-1.3,0-2.5,0-3.8,0c0,0,0,0-0.1,0c-0.7,0-1.3,0.6-1.3,1.3C0,39.4,0.6,40,1.3,40L1.3,40z"/></svg></a>
                            <div class="fw_galeria_icone" id="fw_galeria_fechar"><svg height="18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></div>
                        </div>

                        <div class="fw_galeria_figure" id="fw_galeria_figure"><img draggable class="fw_galeria_img" id="fw_galeria_img" src=""></div>
                        <div class="fw_galeria_figure" id="fw_galeria_figure_animacao"><img draggable class="fw_galeria_img" id="fw_galeria_img_animacao" src=""></div>

                        <div class="fw_galeria_seta" id="fw_galeria_anterior">
                            <svg height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M0,967.6c0.1,0.6,0.3,1.2,0.7,1.6l10.2,12.1c1,1.2,2.8,1.4,4,0.4c1.2-1,1.4-2.8,0.4-4c0,0-0.1-0.1-0.1-0.1l-8.7-10.2 l8.7-10.2c1.1-1.2,1-3-0.2-4.1s-3-1-4,0.2c0,0-0.1,0.1-0.1,0.1L0.7,965.5C0.2,966.1-0.1,966.8,0,967.6L0,967.6z"/></g></svg>
                        </div>
                        <div class="fw_galeria_seta" id="fw_galeria_proximo">
                            <svg height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M16,967.1c-0.1-0.6-0.3-1.2-0.7-1.6L5.1,953.4c-1-1.2-2.8-1.4-4-0.4c-1.2,1-1.4,2.8-0.4,4c0,0,0.1,0.1,0.1,0.1l8.7,10.2 l-8.7,10.2c-1.1,1.2-1,3,0.2,4.1s3,1,4-0.2c0,0,0.1-0.1,0.1-0.1l10.2-12.1C15.8,968.6,16.1,967.9,16,967.1L16,967.1z"/></g></svg>
                        </div>

                        <a id="fw_galeria_link" href="" target="">CLIQUE AQUI</a>
                    </div>
                `
            );
            resolve(true);
        });
    }
    _setarElementosDoBlocoDaGaleria() {
        return new Promise(resolve => {
            this._fwBloco = document.querySelector('#fw_galeria');
            this._fwTitulo = document.querySelector('#fw_galeria_h1');
            this._fwDownload = document.querySelector('#fw_galeria_download');
            this._fwFechar = document.querySelector('#fw_galeria_fechar');
            this._fwAnterior = document.querySelector('#fw_galeria_anterior');
            this._fwProximo = document.querySelector('#fw_galeria_proximo');
            this._fwFigure = document.querySelector('#fw_galeria_figure');
            this._fwFigureAnimacao = document.querySelector('#fw_galeria_figure_animacao');
            this._fwImg = document.querySelector('#fw_galeria_img');
            this._fwImgAnimacao = document.querySelector('#fw_galeria_img_animacao');
            this._fwLink = document.querySelector('#fw_galeria_link');
            resolve(true);
        });
    }
    _setarEventosDaGaleria() {
        const self = this;
        return new Promise(resolve => {
            this._body.addEventListener('keyup', e => {
                if (this._figureAberta && e.key == 'ArrowRight') {
                    if (this._figureLista.length > 1) {
                        this._mudarImagem('proximo');
                    }
                } else if (this._figureAberta && e.key == 'ArrowLeft') {
                    if (this._figureLista.length > 1) {
                        this._mudarImagem('anterior');
                    }
                } else if (this._figureAberta && e.key == 'Escape') {
                    self._fecharGaleria();
                }
            });
            this._fwBloco.addEventListener('click', e => {
                if (e.target.getAttribute('id') == 'fw_galeria') {
                    self._fecharGaleria();
                }
            });
            this._fwFechar.addEventListener('click', () => {
                self._fecharGaleria();
            });
            this._fwAnterior.addEventListener('click', () => {
                self._mudarImagem('anterior');
            });
            this._fwProximo.addEventListener('click', () => {
                self._mudarImagem('proximo');
            });
            this._fwBloco.addEventListener('swiped-right', () => {
                self._mudarImagem('anterior');
            });
            this._fwBloco.addEventListener('swiped-left', () => {
                self._mudarImagem('proximo');
            });
            resolve(true);
        });
    }

    _fecharGaleria() {
        this._figureAberta = false;
        this._fwBloco.classList.remove('fw_galeria_abrir');
        setTimeout(() => {
            this._fwBloco.style.display = 'none';
            this._fwImg.removeAttribute('src');
            this._fwImgAnimacao.removeAttribute('src');
            this._fwTitulo.innerText = '';
            this._fwDownload.style.display = 'none';
            this._fwAnterior.style.display = 'none';
            this._fwProximo.style.display = 'none';
            this._fwFigure.style.margin = 0;
            this._fwFigureAnimacao.style.margin = 0;
            this._fwFigureAnimacao.style.display = 'none';
            this._fwLink.removeAttribute('href');
            this._fwLink.removeAttribute('target');
            this._fwLink.innerText = '';
            this._fwLink.style.display = 'none';
        }, 300);
    }

    _buscarListaDeFigures() {
        const listaFigure = this._bloco.querySelectorAll(this._figure);
        listaFigure.forEach(figure => {
            this._adicionarEventoAoClicarParaAbrir(figure);
        });
    }

    _pegarDadosDaFigure(figure) {
        return new Promise(resolve => {
            if (!figure instanceof Object || !figure.getAttribute || figure.getAttribute('data-galeria-imagem') == '') {
                resolve({ status: false });
            }

            const id = figure.getAttribute('data-galeria-id') || '';
            const imagem = figure.getAttribute('data-galeria-imagem') || '';
            const titulo = figure.getAttribute('data-galeria-titulo') || '';
            const linkUrl = figure.getAttribute('data-galeria-link') || '';
            const linkTitulo = figure.getAttribute('data-galeria-link-titulo') || 'ACESSE';
            const linkTarget = figure.getAttribute('data-galeria-link-target') || '_blank';

            resolve({
                status: true,
                id,
                imagem,
                titulo,
                link: {
                    status: linkUrl != '',
                    url: linkUrl,
                    titulo: linkTitulo,
                    target: linkTarget,
                },
            });
        });
    }

    _adicionarEventoAoClicarParaAbrir(figure) {
        let botao;
        if (this._botao) {
            botao = figure.querySelector(this._botao);
        }
        if (!botao) {
            botao = figure;
        }

        figure.setAttribute('data-galeria', 1);

        const self = this;
        botao.addEventListener('click', async e => {
            const dado = await self._pegarDadosDaFigure(figure);
            if (!dado.status) {
                return;
            }
            this._figureAtual = figure;
            self._abrirImagem(dado.id, dado.imagem, dado.titulo, dado.link);
        });
    }

    _abrirImagem(id, imagem, titulo, link) {
        this._figureAberta = true;

        this._figureLista = this._bloco.querySelectorAll(this._figure);
        if (this._figureLista.length > 1) {
            this._fwAnterior.style.display = 'flex';
            this._fwProximo.style.display = 'flex';
        }

        this._setarDadosDaFigure(id, imagem, titulo, link);

        this._fwBloco.style.display = 'flex';
        setTimeout(() => {
            this._fwBloco.classList.add('fw_galeria_abrir');
        }, 20);
    }

    _setarDadosDaFigure(id, imagem, titulo, link) {
        const blocoDownload = this._fwDownload;
        if (this._download != '') {
            blocoDownload.style.display = 'flex';
            blocoDownload.setAttribute('href', this._download.replace(/\/$/, '') + '/' + id);
        } else {
            blocoDownload.style.display = 'none';
            blocoDownload.removeAttribute('href');
        }

        const blocoLink = this._fwLink;
        if (link.status) {
            blocoLink.style.display = 'flex';
            blocoLink.setAttribute('href', link.url);
            blocoLink.setAttribute('target', link.target);
            blocoLink.innerText = link.titulo;
        } else {
            blocoLink.style.display = 'none';
        }
        const blocoTitulo = this._fwTitulo;
        if (titulo != '') {
            blocoTitulo.style.display = 'block';
            blocoTitulo.innerText = titulo;
        } else {
            blocoTitulo.style.display = 'none';
        }
        this._fwImg.setAttribute('src', imagem);
    }

    async _mudarImagem(tipo) {
        if (this._transicao) {
            return;
        }

        const figureAtual = this._figureAtual;
        const figureNova = await this._pegarFigureNova(figureAtual, tipo);
        if (!figureNova || this._transicao || figureAtual == figureNova) {
            return;
        }
        this._transicao = true;
        const dado = await this._pegarDadosDaFigure(figureNova);
        if (!dado.status) {
            this._transicao = false;
            return;
        }

        this._fwFigureAnimacao.style.display = 'block';
        this._fwImgAnimacao.setAttribute('src', dado.imagem);

        if (tipo == 'anterior') {
            this._fwFigureAnimacao.classList.add('fw_galeria_animar_fake_esquerda_centro');
            this._fwFigure.classList.add('fw_galeria_animar_centro_direita');
        } else if (tipo == 'proximo') {
            this._fwFigureAnimacao.classList.add('fw_galeria_animar_fake_direita_centro');
            this._fwFigure.classList.add('fw_galeria_animar_centro_esquerda');
        }

        setTimeout(() => {
            this._setarDadosDaFigure(dado.id, dado.imagem, dado.titulo, dado.link);
            this._fwFigureAnimacao.classList.remove('fw_galeria_animar_fake_direita_centro');
            this._fwFigureAnimacao.classList.remove('fw_galeria_animar_fake_esquerda_centro');
            this._fwFigure.classList.remove('fw_galeria_animar_centro_esquerda');
            this._fwFigure.classList.remove('fw_galeria_animar_centro_direita');
            this._fwFigureAnimacao.style.display = 'none';
            this._fwImgAnimacao.removeAttribute('src');
            this._figureAtual = figureNova;
            this._transicao = false;
        }, 300);
    }

    async _pegarFigureNova(figureAtual, tipo) {
        let figureNova = figureAtual;
        if (tipo == 'anterior') {
            figureNova = figureNova.previousSibling;
            if (figureNova instanceof Object && !figureNova.getAttribute) {
                figureNova = figureNova.previousElementSibling;
            }
        } else if (tipo == 'proximo') {
            figureNova = figureNova.nextSibling;
            if (figureNova instanceof Object && !figureNova.getAttribute) {
                figureNova = figureNova.nextElementSibling;
            }
        }

        if (figureNova == null && tipo == 'anterior') {
            figureNova = this._figureLista[this._figureLista.length - 1];
        } else if (figureNova == null && tipo == 'proximo') {
            figureNova = this._figureLista[0];
        }
        if (!figureNova instanceof Object || !figureNova.getAttribute) {
            return false;
        }

        const validar = figureNova.getAttribute('data-galeria');
        if (validar != 1 && tipo == 'anterior') {
            figureNova = this._figureLista[this._figureLista.length - 1];
        } else if (validar != 1 && tipo == 'proximo') {
            figureNova = this._figureLista[0];
        }

        if (
            !figureNova instanceof Object ||
            !figureNova.getAttribute ||
            figureNova.getAttribute('data-galeria-imagem') == ''
        ) {
            return false;
        }
        return figureNova;
    }
}
