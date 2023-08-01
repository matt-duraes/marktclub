// @template "login"
// @system "Form"
// @system "Alerta"
// @system "Loading"

/* const RECAPTCHA_SITE = document.querySelector('#RECAPTCHA_SITE');
let captchaBuscar;
let usarRecaptcha = true;

onloadCallbackCaptcha = () => {
    captchaBuscar = grecaptcha.render('captcha_buscar', {
        sitekey: RECAPTCHA_SITE,
        theme: 'light',
    });
};
 */

function escondeElemento(selector) {
    document.querySelector(selector).style.display = 'none';
}

function mostraElemento(selector) {
    document.querySelector(selector).style.display = 'block';
}
function mostraElementoFlex(selector) {
    document.querySelector(selector).style.display = 'block';
}

async function buscarUsuario(form, botao) {
    let captcha = '';

    const buscarInput = form.querySelector('input[name=pesquisa]');

    if (buscarInput.value === '') {
        Alerta.mensagem('Campo obrigatório!', 'O campo para buscar os seus dados não pode ser vazio.');
        return false;
    }

    const tipo = buscarInput.getAttribute('data-tipo');
    const pesquisa = buscarInput.value;

    Loading.show(form, botao);

    const body = new FormData();
    body.append('client_id', '{CLIENT_ID AQUI}');
    body.append('pesquisa', pesquisa);
    body.append('tipo', tipo);
    body.append('captcha', '{CAPTCHA AQUI}');

    const resposta = await fetch(LINK + '/auth/buscar-usuario', {
        method: 'POST',
        body,
    });

    Loading.hide(form, botao);
    let json;
    try {
        json = await resposta.json();
    } catch (error) {
        json = {};
    }

    if (resposta.status === 201) {
        preencherDadosCadastrais(resposta);
        Loading.hide(form, botao);
        return;
    }

    Alerta.notificacao(
        json.erro.mensagem !== undefined
            ? json.erro.mensagem
            : 'Ocorreu um erro ao simular, por favor, tente novamente.',
        false
    );
}

function preencherDadosCadastrais(resposta) {
    /*   document.getElementById('captcha_buscar').style.display = 'none';
    if (usarRecaptcha) {
        grecaptcha.reset(captchaBuscar);
    }
 */
    document.querySelector('#auth_form_ativar input[name=hash]').value = resposta.hash;

    escondeElemento('.auth_bloco_geral_site .auth_form_geral .bloco_buscar');
    mostraElemento('.auth_bloco_geral_site .auth_form_geral .bloco');
    /* document.querySelector('.auth_bloco_geral_site .auth_form_geral .bloco_com_ddi').style.display = 'flex'; */
    const authFormElements = document.querySelectorAll(
        '.auth_bloco_geral_site .auth_form_geral .contato, .auth_bloco_geral_site .auth_form_geral .bloco.dados_pessoais, .auth_bloco_geral_site .auth_form_geral .endereco, .auth_bloco_geral_site .auth_form_geral .senha, .auth_bloco_geral_site .auth_form_geral .dependente, .auth_bloco_geral_site .auth_form_geral .botao'
    );
    authFormElements.forEach(element => (element.style.display = 'flex'));

    document.querySelector('.bloco_input_container.nome input').focus();

    if (true === resposta.cpf) {
        document.querySelector('.bloco_input_container.cpf').remove();
    }
    if (true === resposta.nome) {
        document.querySelector('.bloco_input_container.nome').remove();
    }

    const blocoInputs = document.querySelectorAll('.bloco_input_container .bloco_input');
    blocoInputs.forEach(input => {
        if (input.querySelector('input').value !== '') {
            input.classList.add('ativo');
        }
    });
}

// Event listener for the click on the "buscar_usuario" button
document.getElementById('botao_buscar_usuario').addEventListener('click', function () {
    const form = this.closest('form');
    const botao = this;
    buscarUsuario(form, botao);
});

