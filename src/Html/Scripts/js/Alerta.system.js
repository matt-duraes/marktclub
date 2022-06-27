class Alerta {
    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR
    |--------------------------------------------------------------------------
    */
    static async confirmar(titulo, mensagem, icone, fechar) {
        const body = document.querySelector('body');
        const existe = await this._verificarSeMensagemJaExiste();
        if (existe) {
            await this._removerMensagemExistente(false);
        }

        this._resolveReject = '';
        this._resolveRejectInterval = undefined;

        fechar = fechar == undefined ? true : fechar;

        const blocoMensagem = await this._carregarHtmlDeMensagem(body, titulo, mensagem, icone, true);
        await this._adicionarEventosNaMensagem(blocoMensagem, fechar, true);
        if (!existe) {
            await this._adicionarKeyDownNaMensagem(body);
        }
        return await this._aguardarRetornoDaMensagem(true, titulo);
    }
    /*
    |--------------------------------------------------------------------------
    | MENSAGEM
    |--------------------------------------------------------------------------
    */
    static async mensagem(titulo, mensagem, icone, fechar) {
        const body = document.querySelector('body');
        const existe = await this._verificarSeMensagemJaExiste();
        if (existe) {
            await this._removerMensagemExistente(false);
        }

        this._resolveReject = '';
        this._resolveRejectInterval = undefined;

        fechar = fechar == undefined ? true : fechar;

        const blocoMensagem = await this._carregarHtmlDeMensagem(body, titulo, mensagem, icone, false);
        await this._adicionarEventosNaMensagem(blocoMensagem, fechar, false);
        if (!existe) {
            await this._adicionarKeyDownNaMensagem(body);
        }
        return await this._aguardarRetornoDaMensagem(false, titulo);
    }

    static _aguardarRetornoDaMensagem(confirmar) {
        const self = this;
        return new Promise(resolve => {
            self._resolveRejectInterval = setInterval(() => {
                if ((self._resolveReject == 'resolve' || self._resolveReject == 'reject') && !confirmar) {
                    clearInterval(self._resolveRejectInterval);
                    self._resolveReject = '';
                    resolve(true);
                    return;
                } else if (self._resolveReject == 'resolve') {
                    clearInterval(self._resolveRejectInterval);
                    self._resolveReject = '';
                    resolve(true);
                } else if (self._resolveReject == 'reject') {
                    clearInterval(self._resolveRejectInterval);
                    self._resolveReject = '';
                    resolve(false);
                    return;
                }
            }, 100);
        });
    }

    static _verificarSeMensagemJaExiste() {
        return new Promise(async resolve => {
            resolve(document.querySelector('#fw_alerta_mensagem'));
        });
    }

    static _removerMensagemExistente(confirmar) {
        const self = this;
        return new Promise(async resolve => {
            if (self._resolveRejectInterval) {
                clearInterval(self._resolveRejectInterval);
            }
            const bloco = document.querySelector('#fw_alerta_mensagem');
            bloco.classList.remove('fw_alerta_mensagem_abrir');
            await self._sleep(500);
            bloco.parentNode.removeChild(bloco);
            resolve(true);
        });
    }

    static _sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    static _carregarHtmlDeMensagem(body, titulo, mensagem, icone, confirmar) {
        return new Promise(resolve => {
            let classeConfirmar = '',
                classeHeader = '',
                classeTitulo = 'fw_alerta_mensagem_hide',
                classeIconeOk = 'fw_alerta_mensagem_hide',
                classeIconeErro = 'fw_alerta_mensagem_hide',
                classeIconeAtencao = 'fw_alerta_mensagem_hide',
                classeBotaoOk = '',
                classeBotaoFlex = 'fw_alerta_mensagem_hide',
                classeBotaoCancelar = 'fw_alerta_mensagem_hide',
                classeBotaoConfirmar = 'fw_alerta_mensagem_hide';

            if (confirmar) {
                classeConfirmar = 'fw_alerta_mensagem_bloco_confirmar';
            }
            if (true === icone || 'ok' === icone) {
                classeHeader = 'fw_alerta_mensagem_header_ok';
                classeIconeOk = '';
            }
            if (false === icone || 'erro' === icone) {
                classeHeader = 'fw_alerta_mensagem_header_erro';
                classeIconeErro = '';
            }
            if ('!' === icone) {
                classeHeader = 'fw_alerta_mensagem_header_atencao';
                classeIconeAtencao = '';
            }
            if (titulo != '' && titulo != undefined) {
                classeTitulo = '';
            }
            if (confirmar) {
                classeBotaoOk = 'fw_alerta_mensagem_hide';
                classeBotaoCancelar = '';
                classeBotaoConfirmar = '';
                classeBotaoFlex = '';
            }

            body.insertAdjacentHTML(
                'beforeend',
                `
                    <div id="fw_alerta_mensagem" class="${classeConfirmar}">
                        <div class="fw_alerta_mensagem_conteudo">
                            <div class="fw_alerta_mensagem_header ${classeHeader}">
                                <div class="fw_alerta_mensagem_icone fw_alerta_mensagem_icone_ok ${classeIconeOk}">
                                    <lottie-player class="fw_alerta_player" src="/images/plugins/alerta/ok.json" background="transparent" speed="1" autoplay>
                                    </lottie-player>
                                </div>
                                <div class="fw_alerta_mensagem_icone fw_alerta_mensagem_icone_erro ${classeIconeErro}">
                                    <lottie-player class="fw_alerta_player" src="/images/plugins/alerta/erro.json" background="transparent" speed="1" autoplay>
                                    </lottie-player>
                                </div>
                                <div class="fw_alerta_mensagem_icone fw_alerta_mensagem_icone_atencao ${classeIconeAtencao}">
                                    <lottie-player class="fw_alerta_player" src="/images/plugins/alerta/atencao.json" background="transparent" speed="1" autoplay>
                                    </lottie-player>
                                </div>

                                <div class="fw_alerta_mensagem_titulo ${classeTitulo}">${titulo}</div>
                            </div>
                            <div class="fw_alerta_mensagem_texto">${mensagem}</div>
                            <div class="fw_alerta_mensagem_footer">
                                <div class="fw_alerta_mensagem_botao fw_alerta_mensagem_botao_ok ${classeBotaoOk}">OK</div>
                                <div class="fw_alerta_mensagem_botao fw_alerta_mensagem_botao_cancelar ${classeBotaoCancelar}">Cancelar</div>
                                <div class="fw_alerta_mensagem_botao_flex ${classeBotaoFlex}"></div>
                                <div class="fw_alerta_mensagem_botao fw_alerta_mensagem_botao_confirmar ${classeBotaoConfirmar}">Confirmar</div>
                            </div>
                        </div>
                    </div>
                `
            );

            const bloco = body.querySelector('#fw_alerta_mensagem');
            setTimeout(() => {
                bloco.classList.add('fw_alerta_mensagem_abrir');
            }, 20);
            setTimeout(() => {
                bloco.classList.add('fw_alerta_mensagem_aberto');
            }, 800);

            resolve(bloco);
        });
    }

    static _adicionarEventosNaMensagem(bloco, fechar, confirmar) {
        const self = this;
        return new Promise(resolve => {
            const botaoOk = bloco.querySelector('.fw_alerta_mensagem_botao_ok');
            const botaoCancelar = bloco.querySelector('.fw_alerta_mensagem_botao_cancelar');
            const botaoConfirmar = bloco.querySelector('.fw_alerta_mensagem_botao_confirmar');

            botaoOk.addEventListener('click', () => {
                self._fecharMensagem(bloco, 'resolve');
            });
            botaoCancelar.addEventListener('click', () => {
                self._fecharMensagem(bloco, 'reject');
            });
            botaoConfirmar.addEventListener('click', () => {
                self._fecharMensagem(bloco, 'resolve');
            });
            bloco.addEventListener('click', e => {
                if (e.target.getAttribute('id') == 'fw_alerta_mensagem' && fechar) {
                    if (confirmar) {
                        self._fecharMensagem(bloco, 'reject');
                        return;
                    }
                    self._fecharMensagem(bloco, 'resolve');
                }
            });
            resolve(true);
        });
    }
    static _adicionarKeyDownNaMensagem(body) {
        const self = this;
        return new Promise(resolve => {
            body.addEventListener('keydown', e => {
                const bloco = document.querySelector('#fw_alerta_mensagem');
                if (!bloco) {
                    return;
                }
                const confirmar = bloco.classList.contains('fw_alerta_mensagem_bloco_confirmar');
                const tecla = e.key;
                if (tecla == 'Escape' && confirmar) {
                    self._fecharMensagem(bloco, 'reject');
                } else if ((tecla == 'Escape' && !confirmar) || tecla == 'Enter') {
                    self._fecharMensagem(bloco, 'resolve');
                }
            });
            resolve(true);
        });
    }

    static _fecharMensagem(bloco, retorno) {
        if (!bloco || !bloco.classList.contains('fw_alerta_mensagem_aberto')) {
            return;
        }
        bloco.classList.remove('fw_alerta_mensagem_aberto');
        bloco.classList.remove('fw_alerta_mensagem_abrir');
        this._resolveReject = retorno;
        setTimeout(() => {
            bloco.style.display = 'none';
        }, 500);
    }

    /*
    |--------------------------------------------------------------------------
    | NOTIFICACAO
    |--------------------------------------------------------------------------
    */
    static async notificacao(mensagem, icone) {
        const body = document.querySelector('body');
        const topo = await this._pegarPosicaoDoTopoDaNotificacao(body);
        const blocoNotificacao = await this._carregarHtmlDeNotificacao(body, topo, mensagem, icone);
        return await this._adicionarEventosNaNotificacao(blocoNotificacao);
    }

    static async _pegarPosicaoDoTopoDaNotificacao(body) {
        const lista = body.querySelectorAll('.fw_alerta_notificacao');
        if (lista.length == 0) {
            return 20;
        }
        let topo = 20;
        lista.forEach(notificacao => {
            topo += parseInt(notificacao.getBoundingClientRect().height) + 20;
        });
        return topo;
    }
    static async _carregarHtmlDeNotificacao(body, topo, mensagem, icone) {
        const self = this;
        return new Promise(resolve => {
            let iconeHtml = '';
            let blocoClasse = '';
            if (true === icone) {
                blocoClasse = 'fw_alerta_notificacao_ok';
                iconeHtml = this._iconeOk();
            } else if (false === icone) {
                blocoClasse = 'fw_alerta_notificacao_erro';
                iconeHtml = this._iconeErro();
            }
            const id = self._gerarIdAleatorio();
            body.insertAdjacentHTML(
                'beforeend',
                `
                <div style="top: ${topo}px" class="fw_alerta_notificacao ${blocoClasse}" id="${id}">
                    ${iconeHtml}
                    <div class="fw_alerta_notificacao_p">${mensagem}</div>
                    <div class="fw_alerta_notificacao_fechar"><svg height="15" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></div>
                    <div class="fw_alerta_notificacao_barra"></div>
                </div>
            `
            );
            const bloco = body.querySelector('#' + id);
            setTimeout(() => {
                bloco.classList.add('fw_alerta_notificacao_abrir');
            }, 20);
            resolve(bloco);
        });
    }

    static _gerarIdAleatorio() {
        const id = 'id_notificacao_alerta_' + Math.floor(Math.random() * 65536);
        if (document.querySelector('#' + id)) {
            return this._gerarIdAleatorio();
        }
        return id;
    }

    static _adicionarEventosNaNotificacao(notificacao) {
        return new Promise(resolve => {
            const botaoFechar = notificacao.querySelector('.fw_alerta_notificacao_fechar');
            const blocoWidth = parseInt(notificacao.getBoundingClientRect().width);
            const barra = notificacao.querySelector('.fw_alerta_notificacao_barra');
            let tamanho;
            const fwNotificacaoIntervalo = setInterval(() => {
                tamanho = parseInt(window.getComputedStyle(barra).getPropertyValue('width'));
                if (isNaN(tamanho)) {
                    clearInterval(fwNotificacaoIntervalo);
                } else if (tamanho >= blocoWidth) {
                    clearInterval(fwNotificacaoIntervalo);
                    notificacao.classList.remove('fw_alerta_notificacao_abrir');
                    setTimeout(() => {
                        notificacao.parentNode.removeChild(notificacao);
                    }, 500);
                    resolve(true);
                }
            }, 100);

            botaoFechar.addEventListener('click', () => {
                notificacao.classList.remove('fw_alerta_notificacao_abrir');
                setTimeout(() => {
                    notificacao.parentNode.removeChild(notificacao);
                }, 500);
                resolve(true);
            });
        });
    }

    static _iconeErro() {
        return `
            <div class="fw_alerta_notificacao_icone">
                <svg width="18px" height="18px" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
                    <path d="M20,0c-2.4,0-4.3,1.9-4.3,4.3l1.1,19.5c0,1.8,1.5,3.2,3.2,3.2c1.8,0,3.2-1.5,3.2-3.2l1.1-19.5C24.3,1.9,22.4,0,20,0L20,0z"/>
                    <circle cx="20" cy="34.6" r="5.4"/>
                </svg>
            </div>
        `;
    }
    static _iconeOk() {
        return `
            <div class="fw_alerta_notificacao_icone">
                <svg width="14px" height="14px" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
                    <g transform="translate(0,-952.36218)">
                        <path d="M36.6,958c-0.9,0-1.7,0.4-2.3,1c-0.7,0.7-5.7,5.8-10.8,11.1c-4.2,4.3-7.1,7.3-8.7,8.9L5.5,972c-1.4-1.2-3.5-1.1-4.7,0.3 c-1.2,1.4-1.1,3.5,0.3,4.7c0.1,0.1,0.2,0.2,0.3,0.3l11.6,8.8c1.3,1,3.2,0.9,4.4-0.3c0.7-0.7,5.7-5.8,10.8-11.1 c5.1-5.3,10.4-10.7,10.7-11c1.3-1.3,1.3-3.4,0-4.7C38.4,958.3,37.5,958,36.6,958L36.6,958z"/>
                    </g>
                </svg>
            </div>
        `;
    }
}
