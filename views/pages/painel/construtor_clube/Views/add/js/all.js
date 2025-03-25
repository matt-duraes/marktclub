// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const listaCheckbox = document.querySelectorAll('.input_checkbox');
    const inputGrupoLabel = document.querySelector('#input_grupo_label');
    const inputGrupoPlaceholder = document.querySelector('#input_grupo_placeholder');

    listaCheckbox.forEach(checkbox => {
        if (checkbox.children[0].value == 'grupo') {
            if (!checkbox.children[0].checked) {
                inputGrupoLabel.parentNode.classList.add('display_none');
                inputGrupoPlaceholder.parentNode.classList.add('display_none');
            }

            checkbox.addEventListener('click', () => {
                if (checkbox.children[0].checked) {
                    inputGrupoLabel.parentNode.classList.remove('display_none');
                    inputGrupoPlaceholder.parentNode.classList.remove('display_none');
                    return;
                }

                inputGrupoLabel.parentNode.classList.add('display_none');
                inputGrupoPlaceholder.parentNode.classList.add('display_none');
            });
        }
    });

    const inputSenhaStatus = $('.login_recuperar_senha_botao input');
    const blocoSenhaTipo = $('.login_recuperar_senha_tipo');
    const inputSenhaTipo = $('.login_recuperar_senha_tipo input');
    const blocoSenhaLink = $('.login_recuperar_senha_link');
    const inputSenhaLink = $('.login_recuperar_senha_link input');

    const inputAtivarStatus = $('.login_ativar_botao input');
    const blocoAtivarTipo = $('.login_ativar_tipo');
    const inputAtivarTipo = $('.login_ativar_tipo input');
    const blocoAtivarLink = $('.login_ativar_link');
    const inputAtivarLink = $('.login_ativar_link input');

    const inputCadastroStatus = $('.login_cadastro_botao input');
    const blocoCadastroTipo = $('.login_cadastro_tipo');
    const inputCadastroTipo = $('.login_cadastro_tipo input');
    const blocoCadastroLink = $('.login_cadastro_link');
    const inputCadastroLink = $('.login_cadastro_link input');

    inputSenhaStatus.evento('change', () => {
        loginEventoBotaoTipo(inputSenhaStatus, blocoSenhaTipo, blocoSenhaLink, inputSenhaTipo);
    });
    inputAtivarStatus.evento('change', () => {
        loginEventoBotaoTipo(inputAtivarStatus, blocoAtivarTipo, blocoAtivarLink, inputAtivarTipo);
    });
    inputCadastroStatus.evento('change', () => {
        loginEventoBotaoTipo(inputCadastroStatus, blocoCadastroTipo, blocoCadastroLink, inputCadastroTipo);
    });
    const loginEventoBotaoTipo = (input, bloco, blocoLink, inputLimpar) => {
        inputLimpar.valor('');
        if (input.checked) {
            bloco.aparecer();
            return;
        }
        blocoLink.sumir();
        bloco.sumir();
    };
    if (inputSenhaStatus.checked) {
        blocoSenhaTipo.aparecer();
    }
    if (inputSenhaTipo.valor() == 'link') {
        blocoSenhaLink.aparecer();
    }
    if (inputAtivarStatus.checked) {
        blocoAtivarTipo.aparecer();
    }
    if (inputAtivarTipo.valor() == 'link') {
        blocoAtivarLink.aparecer();
    }
    if (inputCadastroStatus.checked) {
        blocoCadastroTipo.aparecer();
    }
    if (inputCadastroTipo.valor() == 'link') {
        blocoCadastroLink.aparecer();
    }

    inputSenhaTipo.evento('formChange', () => {
        loginEventoLink(inputSenhaTipo, blocoSenhaLink, inputSenhaLink);
    });
    inputAtivarTipo.evento('formChange', () => {
        loginEventoLink(inputAtivarTipo, blocoAtivarLink, inputAtivarLink);
    });
    inputCadastroTipo.evento('formChange', () => {
        loginEventoLink(inputCadastroTipo, blocoCadastroLink, inputCadastroLink);
    });
    const loginEventoLink = (input, bloco, inputLimpar) => {
        inputLimpar.valor('');
        if (input.valor() == 'botao') {
            bloco.sumir();
            return;
        }
        bloco.aparecer();
    };
});
