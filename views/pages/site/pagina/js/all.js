// @template "site"
// @system "Esqueleto"
// @system "Banner"
// @resource "site/loja/parceiro"
// @resource "site/loja/favorito"
// @import "banner"
// @import "campanha"
// @import "relacionado"
// @import "tabela"
// @import "loja"

const paginaUrl = $('#input_url').valor();
const paginaDado = $('#input_dado').valor();

class ComponenteBuscar {
    constructor(quantidade) {
        this.quantidade = quantidade;
        this.body = new FormData();
        this.id = 1;
        this.retorno = {};
    }

    add(hash, tipo, dado) {
        const id = 'id-' + this.id;
        this.body.append(
            'hash[]',
            JSON.stringify({
                hash,
                id,
                tipo,
            })
        );
        this.retorno[id] = {
            tipo,
            dado,
        };
        if (this.quantidade === this.id) {
            this.enviar();
            return;
        }
        this.id++;
    }

    enviar() {
        const body = this.body;
        body.append('dado', paginaDado);
        fetch(LINK + '/componente', {
            method: 'POST',
            body,
        })
            .then(resposta => {
                const json = resposta.json();
                json.then(resposta => {
                    if ('status' in resposta && resposta.status === 'sucesso') {
                        this.executarRetorno(resposta.dado);
                        return;
                    }
                    this.erro(1);
                }).catch(() => {
                    this.erro(2);
                });
            })
            .catch(() => {
                this.erro(3);
            });
    }

    erro() {
        const dado = this.retorno;
        for (let i = 1; i <= this.quantidade; ++i) {
            const id = 'id-' + i;
            const item = dado[id];
            if ('dado' in item && 'esqueleto' in item.dado) {
                item.dado.esqueleto.hide();
            }
            if ('dado' in item && 'remover' in item.dado) {
                this.removerLista(item.dado.remover);
            }
        }
    }

    removerLista(lista) {
        if (lista instanceof Element) {
            lista.remove();
            return;
        } else if (!lista instanceof NodeList && !lista instanceof Array) {
            return;
        }
        for (const item of lista) {
            item.remove();
        }
    }

    executarRetorno(resposta) {
        for (const item of resposta) {
            const id = item.id;
            const retorno = this.retorno[id];
            const tipo = retorno.tipo;
            const dado = retorno.dado;
            if (tipo === 'tabela') {
                this.adicionarTabela(item.dado, dado);
            } else if (tipo === 'loja') {
                this.adicionarLoja(item.dado, dado);
            }
        }
    }

    tratarItemDado(dado) {
        dado = 'dado' in dado ? dado : dado;
        if ('esqueleto' in dado) {
            dado.esqueleto.hide();
        }
        if ('remover' in dado) {
            this.removerLista(dado.remover);
        }
        return dado;
    }

    adicionarLoja(lista, dado) {
        dado = this.tratarItemDado(dado);
        const fake = $$('.article_fake', dado.bloco);
        for (const remover of fake) {
            remover.remove();
        }

        for (const item of lista) {
            if (!'tipo_loja' in item) {
                continue;
            }

            const tipo = item.tipo_loja;
            const clone = dado.modelo.clonar();
            clone.classe('padrao_loja', false);
            clone.setAttribute('data-url', item.id);
            $('.item_titulo', clone).html(item.titulo);

            if (!vazio(item.link)) {
                $('.item_link', clone).setAttribute('href', item.link);
            }
            if (!vazio(item.imagem)) {
                $('.item_logo', clone).html(`<img src="${item.imagem}" alt="">`);
            }
            if (!vazio(item.desconto)) {
                $('.item_desconto', clone).html(tipo === 'cashback' ? item.desconto + '%' : item.desconto);
            }
            if (tipo === 'cashback') {
                $('.item_pontos', clone).text('Revertido em pontos SILIUM');
                $('.item_volta', clone).text('Receba de volta');
            }
            if (!vazio(item.estado) && !['cashback', 'plano-saude'].includes(tipo)) {
                $('.bloco_estado', clone).classe('display_none', false);
                $('.item_estado', clone).texto(item.estado);
            }

            // Favorito
            if (!['plano-saude', 'cashback'].includes(tipo)) {
                const favorito = $('.botao_favorito', clone);
                favorito.classe('display_none', false);
                if (item.favorito === 'sim') {
                    favorito.classe('favorito_marcado', true);
                }
            }

            dado.bloco.final(clone);
        }

        dado.bloco.final(
            `<div class="article_fake"></div><div class="article_fake"></div><div class="article_fake"></div>`
        );
    }

    adicionarTabela(lista, dado) {
        dado = this.tratarItemDado(dado);

        if (lista.length === 0) {
            dado.tabela.closest('.com_tabela_scroll').depois(`
                <div class="com_pai_tabela_erro">
                    <span class="lang_br">Ocorreu um erro ao buscar lista da tabela</span>
                    <span class="lang_en">Ocorreu um erro ao buscar lista da tabela</span>
                    <span class="lang_es">Ocorreu um erro ao buscar lista da tabela</span>
                </div>
            `);
            return;
        }

        for (const linha of lista) {
            const tr = dado.modelo.clonar();
            const td = $$('.td', tr);
            linha.forEach((valor, i) => {
                const br = valor.br || '';
                const en = 'en' in valor && !vazio(valor.en) ? valor.en : br;
                const es = 'es' in valor && !vazio(valor.es) ? valor.es : br;
                td[i].html(`
                    <span class="lang_br">${br}</span>
                    <span class="lang_en">${en}</span>
                    <span class="lang_es">${es}</span>
                `);
            });
            dado.tabela.final(tr);
        }
    }
}

const Buscar = new ComponenteBuscar($$('.com_api_geral').length);
