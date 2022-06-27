window.addEventListener('load', () => {
    // const botaoAbrir = document.getElementById('botao_config_alterar_senha');
    // const blocoConfig = document.getElementById('bloco_config_template');
    // const paginaSenha = new Pagina('Alterar senha', LINK + '/perfil/senha');
    // botaoAbrir.addEventListener('click', () => {
    //     blocoConfig.classList.remove('aberto');
    //     setTimeout(() => {
    //         blocoConfig.classList.add('fechado');
    //     }, 320);
    //     paginaSenha.abrir();
    // });
    // document.addEventListener('keydown', e => {
    //     const target = e.target;
    //     if (
    //         (target.getAttribute('id') == 'input_senha_atual' ||
    //             target.closest('#input_senha_atual') ||
    //             target.getAttribute('id') == 'input_senha_nova' ||
    //             target.closest('#input_senha_nova') ||
    //             target.getAttribute('id') == 'input_senha_repetir' ||
    //             target.closest('#input_senha_repetir')) &&
    //         e.key == 'Enter'
    //     ) {
    //         botaoAtualizarSenha();
    //     }
    // });
    // document.addEventListener('click', e => {
    //     const target = e.target;
    //     if (target.getAttribute('id') == 'botao_salvar_nova_senha' || target.closest('#botao_salvar_nova_senha')) {
    //         botaoAtualizarSenha();
    //     }
    //     if (
    //         (target.classList.contains('botao_fechar_popup') || target.closest('.botao_fechar_popup')) &&
    //         target.closest('#bloco_alterar_senha')
    //     ) {
    //         paginaSenha.fechar();
    //     }
    // });
    // const botaoAtualizarSenha = async () => {
    //     const botao = document.getElementById('botao_salvar_nova_senha');
    //     if (botao.classList.contains('aguarde')) {
    //         return;
    //     }
    //     botao.classList.add('aguarde');
    //     const inputSenhaAtual = document.getElementById('input_senha_atual');
    //     const inputSenhaNova = document.getElementById('input_senha_nova');
    //     const inputSenhaRepetir = document.getElementById('input_senha_repetir');
    //     const inputHash = document.querySelector('#bloco_alterar_senha input[name=form_system_hash]');
    //     let body = new FormData();
    //     body.append('senha_atual', inputSenhaAtual.value);
    //     body.append('senha_nova', inputSenhaNova.value);
    //     body.append('senha_repetir', inputSenhaRepetir.value);
    //     body.append('form_system_hash', inputHash.value);
    //     body.append('form_system_validacao', '');
    //     const response = await fetch(LINK + '/perfil/senha', {
    //         method: 'POST',
    //         body,
    //     });
    //     botao.classList.remove('aguarde');
    //     if (response.status == 204) {
    //         paginaSenha.fechar();
    //         Alerta.notificacao('Senha alterada com sucesso!', true);
    //         return;
    //     }
    //     fetchNotificacaoErro(response, 'Ocorreu um erro ao atualizar sua senha.');
    // };
});
