// @template "site"
// @system "Popup"

const atualizarEmail = bloco => {
    const botao = bloco.querySelector('.botao_atualizar_email');
    botao.addEventListener('click', () => {
        ppe('teste');
    });
};
window.addEventListener('load', () => {
    const botaoAbrirPopupAtualizar = $('#botao_samsung_atualizar');
    const PopupAtualizar = new Popup('atualizar-dado', $('#bloco_atualizar_email'), true, true, atualizarEmail);

    botaoAbrirPopupAtualizar.addEventListener('click', () => {
        PopupAtualizar.abrir();
    });
});
