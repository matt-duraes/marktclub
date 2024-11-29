class Alerta {
    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR
    |--------------------------------------------------------------------------
    */
    static async confirmar(titulo, mensagem, icone, fechar, option) {
        const body = document.querySelector('body');
        const existe = await this._verificarSeMensagemJaExiste();
        if (existe) {
            await this._removerMensagemExistente(false);
        }

        this._resolveReject = '';
        this._resolveRejectInterval = undefined;
        this.option = typeof option === 'object' ? option : {};

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
    static async mensagem(titulo, mensagem, icone, fechar, option) {
        const body = document.querySelector('body');
        const existe = await this._verificarSeMensagemJaExiste();
        if (existe) {
            await this._removerMensagemExistente(false);
        }

        this._resolveReject = '';
        this._resolveRejectInterval = undefined;
        this.option = typeof option === 'object' ? option : {};

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
                classeBotaoOk = '',
                classeBotaoFlex = 'fw_alerta_mensagem_hide',
                classeBotaoCancelar = 'fw_alerta_mensagem_hide',
                classeBotaoConfirmar = 'fw_alerta_mensagem_hide',
                jsonAnimacao = '';

            if (confirmar) {
                classeConfirmar = 'fw_alerta_mensagem_bloco_confirmar';
            }
            if (true === icone || 'ok' === icone) {
                classeHeader = 'fw_alerta_mensagem_header_ok';
                jsonAnimacao =
                    '{"v":"5.5.10","fr":29.9700012207031,"ip":0,"op":137.000005580124,"w":300,"h":335,"nm":"Comp 1","ddd":0,"assets":[],"layers":[{"ddd":0,"ind":1,"ty":4,"nm":"Shape Layer 4","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[148,185.5,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":0,"k":[96.429,96.429,100],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ind":0,"ty":"sh","ix":1,"ks":{"a":0,"k":{"i":[[0,0],[0,0],[-17.136,27.185]],"o":[[13.086,7.272],[0,0],[0,0]],"v":[[-46.074,-6.315],[-6.815,15.5],[44.593,-66.056]],"c":false},"ix":2},"nm":"Path 1","mn":"ADBE Vector Shape - Group","hd":false},{"ty":"st","c":{"a":0,"k":[1,1,1,1],"ix":3},"o":{"a":0,"k":100,"ix":4},"w":{"a":0,"k":25,"ix":5},"lc":2,"lj":2,"bm":0,"nm":"Stroke 1","mn":"ADBE Vector Graphic - Stroke","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,7],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":9.672,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Shape 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false},{"ty":"tm","s":{"a":1,"k":[{"i":{"x":[0.148],"y":[1]},"o":{"x":[0.471],"y":[0.002]},"t":18,"s":[0]},{"t":38.0000015477717,"s":[100]}],"ix":1},"e":{"a":0,"k":0,"ix":2},"o":{"a":0,"k":0,"ix":3},"m":1,"ix":2,"nm":"Trim Paths 1","mn":"ADBE Vector Filter - Trim","hd":false}],"ip":18.000000733155,"op":396.000016129411,"st":18.000000733155,"bm":0},{"ddd":0,"ind":2,"ty":4,"nm":"Shape Layer 3","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":7,"s":[0,0,100]},{"t":25.0000010182709,"s":[78.151,78.151,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.054901960784313725,0.5450980392156862,0.49411764705882355,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":7.00000028511585,"op":385.000015681372,"st":7.00000028511585,"bm":0},{"ddd":0,"ind":3,"ty":4,"nm":"Shape Layer 2","sr":1,"ks":{"o":{"a":0,"k":20,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":3,"s":[0,0,100]},{"t":21.0000008553475,"s":[96.639,96.639,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.054901960784313725,0.5450980392156862,0.49411764705882355,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":3.00000012219251,"op":381.000015518448,"st":3.00000012219251,"bm":0},{"ddd":0,"ind":4,"ty":4,"nm":"Shape Layer 1","sr":1,"ks":{"o":{"a":0,"k":10,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":0,"s":[0,0,100]},{"t":18.000000733155,"s":[110.084,110.084,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.054901960784313725,0.5450980392156862,0.49411764705882355,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":378.000015396256,"st":0,"bm":0}],"markers":[{"tm":40.0000016292334,"cm":"","dr":0}]}';
            }
            if (false === icone || 'erro' === icone) {
                classeHeader = 'fw_alerta_mensagem_header_erro';
                jsonAnimacao =
                    '{"v":"5.5.10","fr":30,"ip":0,"op":75,"w":300,"h":335,"nm":"Comp 1","ddd":0,"assets":[],"layers":[{"ddd":0,"ind":1,"ty":4,"nm":"Shape Layer 9","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":1,"k":[{"i":{"x":[0.413],"y":[0.987]},"o":{"x":[0.333],"y":[0]},"t":38.038,"s":[90]},{"i":{"x":[0.833],"y":[1]},"o":{"x":[0.167],"y":[0]},"t":49.049,"s":[35]},{"t":52.0517578125,"s":[45]}],"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-7.75,0.125,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":28.028,"s":[181.14,0,100]},{"t":38.0380859375,"s":[181.14,110.579,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ty":"rc","d":1,"s":{"a":0,"k":[12,108.75],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"r":{"a":0,"k":0,"ix":4},"nm":"Rectangle Path 1","mn":"ADBE Vector Shape - Rect","hd":false},{"ty":"fl","c":{"a":0,"k":[1,1,1,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-7.75,0.125],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,97.166],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Rectangle 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":75.0750750750751,"st":0,"bm":0},{"ddd":0,"ind":2,"ty":4,"nm":"Shape Layer 8","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":1,"k":[{"i":{"x":[0.413],"y":[0.995]},"o":{"x":[0.333],"y":[0]},"t":38.038,"s":[90]},{"i":{"x":[0.833],"y":[1]},"o":{"x":[0.167],"y":[0]},"t":49.049,"s":[-55]},{"t":52.0517578125,"s":[-45]}],"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-7.75,0.125,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":28.028,"s":[181.14,0,100]},{"t":38.0380859375,"s":[181.14,110.579,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ty":"rc","d":1,"s":{"a":0,"k":[12,108.75],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"r":{"a":0,"k":0,"ix":4},"nm":"Rectangle Path 1","mn":"ADBE Vector Shape - Rect","hd":false},{"ty":"fl","c":{"a":0,"k":[1,1,1,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-7.75,0.125],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,97.166],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Rectangle 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":75.0750750750751,"st":0,"bm":0},{"ddd":0,"ind":3,"ty":4,"nm":"Shape Layer 10","sr":1,"ks":{"o":{"a":0,"k":60,"ix":11},"r":{"a":1,"k":[{"i":{"x":[0.413],"y":[0.995]},"o":{"x":[0.333],"y":[0]},"t":40.04,"s":[90]},{"i":{"x":[0.833],"y":[1]},"o":{"x":[0.167],"y":[0]},"t":51.051,"s":[-55]},{"t":54.053759814502,"s":[-45]}],"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-7.75,0.125,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":30.03,"s":[181.14,0,100]},{"t":40.040087939502,"s":[181.14,110.579,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ty":"rc","d":1,"s":{"a":0,"k":[12,108.75],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"r":{"a":0,"k":0,"ix":4},"nm":"Rectangle Path 1","mn":"ADBE Vector Shape - Rect","hd":false},{"ty":"fl","c":{"a":0,"k":[1,1,1,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-7.75,0.125],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,97.166],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Rectangle 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":2.002002002002,"op":75.0750750750751,"st":2.002002002002,"bm":0},{"ddd":0,"ind":4,"ty":4,"nm":"Shape Layer 11","sr":1,"ks":{"o":{"a":0,"k":20,"ix":11},"r":{"a":1,"k":[{"i":{"x":[0.413],"y":[0.995]},"o":{"x":[0.333],"y":[0]},"t":41.041,"s":[90]},{"i":{"x":[0.833],"y":[1]},"o":{"x":[0.167],"y":[0]},"t":52.052,"s":[-55]},{"t":55.054760815503,"s":[-45]}],"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-7.75,0.125,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":31.031,"s":[181.14,0,100]},{"t":41.041088940503,"s":[181.14,110.579,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ty":"rc","d":1,"s":{"a":0,"k":[12,108.75],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"r":{"a":0,"k":0,"ix":4},"nm":"Rectangle Path 1","mn":"ADBE Vector Shape - Rect","hd":false},{"ty":"fl","c":{"a":0,"k":[1,1,1,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-7.75,0.125],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,97.166],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Rectangle 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":3.003003003003,"op":75.0750750750751,"st":3.003003003003,"bm":0},{"ddd":0,"ind":5,"ty":4,"nm":"Shape Layer 3","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":7.007,"s":[0,0,100]},{"t":25.024585132007,"s":[78.151,78.151,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.7215686274509804,0.17254901960784313,0.12156862745098039,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":7.00700700700701,"op":75.0750750750751,"st":7.00700700700701,"bm":0},{"ddd":0,"ind":6,"ty":4,"nm":"Shape Layer 2","sr":1,"ks":{"o":{"a":0,"k":20,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":3.003,"s":[0,0,100]},{"t":21.020581128003,"s":[96.639,96.639,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.7215686274509804,0.17254901960784313,0.12156862745098039,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":3.003003003003,"op":75.0750750750751,"st":3.003003003003,"bm":0},{"ddd":0,"ind":7,"ty":4,"nm":"Shape Layer 1","sr":1,"ks":{"o":{"a":0,"k":10,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":0,"s":[0,0,100]},{"t":18.017578125,"s":[110.084,110.084,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.7215686274509804,0.17254901960784313,0.12156862745098039,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":75.0750750750751,"st":0,"bm":0}],"markers":[{"tm":40.0400390625,"cm":"","dr":0}]}';
            }
            if ('!' === icone) {
                classeHeader = 'fw_alerta_mensagem_header_atencao';
                jsonAnimacao =
                    '{"v":"5.5.10","fr":29.9700012207031,"ip":0,"op":75.0000030548126,"w":300,"h":335,"nm":"Comp 1","ddd":0,"assets":[],"layers":[{"ddd":0,"ind":1,"ty":4,"nm":"Shape Layer 6","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":1,"k":[{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":25,"s":[148,190.571,0],"to":[0,1.667,0],"ti":[0,-1.667,0]},{"t":32.0000013033867,"s":[148,200.571,0]}],"ix":2},"a":{"a":0,"k":[0,31.75,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.833,0.833,0.833],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":25,"s":[0,0,100]},{"i":{"x":[0.833,0.833,0.833],"y":[1,1,1]},"o":{"x":[0.167,0.167,0.167],"y":[0,0,0]},"t":32,"s":[119.25,136.286,100]},{"i":{"x":[0.833,0.833,0.833],"y":[1,1,1]},"o":{"x":[0.167,0.167,0.167],"y":[0,0,0]},"t":34,"s":[86,98.286,100]},{"t":36.0000014663101,"s":[100,114.286,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[20,20],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[1,1,1,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[0,40.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":25.0000010182709,"op":372.000015151871,"st":-6.00000024438501,"bm":0},{"ddd":0,"ind":2,"ty":4,"nm":"Shape Layer 5","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[149,187.5,0],"ix":2},"a":{"a":0,"k":[0,78.156,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.833,0.833,0.833],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":19,"s":[52.459,2.459,100]},{"i":{"x":[0.833,0.833,0.833],"y":[1,1,1]},"o":{"x":[0.167,0.167,0.167],"y":[0,0,0]},"t":27,"s":[52.459,61.459,100]},{"i":{"x":[0.833,0.833,0.833],"y":[1,1,1]},"o":{"x":[0.167,0.167,0.167],"y":[0,0,0]},"t":30.5,"s":[52.459,45.459,100]},{"t":34.0000013848484,"s":[52.459,52.459,100]}],"ix":6}},"ao":0,"hasMask":true,"masksProperties":[{"inv":false,"mode":"f","pt":{"a":0,"k":{"i":[[-3.805,13.022],[-0.092,37.927],[0,-49.691],[-4.587,-15.298]],"o":[[3.996,-13.582],[0,-49.932],[0,37.689],[4.06,13.26]],"v":[[10.277,74.31],[31.587,-36.638],[-30.587,-36.638],[-9.624,74.31]],"c":true},"ix":1},"o":{"a":0,"k":100,"ix":3},"x":{"a":0,"k":0,"ix":4},"nm":"Mask 1"}],"shapes":[{"ty":"gr","it":[{"ty":"rc","d":1,"s":{"a":0,"k":[234,260],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"r":{"a":0,"k":0,"ix":4},"nm":"Rectangle Path 1","mn":"ADBE Vector Shape - Rect","hd":false},{"ty":"fl","c":{"a":0,"k":[1,1,1,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[5,1.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Rectangle 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":19.0000007738859,"op":374.000015233332,"st":-4.00000016292334,"bm":0},{"ddd":0,"ind":5,"ty":4,"nm":"Shape Layer 3","sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":7,"s":[0,0,100]},{"t":25.0000010182709,"s":[78.151,78.151,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.788235294117647,0.36470588235294116,0.08627450980392157,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":7.00000028511585,"op":385.000015681372,"st":7.00000028511585,"bm":0},{"ddd":0,"ind":6,"ty":4,"nm":"Shape Layer 2","sr":1,"ks":{"o":{"a":0,"k":20,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":3,"s":[0,0,100]},{"t":21.0000008553475,"s":[96.639,96.639,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.788235294117647,0.36470588235294116,0.08627450980392157,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":3.00000012219251,"op":381.000015518448,"st":3.00000012219251,"bm":0},{"ddd":0,"ind":7,"ty":4,"nm":"Shape Layer 1","sr":1,"ks":{"o":{"a":0,"k":10,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[150,167,0],"ix":2},"a":{"a":0,"k":[-4,-8.5,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.471,0.471,0.667],"y":[1,1,1]},"o":{"x":[0.48,0.48,0.333],"y":[0,0,0]},"t":0,"s":[0,0,100]},{"t":18.000000733155,"s":[110.084,110.084,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"d":1,"ty":"el","s":{"a":0,"k":[238,238],"ix":2},"p":{"a":0,"k":[0,0],"ix":3},"nm":"Ellipse Path 1","mn":"ADBE Vector Shape - Ellipse","hd":false},{"ty":"fl","c":{"a":0,"k":[0.788235294117647,0.36470588235294116,0.08627450980392157,1],"ix":4},"o":{"a":0,"k":100,"ix":5},"r":1,"bm":0,"nm":"Fill 1","mn":"ADBE Vector Graphic - Fill","hd":false},{"ty":"tr","p":{"a":0,"k":[-4,-8.5],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Ellipse 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":378.000015396256,"st":0,"bm":0}],"markers":[{"tm":40.0000016292334,"cm":"","dr":0}]}';
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

            const botaoCancelarTexto = this.option.botaoCancelarTexto || 'Cancelar';
            let botaoCancelarStyle = {};
            if (this.option.botaoCancelarBg) {
                botaoCancelarStyle['background-color'] = this.option.botaoCancelarBg + '!important';
            }
            if (this.option.botaoCancelarColor) {
                botaoCancelarStyle['color'] = this.option.botaoCancelarColor + '!important';
            }

            const botaoConfirmarTexto = this.option.botaoConfirmarTexto || 'Confirmar';
            let botaoConfirmarStyle = {};
            if (this.option.botaoConfirmarBg) {
                botaoConfirmarStyle['background-color'] = this.option.botaoConfirmarBg + '!important';
            }
            if (this.option.botaoConfirmarColor) {
                botaoConfirmarStyle['color'] = this.option.botaoConfirmarColor + '!important';
            }

            body.inicio(
                `
                    <div id="fw_alerta_mensagem" class="${classeConfirmar}">
                        <div class="fw_alerta_mensagem_conteudo">
                            <div class="fw_alerta_mensagem_header ${classeHeader}">
                                <div class="fw_alerta_mensagem_icone">
                                    <lottie-player class="fw_alerta_player" background="transparent" speed="1" autoplay>
                                    </lottie-player>
                                </div>
                                <div class="fw_alerta_mensagem_titulo ${classeTitulo}">${titulo}</div>
                            </div>
                            <div class="fw_alerta_mensagem_texto">${mensagem}</div>
                            <div class="fw_alerta_mensagem_footer">
                                <div class="fw_alerta_mensagem_botao fw_alerta_mensagem_botao_ok ${classeBotaoOk}">OK</div>
                                <div class="fw_alerta_mensagem_botao fw_alerta_mensagem_botao_cancelar ${classeBotaoCancelar}">${botaoCancelarTexto}</div>
                                <div class="fw_alerta_mensagem_botao_flex ${classeBotaoFlex}"></div>
                                <div class="fw_alerta_mensagem_botao fw_alerta_mensagem_botao_confirmar ${classeBotaoConfirmar}">${botaoConfirmarTexto}</div>
                            </div>
                        </div>
                    </div>
                `
            );
            if (botaoCancelarStyle.contar() > 0) {
                $('#fw_alerta_mensagem .fw_alerta_mensagem_botao_cancelar').css(botaoCancelarStyle);
            }
            if (botaoConfirmarStyle.contar() > 0) {
                $('#fw_alerta_mensagem .fw_alerta_mensagem_botao_confirmar').css(botaoConfirmarStyle);
            }

            const bloco = body.querySelector('#fw_alerta_mensagem');
            const blocoAnimacao = $('.fw_alerta_player', bloco);
            setTimeout(() => {
                bloco.classList.add('fw_alerta_mensagem_abrir');
                if (jsonAnimacao != '') {
                    const animationData = JSON.parse(jsonAnimacao);
                    lottie.loadAnimation({
                        container: blocoAnimacao,
                        renderer: 'svg',
                        loop: false,
                        autoplay: true,
                        animationData: animationData,
                    });
                }
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
        const lista = body.querySelectorAll('.fw_alerta_notificacao_abrir');
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
            body.inicio(
                `
                <div class="fw_alerta_notificacao ${blocoClasse}" id="${id}">
                    ${iconeHtml}
                    <div class="fw_alerta_notificacao_p">${mensagem}</div>
                    <div class="fw_alerta_notificacao_fechar"><svg height="15" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></div>
                    <div class="fw_alerta_notificacao_barra"></div>
                </div>
            `
            );

            const bloco = $(`#${id}`);
            bloco.css('top', `${topo}px`);
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
                    this._animacaoNotificacaoFechar(notificacao);
                    resolve(true);
                }
            }, 100);

            botaoFechar.addEventListener('click', () => {
                this._animacaoNotificacaoFechar(notificacao);
            });
        });
    }
    static _animacaoNotificacaoFechar(notificacao) {
        notificacao.classList.remove('fw_alerta_notificacao_abrir');
        setTimeout(() => {
            notificacao.parentNode.removeChild(notificacao);
        }, 500);
        this._reposicionarOutrosAlertas(notificacao);
    }
    static _reposicionarOutrosAlertas(itemRemovido) {
        const lista = Array.from($$('.fw_alerta_notificacao_abrir'));
        if (lista.length < 1) {
            return;
        }
        const resto = lista.filter(lista => lista !== itemRemovido).reverse();
        let topo = 20;
        resto.forEach(item => {
            item.css('top', `${topo}px`);
            topo += parseInt(item.getBoundingClientRect().height) + 20;
        });
    }

    static _iconeErro() {
        return `
            <div class="fw_alerta_notificacao_icone">
                <svg width="18px" height="18px" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" xml:space="preserve">
                    <path d="M20,0c-2.4,0-4.3,1.9-4.3,4.3l1.1,19.5c0,1.8,1.5,3.2,3.2,3.2c1.8,0,3.2-1.5,3.2-3.2l1.1-19.5C24.3,1.9,22.4,0,20,0L20,0z"/>
                    <circle cx="20" cy="34.6" r="5.4"/>
                </svg>
            </div>
        `;
    }
    static _iconeOk() {
        return `
            <div class="fw_alerta_notificacao_icone">
                <svg width="14px" height="14px" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" xml:space="preserve">
                    <g transform="translate(0,-952.36218)">
                        <path d="M36.6,958c-0.9,0-1.7,0.4-2.3,1c-0.7,0.7-5.7,5.8-10.8,11.1c-4.2,4.3-7.1,7.3-8.7,8.9L5.5,972c-1.4-1.2-3.5-1.1-4.7,0.3 c-1.2,1.4-1.1,3.5,0.3,4.7c0.1,0.1,0.2,0.2,0.3,0.3l11.6,8.8c1.3,1,3.2,0.9,4.4-0.3c0.7-0.7,5.7-5.8,10.8-11.1 c5.1-5.3,10.4-10.7,10.7-11c1.3-1.3,1.3-3.4,0-4.7C38.4,958.3,37.5,958,36.6,958L36.6,958z"/>
                    </g>
                </svg>
            </div>
        `;
    }
}
