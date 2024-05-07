// @template "site"
// @system "Alerta"
// @system "Loading"
// @system "Popup"

const PopupAtualizar = new Popup('atualizar-dado', 'bloco_atualizar_email', true, true);
const botaoAbrirPopupAtualizar = $('#botao_samsung_atualizar');
const blocoEmailLista = $('#bloco_email_lista');
const blocoEmailZero = $('#bloco_email_zero');
const blocoEmaiNovo = $('#bloco_email_novo');
const inputEmailPessoal = $('#input_email_pessoal');
const inputEmailTrabalho = $('#input_email_trabalho');
const bannerDesktop = $('#bloco_banner_desktop');
const bannerMobile = $('#bloco_banner_mobile');

/* eslint-disable */
class Banner {
    constructor(banner, item, botaoProximo, botaoAnterior) {
        if (!banner) {
            return false;
        }
        this.banner = banner;
        this.banner.classList.add('fw_banner');
        this.botaoProximo = botaoProximo;
        this.botaoAnterior = botaoAnterior;
        this.lista = banner.querySelectorAll(item);
        this.quantidade = this.lista.length - 1;
        this.eventoBotaoProximoAnterior();
        this.eventoItem();
    }
    eventoItem() {
        this.lista.forEach((item, i) => {
            item.addEventListener('animationend', () => {
                this.bannerProximo();
            });
            item.classList.add('fw_banner_item');
            if (i > 0) {
                item.classList.add('fw_banner_hide');
                return;
            }
            item.classList.add('fw_banner_atual');
        });
    }
    bannerProximo() {
        const itemAtual = this.banner.querySelector('.fw_banner_atual');
        const quantidade = this.quantidade;

        let lista = this.lista;

        let proximo;
        let i = 0;
        for (; i <= quantidade; i++) {
            if (lista[i] == itemAtual) {
                proximo = lista[i == quantidade ? 0 : i + 1];
                break;
            }
        }
        this.animarProximoBanner(itemAtual, proximo);
    }
    bannerAnterior() {
        const itemAtual = this.banner.querySelector('.fw_banner_atual');
        const quantidade = this.quantidade;

        let lista = this.lista;
        let proximo;
        let i = 0;
        for (; i <= quantidade; i++) {
            if (lista[i] == itemAtual) {
                proximo = lista[i == 0 ? quantidade : i - 1];
                break;
            }
        }
        this.animarProximoBanner(itemAtual, proximo);
    }
    animarProximoBanner(atual, proximo) {
        atual.classList.remove('fw_banner_atual');
        setTimeout(() => {
            atual.classList.add('fw_banner_hide');
        }, 300);

        proximo.classList.remove('fw_banner_hide');
        setTimeout(() => {
            proximo.classList.add('fw_banner_atual');
        }, 20);
    }
    eventoBotaoProximoAnterior() {
        if (this.lista.length <= 1) {
            return;
        }
        const banner = this.banner;
        const botaoProximo = this.botaoProximo;
        const botaoAnterior = this.botaoAnterior;

        const self = this;
        if (botaoProximo && botaoAnterior) {
            botaoProximo.addEventListener('click', function () {
                self.bannerProximo();
            });
            botaoAnterior.addEventListener('click', function () {
                self.bannerAnterior();
            });
        }

        banner.addEventListener('swiped-left', function () {
            self.bannerAnterior();
        });
        banner.addEventListener('swiped-right', function () {
            self.bannerProximo();
        });
    }
}
/* eslint-enable */

if (bannerDesktop) {
    new Banner(bannerDesktop, 'figure', $('#botao_banner_proximo'), $('#botao_banner_anterior'));
}

if (bannerMobile) {
    new Banner(bannerMobile, 'figure', $('#botao_banner_proximo_mobile'), $('#botao_banner_anterior_mobile'));
}
window.addEventListener('load', () => {
    botaoAbrirPopupAtualizar.addEventListener('click', () => {
        PopupAtualizar.abrir();
    });

    const adicionarNovoEmail = (pessoal, trabalho) => {
        blocoEmailLista.classList.remove('display_none');
        blocoEmailZero.classList.add('display_none');
        botaoAbrirPopupAtualizar.innerText = 'Atualizar e-mail';
        const email = [];
        if (pessoal != '') {
            email.push(pessoal);
        }
        if (trabalho != '') {
            email.push(trabalho);
        }

        inputEmailPessoal.value = pessoal;
        inputEmailTrabalho.value = trabalho;

        blocoEmaiNovo.innerHTML = '<strong>' + email.join('</strong> ou <strong>', email) + '</strong>';
        Popup.staticFechar();
    };

    const botao = document.querySelector('.botao_atualizar_email');
    const inputPessoal = document.querySelector('input[name="email_pessoal"]');
    const inputTrabalho = document.querySelector('input[name="email_trabalho"]');

    const salvarEmail = async () => {
        if (inputPessoal.value == '' && inputTrabalho.value == '') {
            Alerta.notificacao('Você deve passar pelo menos um e-mail para continuar.', false);
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/perfil/salvar-email',
            {
                /* eslint-disable */
                email_pessoal: inputPessoal.value,
                email_trabalho: inputTrabalho.value,
                /* eslint-enable */
            },
            'Erro ao atualizar seus e-mail, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        adicionarNovoEmail(inputPessoal.value, inputTrabalho.value);
    };

    botao.addEventListener('click', () => {
        salvarEmail();
    });
    adicionarEventoEnter([inputPessoal, inputTrabalho], salvarEmail);
});