//ativar cadastro:
const ativarCadastro = async (form, botao) => {
    try {
        const CLIENT_ID = '{CLIENT_ID AQUI}';
        const SCOPE = '{SCOPE AQUI}';
        const REDIRECT_URI = '{REDIRECT_URI AQUI}';
        const STATE = '{STATE AQUI}';

        const termoUsoChecked = document.querySelector('#termo_uso').checked;
        if (!termoUsoChecked) {
            Alerta.notificacao('Campo obrigatório! Você precisa aceitar os termos para continuar.');
            return;
        }

        Loading.show(form, botao);

        const dado = {};
        const inputElements = form.querySelectorAll('*[name]');
        inputElements.forEach(input => {
            const name = input.getAttribute('name');
            const value = input.value;
            dado[name] = value;
        });
        dado['termo_uso'] = termoUsoChecked;

        const options = {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                dado: JSON.stringify(dado),
                client_id: CLIENT_ID,
                scope: SCOPE,
                redirect_uri: REDIRECT_URI,
                state: STATE,
            }),
        };

        const response = await fetch(form.getAttribute('action'), options);
        const resposta = await response.json();

        if (resposta.status === 'sucesso' && resposta.empresa !== 233) {
            if (
                resposta.limite < 5 &&
                resposta.liberacao_dependente === 1 &&
                (resposta.tipo === 1 || resposta.tipo === 3)
            ) {
                form.style.display = 'none';

                mostraElementoFlex('#auth_form_adicionar_dependente');
                const efetuarLoginLink = authFormAdicionarDependente.querySelector('.botao_coluna .efetuar_login');
                efetuarLoginLink.setAttribute('href', resposta.link);
                const titularInput = authFormAdicionarDependente.querySelector(
                    '.dependente_conteudo input[name=titular]'
                );
                titularInput.value = resposta.id;
            } else if (resposta.limite < 5 && resposta.liberacao_dependente === 1 && resposta.tipo === 2) {
                window.location.assign(resposta.link);
            } else {
                window.location.assign(resposta.link);
            }
        } else {
            if (resposta.erro === true && resposta.recarregar === true) {
                Alerta.notificacao(resposta.titulo + ' ' + resposta.texto);
                location.reload();
            } else {
                Alerta.notificacao(resposta.titulo + ' ' + resposta.texto);
            }
        }
    } catch (error) {
        Alerta.notificacao('Erro ao ativar! Ocorreu um erro ao enviar dados, por favor, tente novamente.');
    } finally {
        Loading.hide(form, botao);
    }
};

document.getElementById('botao_ativar_cadastro').addEventListener('click', function (e) {
    e.preventDefault();
    const form = this.closest('form');
    ativarCadastro(form, this);
});

//ADICIONAR DEPENDENTE

document
    .querySelector('#auth_form_adicionar_dependente .botao_coluna .adicionar_dependente')
    .addEventListener('click', function (e) {
        e.preventDefault();
        escondeElemento('#auth_form_adicionar_dependente .botao_coluna');
        mostraElemento('#auth_form_adicionar_dependente .dependente_conteudo');
    });

document
    .querySelector('#auth_form_adicionar_dependente .dependente_conteudo .post_botao_salvar_dependente')
    .addEventListener('click', function (e) {
        e.preventDefault();

        const form = this.closest('form');
        const link = form.getAttribute('action');
        const nome = form.querySelector('input[data-name=nome_dependente]').value;
        const cpf = form.querySelector('input[data-name=cpf_dependente]').value;
        const sexo = form.querySelector('select[data-name=sexo_dependente]').value;
        const email_dependente = form.querySelector('input[name=email_dependente]').value;
        const titular = form.querySelector('input[name=titular]').value;
        const empresa = form.querySelector('input[name=empresa]').value;
        const client_id = form.querySelector('input[name=client_id]').value;

        Loading.show();

        // Make the AJAX request using fetch API
        fetch(link, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                nome_completo: nome,
                documento_cpf: cpf,
                email_pessoal: email_dependente,
                sexo: sexo,
                titular: titular,
                empresa: empresa,
                client_id: client_id,
            }),
        })
            .then(response => response.json())
            .then(resposta => {
                if (resposta.erro === false) {
                    Alerta.mensagem('Dependente criado!', 'Dependente ' + nome + ' criado com sucesso!');

                    // Update the UI based on the response
                    if (resposta.limite === 5) {
                        escondeElemento('#auth_form_adicionar_dependente .botao_coluna');
                        mostraElemento('#auth_form_adicionar_dependente .botao_coluna .limite_dependente');
                        escondeElemento('#auth_form_adicionar_dependente .botao_coluna .adicionar_dependente');
                        escondeElemento('#auth_form_adicionar_dependente .dependente_conteudo');
                    } else {
                        mostraElemento('#auth_form_adicionar_dependente .botao_coluna');
                        escondeElemento('#auth_form_adicionar_dependente .dependente_conteudo');
                        document.querySelector(
                            '#auth_form_adicionar_dependente .botao_coluna .adicionar_dependente'
                        ).textContent = 'Adicionar um novo dependente';
                        form.querySelector('input[data-name=nome_dependente]').value = '';
                        form.querySelector('input[data-name=cpf_dependente]').value = '';
                        form.querySelector('select[name=sexo_dependente]').value = '';
                        form.querySelector('input[name=email_dependente]').value = '';
                    }
                }
            })
            .catch(error => {
                Alerta.mensagem(
                    'Erro ao criar dependente!',
                    'Ocorreu um erro ao enviar os dados, por favor, tente novamente.'
                );
            })
            .finally(() => {
                Loading.hide();
            });
    });
