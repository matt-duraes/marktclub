// @system "Mascara"
// @system "Loading"
// @system "Alerta"
// @system "Calendario"
// @system "Form"

const RECAPTCHA = $('#RECAPTCHA').value;

window.addEventListener('load', () => {
    const blocoPopupCadastro = $('#bloco_cadastro');
    const blocoPopupTermo = $('#bloco_termo');

    const inputTermo = $('#input_termo');
    const inputCookie = $('#input_cookie');
    const inputCpf = $('#input_cpf');
    const inputInscricao = $('#input_inscricao');
    const inputEstado = $('#input_estado');
    const inputDataNascimento = $('#input_data_nascimento');
    const inputMae = $('#input_nome_mae');
    const inputLista = $$('#input_cpf, #input_inscricao, #input_estado, #input_data_nascimento, #input_nome_mae');

    const botaoLogar = $('#botao_logar');
    const botaoCadastro = $('#botao_cadastro');
    const botaoCancelar = $('#botao_cancelar');
    const botaoTermoFechar = $('#botao_termo_fechar');
    const botaoTermoAbrir = $('#botao_abrir_termo');

    Calendario.init({
        input: '#input_data_nascimento',
    });

    fwFormValidarMascara = (string, mascara) => {
        if (string.length !== mascara.length) {
            return false;
        }
        for (let i = 0; i < string.length; i++) {
            if ((mascara[i] == '0' && !/\d/.test(string[i])) || (mascara[i] != '0' && string[i] !== mascara[i])) {
                return false;
            }
        }
        return true;
    };

    inputLista.focar();

    inputMae.evento('keydown', e => {
        if (e.key == ' ') {
            e.preventDefault();
        } else if (e.key == 'Enter' && !vazio(inputMae.valor())) {
            fazerLogin(false);
        }
    });

    botaoLogar.evento('click', () => {
        fazerLogin(false);
    });

    botaoCadastro.evento('click', () => {
        fazerLogin(true);
    });

    const fazerLogin = async cadastro => {
        const validar = inputLista.validar();
        if (!cadastro && validar !== true) {
            Alerta.notificacao(validar.mensagem, false);
            return;
        } else if (cadastro && !inputTermo.checked) {
            Alerta.notificacao('Aceite os termos para continuar.');
        }
        const resposta = await ajaxPost(
            LINK + '/',
            {
                tipo: 'funcionario',
                cpf: inputCpf.valor(),
                inscricao: inputInscricao.valor(),
                estado: inputEstado.valor(),
                /* eslint-disable */
                data_nascimento: dataBanco(inputDataNascimento.valor()),
                nome_mae: inputMae.valor(),
                /* eslint-enable */
                cadastro: cadastro ? 'sim' : 'nao',
                captcha: '',
            },
            'Ocorreu um erro ao fazer seu login, por favor, tente novamente.'
        );
        // popupAbrir(blocoPopupCadastro);
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

    // const blocoMensagemLogin = document.querySelector('#bloco_mensagem_login');
    // const blocoMensagemRelogar = document.querySelector('#bloco_mensagem_relogar');
    // const blocoNomeUsuario = document.querySelector('#bloco_nome_usuario');
    // const blocoCpf = document.querySelector('.bloco_input.cpf');
    // const blocoInscricao = document.querySelector('.bloco_input.inscricao');
    // const blocoEstado = document.querySelector('.bloco_input.estado');
    // const blocoDataNascimento = document.querySelector('.bloco_input.data_nascimento');
    // const blocoMae = document.querySelector('.bloco_input.mae');
    // const blocoRecaptcha = document.querySelector('#recaptcha_login');
    // const blocoCookie = document.querySelector('#bloco_cookie');
    // const blocoTrocarUsuario = document.querySelector('#bloco_trocar_usuario');

    // const blocoTermo = document.querySelector('#bloco_termo');
    // const botaoAbrirTermo = document.querySelector('#botao_abrir_termo');
    // const botaoFecharTermo = document.querySelector('#botao_fechar_termo');

    // const botaoCadastroCancelar = document.querySelector('#botao_cadastro_cancelar');
    // const botaoCadastroContinuar = document.querySelector('#botao_cadastro_continuar');

    // const blocoCadastro = document.querySelector('#bloco_cadastro');

    // const botaoFazerLogin = document.querySelector('#botao_fazer_login');
    // const botaoRefazerLogin = document.querySelector('#botao_refazer_login');
    // const botaoMudarUsuario = document.querySelector('#botao_mudar_usuario');

    // const mostrarBlocoDoLogin = () => {
    //     deleteCookie();
    //     blocoMensagemLogin.classList.remove('display_none');
    //     blocoCpf.classList.remove('display_none');
    //     blocoInscricao.classList.remove('display_none');
    //     blocoEstado.classList.remove('display_none');
    //     blocoDataNascimento.classList.remove('display_none');
    //     blocoMae.classList.remove('display_none');
    //     blocoRecaptcha.classList.remove('display_none');
    //     blocoCookie.classList.remove('display_none');
    //     botaoFazerLogin.classList.remove('display_none');

    //     blocoNomeUsuario.innerText = '';
    //     blocoMensagemRelogar.classList.add('display_none');
    //     blocoTrocarUsuario.classList.add('display_none');
    //     botaoRefazerLogin.classList.add('display_none');

    //     blocoCookie.checked = false;
    //     blocoTermo.checked = false;

    //     inputCpf.value = '';
    //     inputInscricao.value = '';
    //     inputEstado.value = '';
    //     inputDataNascimento.value = '';
    //     inputMae.value = '';
    // };

    // const C = getCookie();
    // if (C) {
    //     blocoNomeUsuario.innerText = C.nome;
    //     blocoMensagemRelogar.classList.remove('display_none');
    //     blocoTrocarUsuario.classList.remove('display_none');
    //     botaoRefazerLogin.classList.remove('display_none');

    //     inputCpf.value = C.cpf;
    //     inputInscricao.value = C.inscricao;
    //     inputEstado.value = C.estado;
    //     inputDataNascimento.value = C.data_nascimento;
    //     inputMae.value = C.nome_mae;
    // } else {
    //     mostrarBlocoDoLogin();
    // }

    // botaoMudarUsuario.addEventListener('click', () => {
    //     mostrarBlocoDoLogin();
    // });

    // const fazerLogin = async (cadastro, relogar) => {
    //     captcha = grecaptcha.getResponse(captchaLogin);
    //     const cpf = inputCpf.value.trim().replace(/[^0-9]/g, '');
    //     const inscricao = inputInscricao.value.trim().replace(/[^0-9]/g, '');
    //     const estado = inputEstado.value.trim();
    //     const dataNascimento = inputDataNascimento.value.trim();
    //     const mae = inputMae.value.trim();

    //     if (cadastro && !inputTermo.checked) {
    //         Alerta('Campo obrigatório!', 'Você precisa aceitar os termos para continuar.');
    //         return;
    //     } else if (captcha == '' && !cadastro && !relogar) {
    //         Alerta('Campo obrigatório!', 'Marque o box de "Não sou um robo" para continuar.');
    //         return false;
    //     } else if (cpf == '') {
    //         Alerta('Campo obrigatório!', 'Digite seu CPF para continuar.');
    //         return;
    //     } else if (inscricao == '') {
    //         Alerta('Campo obrigatório!', 'Digite seu matrícula para continuar.');
    //         return;
    //     } else if (estado == '') {
    //         Alerta('Campo obrigatório!', 'Escolha a UF para continuar');
    //         return;
    //     } else if (dataNascimento == '') {
    //         Alerta('Campo obrigatório!', 'Digite a sua data de nascimento para continuar.');
    //         return;
    //     } else if (mae == '') {
    //         Alerta('Campo obrigatório!', 'Digite o nome da sua mãe para continuar.');
    //         return;
    //     } else if (mae.length > 80) {
    //         Alerta('Campo inválido!', 'O primeiro nome da sua mãe deve teve ter no máximo 80 caracteres.');
    //         return;
    //     }

    //     Loading('show');

    //     const body = new FormData();
    //     body.append('tipo', 'funcionario');
    //     body.append('cpf', cpf);
    //     body.append('inscricao', inscricao);
    //     body.append('estado', estado);
    //     body.append('data_nascimento', converterData(dataNascimento));
    //     body.append('nome_mae', mae);
    //     body.append('cadastro', cadastro ? 'sim' : 'nao');
    //     body.append('captcha', captcha);

    //     const resposta = await fetch(LINK + '/auth/cfm', {
    //         method: 'POST',
    //         body,
    //     });

    //     let json;
    //     try {
    //         json = await resposta.json();
    //     } catch (error) {
    //         json = {};
    //     }

    //     if (json.erro == false && json.cadastro == true) {
    //         blocoCadastro.classList.remove('display_none');
    //         setTimeout(() => {
    //             blocoCadastro.classList.add('ativo');
    //         }, 40);
    //         Loading('hide');
    //         return;
    //     } else if (json.erro == true) {
    //         Alerta(json.titulo || 'Erro!', json.texto || 'Ocorreu um erro ao fazer login, por favor, tente novamente.');
    //         Loading('hide');
    //         return;
    //     }

    //     if (json === undefined || typeof json != 'object' || json.link === undefined) {
    //         Alerta('Erro!', 'Ocorreu um erro ao fazer login, por favor, tente novamente.');
    //         Loading('hide');
    //         return;
    //     }

    //     if (inputCookie.checked) {
    //         const cookie = JSON.stringify({
    //             nome: json.nome,
    //             cpf: cpf,
    //             inscricao: inscricao,
    //             estado: estado,
    //             data_nascimento: dataNascimento,
    //             nome_mae: mae,
    //         });
    //         setCookie(window.btoa(cookie));
    //     }
    //     window.location.assign(json.link);
    // };

    // const converterData = data => {
    //     const e = data.split('/');
    //     if (e.length != 3) {
    //         return '';
    //     }
    //     return e[2] + '-' + e[1] + '-' + e[0];
    // };

    // const listaInput = document.querySelectorAll('.bloco_input input');
    // listaInput.forEach(input => {
    //     input.addEventListener('focus', () => {
    //         input.closest('.bloco_input').classList.add('ativo');
    //     });
    //     input.addEventListener('blur', () => {
    //         if (input.value == '') {
    //             input.closest('.bloco_input').classList.remove('ativo');
    //         }
    //     });
    //     input.addEventListener('keydown', e => {
    //         if (e.key == 'Enter') {
    //             e.preventDefault();
    //             fazerLogin(false);
    //         }
    //     });
    // });
    // inputEstado.addEventListener('change', () => {
    //     const bloco = inputEstado.closest('.bloco_input');
    //     if (inputEstado.value == '') {
    //         bloco.classList.remove('ativo');
    //         return;
    //     }
    //     bloco.classList.add('ativo');
    // });

    // botaoCadastroCancelar.addEventListener('click', () => {
    //     blocoCadastro.classList.remove('ativo');
    //     setTimeout(() => {
    //         blocoCadastro.classList.add('display_none');
    //     }, 300);
    // });
    // botaoCadastroContinuar.addEventListener('click', () => {
    //     fazerLogin(true, false);
    // });
    // botaoFazerLogin.addEventListener('click', e => {
    //     e.preventDefault();
    //     fazerLogin(false, false);
    // });
    // botaoRefazerLogin.addEventListener('click', e => {
    //     e.preventDefault();
    //     fazerLogin(false, true);
    // });

    // const fecharTermo = () => {
    //     blocoTermo.classList.remove('ativo');
    //     setTimeout(() => {
    //         blocoTermo.classList.add('display_none');
    //     }, 300);
    // };
    // botaoAbrirTermo.addEventListener('click', () => {
    //     blocoTermo.classList.remove('display_none');
    //     setTimeout(() => {
    //         blocoTermo.classList.add('ativo');
    //     }, 40);
    // });
    // botaoFecharTermo.addEventListener('click', () => {
    //     fecharTermo();
    // });
    // blocoTermo.addEventListener('click', e => {
    //     if (e.target.getAttribute('id') == 'bloco_termo') {
    //         fecharTermo();
    //     }
    // });

    // const blocoAlerta = document.querySelector('#bloco_alerta');
    // const blocoAlertaTitulo = document.querySelector('#bloco_alerta h1');
    // const blocoAlertaMensagem = document.querySelector('#bloco_alerta p');
    // const botaoAlertaFechar = document.querySelector('#botao_alerta_fechar');
    // const Alerta = (titulo, mensagem) => {
    //     blocoAlertaTitulo.innerText = titulo;
    //     blocoAlertaMensagem.innerText = mensagem;
    //     blocoAlerta.classList.remove('display_none');
    //     setTimeout(() => {
    //         blocoAlerta.classList.add('ativo');
    //     }, 40);
    // };

    // const fecharAlerta = () => {
    //     blocoAlerta.classList.remove('ativo');
    //     setTimeout(() => {
    //         blocoAlerta.classList.add('display_none');
    //         blocoAlertaTitulo.innerText = '';
    //         blocoAlertaMensagem.innerText = '';
    //     }, 300);
    // };
    // blocoAlerta.addEventListener('click', e => {
    //     if (e.target.getAttribute('id') == 'bloco_alerta') {
    //         fecharAlerta();
    //     }
    // });
    // botaoAlertaFechar.addEventListener('click', () => {
    //     fecharAlerta();
    // });

    // const blocoLoading = document.querySelector('#bloco_loading');
    // const Loading = tipo => {
    //     if (tipo == 'show') {
    //         blocoLoading.classList.remove('display_none');
    //         setTimeout(() => {
    //             blocoLoading.classList.add('ativo');
    //         }, 40);
    //         return;
    //     }

    //     blocoLoading.classList.remove('ativo');
    //     setTimeout(() => {
    //         blocoLoading.classList.add('display_none');
    //     }, 300);
    // };

    // function getCookie() {
    //     const cookies = ' ' + document.cookie;
    //     const key = ' LCFM=';
    //     const start = cookies.indexOf(key);

    //     if (start === -1) return null;

    //     const pos = start + key.length;
    //     const last = cookies.indexOf(';', pos);

    //     if (last !== -1) return cookies.substring(pos, last);

    //     let C = cookies.substring(pos);
    //     try {
    //         C = JSON.parse(window.atob(C));
    //     } catch (error) {
    //         return '';
    //     }
    //     if (
    //         C instanceof Object &&
    //         C.nome != undefined &&
    //         C.nome != '' &&
    //         C.cpf != undefined &&
    //         C.cpf != '' &&
    //         C.inscricao != undefined &&
    //         C.inscricao != '' &&
    //         C.estado != undefined &&
    //         C.estado != '' &&
    //         C.data_nascimento != undefined &&
    //         C.data_nascimento != '' &&
    //         C.nome_mae != undefined &&
    //         C.nome_mae != ''
    //     ) {
    //         return C;
    //     }
    //     return '';
    // }

    // function setCookie(v) {
    //     const expirationDate = new Date();
    //     expirationDate.setFullYear(expirationDate.getFullYear() + 1);
    //     const expirationDateString = expirationDate.toUTCString();
    //     document.cookie = 'LCFM=' + encodeURIComponent(v) + '; expires=' + expirationDateString + '; path=/';
    // }

    // function deleteCookie() {
    //     document.cookie = `LCFM=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    // }
});
