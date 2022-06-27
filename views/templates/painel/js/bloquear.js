window.addEventListener('load', () => {
    // const blocoSite = document.getElementById('site');
    // const blocoBloquear = document.getElementById('bloco_bloquear');
    // const botao = document.getElementById('botao_config_bloquear_sessao');
    // const botaoLogin = document.getElementById('botao_login_desbloquear');
    // const blocoConfig = document.getElementById('bloco_config_template');
    // const desbloquearHash = document.querySelector(
    //     '#bloco_bloquear input[name=form_system_hash]'
    // ).value;
    // const desbloquearCpf = document.getElementById(
    //     'input_desbloquear_login'
    // ).value;
    // const inputBloqueioSenha = document.getElementById(
    //     'input_desbloquear_senha'
    // );
    // const desbloquearLogado = document.getElementById(
    //     'input_desbloqueio_ficar_logado'
    // );
    // let bloqueioAberto = false;
    // botao.addEventListener('click', () => {
    //     fetch(LINK + '/login/bloquear', { method: 'GET' });
    //     blocoConfig.classList.remove('aberto');
    //     setTimeout(() => {
    //         blocoConfig.classList.add('fechado');
    //     }, 320);
    //     blocoBloquear.style.display = 'flex';
    //     setTimeout(() => {
    //         blocoBloquear.classList.add('abrir_bloqueio');
    //     }, 20);
    //     setTimeout(() => {
    //         blocoSite.style.display = 'none';
    //     }, 520);
    //     setTimeout(() => {
    //         bloqueioAberto = true;
    //     }, 540);
    // });
    // const verificarSeEstaoBurlandoBloqueio = () => {
    //     if (bloqueioAberto) {
    //         blocoSite.innerHTML = '';
    //         window.location.reload();
    //     }
    // };
    // const observer = new MutationObserver(verificarSeEstaoBurlandoBloqueio);
    // observer.observe(blocoSite, { attributes: true });
    // inputBloqueioSenha.addEventListener('keydown', e => {
    //     if (e.key == 'Enter') {
    //         e.preventDefault();
    //         acaoParaDesbloquearTela();
    //     }
    // });
    // botaoLogin.addEventListener('click', () => {
    //     acaoParaDesbloquearTela();
    // });
    // const acaoParaDesbloquearTela = async () => {
    //     if (botaoLogin.classList.contains('aguarde')) {
    //         return;
    //     }
    //     botaoLogin.classList.add('aguarde');
    //     let body = new FormData();
    //     body.append('logado', desbloquearLogado.checked ? 1 : 0);
    //     body.append('login', desbloquearCpf);
    //     body.append('senha', inputBloqueioSenha.value);
    //     body.append('form_system_hash', desbloquearHash);
    //     body.append('form_system_validacao', '');
    //     const response = await fetch(LINK + '/login/desbloquear', {
    //         method: 'POST',
    //         body,
    //     });
    //     if (response.status == 201) {
    //         desbloquearTela();
    //         setTimeout(() => {
    //             botaoLogin.classList.remove('aguarde');
    //         }, 1000);
    //         return;
    //     }
    //     botaoLogin.classList.remove('aguarde');
    //     fetchNotificacaoErro(
    //         response,
    //         'Erro ao fazer login, por favor, tente novamente.'
    //     );
    // };
    // const desbloquearTela = () => {
    //     bloqueioAberto = false;
    //     setTimeout(() => {
    //         blocoSite.style.display = 'flex';
    //         blocoBloquear.classList.remove('abrir_bloqueio');
    //     }, 40);
    //     setTimeout(() => {
    //         inputBloqueioSenha.value = '';
    //         blocoBloquear.style.display = 'none';
    //     }, 520);
    // };
});
