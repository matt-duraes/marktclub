// @system "Mascara"
// @system "Loading"
// @system "Alerta"
// @system "Calendario"
// @system "Form"

window.addEventListener('load', () => {
    const RECAPTCHA = $('#RECAPTCHA').valor();
    const RECAPTCHAV2 = $('#RECAPTCHA_V2').valor();
    const HASH = $('#input_login_hash').valor();
    const blocoPopupCadastro = $('#bloco_cadastro');
    const blocoPopupTermo = $('#bloco_termo');

    const blocoNormal = $$('.bloco_normal');
    const blocoCookie = $$('.bloco_cookie');

    const blocoRecaptchaLogin = $('#bloco_recaptcha_v2_login');
    const blocoRecaptchaCadastro = $('#bloco_recaptcha_v2_cadastro');

    const inputTermo = $('#input_termo');
    const inputCookie = $('#input_cookie');
    const inputCpf = $('#input_cpf');
    const inputInscricao = $('#input_inscricao');
    const inputEstado = $('#input_estado');
    const inputDataNascimento = $('#input_data_nascimento');
    const inputMae = $('#input_nome_mae');
    const inputLista = $$('#input_cpf, #input_inscricao, #input_estado, #input_data_nascimento, #input_nome_mae');

    const botaoLogar = $$('#botao_logar, #botao_cookie');
    const botaoCadastro = $('#botao_cadastro');
    const botaoCancelar = $('#botao_cancelar');
    const botaoTermoFechar = $('#botao_termo_fechar');
    const botaoTermoAbrir = $('#botao_abrir_termo');
    const botaoOutro = $('#botao_outro');

    Calendario.init({
        input: '#input_data_nascimento',
    });

    const CB = pegarCookie();
    if (!vazio(CB)) {
        blocoCookie.aparecer();
        inputCpf.valor(CB.cpf);
        inputInscricao.valor(CB.inscricao);
        inputEstado.valor(CB.estado);
        inputDataNascimento.valor(CB.dataNascimento);
        inputMae.valor(CB.nomeMae);
        inputCookie.check = true;
    } else {
        blocoNormal.aparecer();
    }

    inputLista.focar();

    inputMae.evento('keydown', e => {
        if (e.key == ' ') {
            e.preventDefault();
        } else if (e.key == 'Enter' && !vazio(inputMae.valor())) {
            pegarCaptchaParaLogin(true);
        }
    });

    botaoLogar.evento('click', () => {
        pegarCaptchaParaLogin(true);
    });

    botaoOutro.evento('click', () => {
        blocoCookie.sumir();
        blocoNormal.aparecer();
        inputCookie.check = false;
    });

    botaoCadastro.evento('click', () => {
        pegarCaptchaParaLogin(false);
    });

    let captchaVersao = 'v3.';
    const pegarCaptchaParaLogin = cadastro => {
        const validar = inputLista.validar();
        if (cadastro && validar !== true) {
            Alerta.notificacao(validar.mensagem, false);
            return;
        } else if (!cadastro && !inputTermo.checked) {
            Alerta.notificacao('Aceite os termos para continuar.', false);
            return;
        }

        Loading.show();
        if (captchaVersao == 'v2.') {
            const token = grecaptcha.getResponse(cadastro ? 0 : 1);
            if (token == '') {
                Loading.hide();
                Alerta.notificacao('Marque o box de "Não sou um Robô" para continuar.', false);
                return;
            }
            fazerLogin(cadastro, token);
            return;
        }

        grecaptcha.ready(function () {
            grecaptcha
                .execute(RECAPTCHA, { action: 'create_singup' })
                .then(function (token) {
                    fazerLogin(cadastro, token);
                })
                .catch(() => {
                    Loading.hide();
                    Alerta.notificacao(
                        'Ocorreu um erro ao fazer o login, a página vai ser recarregada em 10 segundos.',
                        false
                    );
                });
        });
    };

    const fazerLogin = async (cadastro, token) => {
        const cpf = inputCpf.valor();
        const inscricao = inputInscricao.valor();
        const estado = inputEstado.valor();
        const dataNascimento = dataBanco(inputDataNascimento.valor());
        const nomeMae = inputMae.valor();

        const body = new FormData();
        body.append('cpf', cpf);
        body.append('inscricao', inscricao);
        body.append('estado', estado);
        body.append('cadastro', cadastro ? 'sim' : 'nao');
        body.append('data_nascimento', dataNascimento);
        body.append('nome_mae', nomeMae);
        body.append('form_system_captcha', captchaVersao + token);
        body.append('form_system_validacao', '');
        body.append('form_system_hash', HASH);

        const resposta = await fetch(LINK + '/login/cfm', {
            body,
            method: 'POST',
        });

        let json;
        try {
            json = await resposta.json();
        } catch (e) {
            Loading.hide();
            Alerta.notificacao('Ocorreu um erro ao tentar fazer o login, por favor, tente novamente.', false);
            return false;
        }

        if (resposta.status == 201) {
            loginRealizadoComSucesso(
                resposta.dado.nome,
                cpf,
                inscricao,
                estado,
                dataNascimento,
                nomeMae,
                resposta.dado.link
            );
            return;
        }

        Loading.hide();
        if (json.status == 'erro' && json.erro.captcha === false && captchaVersao == 'v3.') {
            Alerta.notificacao('Erro ao validar recaptcha, faça o desafio manual para continuar.', false);
            mostrarCaptchaV2();
            return;
        } else if (
            json.status == 'sucesso' &&
            json.dado !== undefined &&
            json.dado.cadastro !== undefined &&
            json.dado.cadastro === 'sim'
        ) {
            inputTermo.check = false;
            popupAbrir(blocoPopupCadastro);
            return;
        }

        if (captchaVersao == 'v2.') {
            grecaptcha.reset(cadastro ? 1 : 0);
        }

        Alerta.notificacao(
            json.erro !== undefined && json.erro.mensagem !== undefined
                ? json.erro.mensagem
                : 'Erro ao fazer seu login, por favor, tente novamente.',
            false
        );
    };

    const loginRealizadoComSucesso = (nome, cpf, inscricao, estado, dataNascimento, nomeMae, link) => {
        if (inputCookie.checked) {
            const cookie = JSON.stringify({
                nome,
                cpf,
                inscricao,
                estado,
                dataNascimento,
                nomeMae,
            });
            salvarCookie(window.btoa(cookie));
        }
        window.location.assing(link);
    };

    const mostrarCaptchaV2 = () => {
        captchaVersao = 'v2.';
        blocoRecaptchaLogin.aparecer();
        blocoRecaptchaCadastro.aparecer();

        grecaptcha.render('bloco_recaptcha_v2_login', {
            sitekey: RECAPTCHAV2,
            theme: 'light',
        });
        grecaptcha.render('bloco_recaptcha_v2_cadastro', {
            sitekey: RECAPTCHAV2,
            theme: 'light',
        });
    };

    botaoCancelar.evento('click', () => {
        popupFechar(blocoPopupCadastro);
    });
    botaoTermoAbrir.evento('click', () => {
        popupAbrir(blocoPopupTermo);
    });
    botaoTermoFechar.evento('click', () => {
        popupFechar(blocoPopupTermo);
    });

    const popupAbrir = bloco => {
        bloco.aparecer();
        bloco.classe('aberto', true, 10);
    };
    const popupFechar = bloco => {
        bloco.classe('aberto', false);
        bloco.sumir(300);
    };

    function pegarCookie() {
        const cookies = ' ' + document.cookie;
        const key = ' LCFM=';
        const start = cookies.indexOf(key);

        if (start === -1) {
            return '';
        }

        const pos = start + key.length;
        const last = cookies.indexOf(';', pos);

        if (last !== -1) return cookies.substring(pos, last);

        let C = cookies.substring(pos);
        try {
            C = JSON.parse(window.atob(C));
        } catch (error) {
            return '';
        }
        if (
            C instanceof Object &&
            C.nome != undefined &&
            C.nome != '' &&
            C.cpf != undefined &&
            C.cpf != '' &&
            C.inscricao != undefined &&
            C.inscricao != '' &&
            C.estado != undefined &&
            C.estado != '' &&
            C.dataNascimento != undefined &&
            C.dataNascimento != '' &&
            C.nomeMae != undefined &&
            C.nomeMae != ''
        ) {
            return C;
        }
        return '';
    }

    function salvarCookie(v) {
        const expirationDate = new Date();
        expirationDate.setFullYear(expirationDate.getFullYear() + 1);
        const expirationDateString = expirationDate.toUTCString();
        document.cookie = 'LCFM=' + encodeURIComponent(v) + '; expires=' + expirationDateString + '; path=/';
    }

    function deletarCookie() {
        document.cookie = `LCFM=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }
});
