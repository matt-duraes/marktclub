// @template "site"
// @system "Esqueleto"
// @system "Banner"
// @resource "site/loja/parceiro"
// @resource "site/loja/favorito"
// @import "banner"
// @import "campanha"
// @import "relacionado"
// @import "tabela"

const paginaUrl = $('#input_url').valor();

class ComponenteBuscar {
    constructor(quantidade) {
        this.quantidade = quantidade;
        this.body = new FormData();
        this.id = 1;
        this.retorno = {};
    }

    async add(hash, tipo, dado) {
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
        this.id++;
        if (this.quantidade === this.id) {
            await new Promise(resolve => setTimeout(resolve, 1000));
            this.enviar();
        }
    }

    enviar() {
        const body = this.body;
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

    erro(numero) {
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
            }
        }
    }

    adicionarTabela(lista, dado) {
        dado = 'dado' in dado ? dado : dado;
        if ('esqueleto' in dado) {
            dado.esqueleto.hide();
        }
        dado.esqueleto.hide();
        if ('remover' in dado) {
            this.removerLista(dado.remover);
        }

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
