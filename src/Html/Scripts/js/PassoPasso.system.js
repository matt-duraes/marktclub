class PassoPasso {
    constructor(conteudo, progresso) {
        this.conteudo = $$(conteudo);
        this.conteudoQuantidade = this.conteudo.length;
        const classe = conteudo.split(' ');
        this.conteudoClasse = classe[classe.length - 1];
        this.montarProgesso(progresso);
        this.montarConteudo();
    }

    montarProgesso(progresso) {
        const bloco = $(progresso);
        const quantidade = this.conteudoQuantidade;
        let i = 1;
        for (; i <= quantidade; ++i) {
            const titulo = this.conteudo[i - 1].attr('data-titulo');
            const tituloHtml = !vazio(titulo) ? `<p>${titulo}</p>` : '';
            let linhaHtml = '<div class="linha_esquerda"></div><div class="linha_direita"></div>';
            if (i == 1) {
                linhaHtml = `<div class="linha_direita"></div>`;
            } else if (i == quantidade) {
                linhaHtml = `<div class="linha_esquerda"></div>`;
            }
            const classe = i == 1 ? 'atual' : '';

            bloco.final(`
                <div class="item ${classe}">
                    ${linhaHtml}
                    <div class="bola">
                        <span>${i}</span>
                        <div class="cor_fill check"><svg height="14" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"></path></svg></div>
                    </div>
                    ${tituloHtml}
                </div>
            `);
        }
        this.progresso = bloco;
        this.progressoItem = $$('.item', bloco);
    }

    montarConteudo() {
        let i = 1;
        let quantidade = this.conteudoQuantidade - 1;
        for (; i <= quantidade; ++i) {
            const item = this.conteudo[i];
            item.sumir().classe('sumir', true);
        }
        this.conteudo[0].aparecer().classe('sumir', false);
    }

    proximo(botao) {
        const blocoAtual = botao.closest(this.conteudoClasse);
        let blocoNovo, numeroNovo;
        let i = 0;
        const quantidade = this.conteudoQuantidade - 1;
        for (; i < quantidade; ++i) {
            if (i == quantidade) {
                return;
            } else if (this.conteudo[i] == blocoAtual) {
                numeroNovo = i + 1;
                blocoNovo = this.conteudo[numeroNovo];
                this.progressoItem[i].classe('atual', false).classe('concluido', true);
                this.progressoItem[numeroNovo].classe('atual', true);
                break;
            }
        }
        this.animarTransicao(blocoNovo, blocoAtual);
        this.rolarBlocoProgresso();
    }

    anterior(botao) {
        const blocoAtual = botao.closest(this.conteudoClasse);
        let blocoNovo, numeroNovo;
        let i = this.conteudoQuantidade - 1;
        for (; i >= 0; --i) {
            if (i == 0) {
                return;
            } else if (this.conteudo[i] == blocoAtual) {
                numeroNovo = i - 1;
                blocoNovo = this.conteudo[numeroNovo];
                this.progressoItem[i].classe('atual', false);
                this.progressoItem[numeroNovo].classe('atual', true).classe('concluido', false);
                break;
            }
        }
        this.animarTransicao(blocoNovo, blocoAtual);
        this.rolarBlocoProgresso();
    }

    animarTransicao(novo, atual) {
        // novo.aparecer();
        atual.classe('sumir', true);
        setTimeout(() => {
            novo.aparecer();
        }, 300);
        setTimeout(() => {
            novo.classe('sumir', false);
        }, 310);
        setTimeout(() => {
            atual.sumir().classe('sumir', true);
        }, 300);
    }

    rolarBlocoProgresso() {
        this.progresso.scrollIntoView({
            behavior: 'smooth',
        });
    }
}
