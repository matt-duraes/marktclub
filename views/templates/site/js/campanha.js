window.addEventListener('load', async () => {
    const resposta = await ajaxGet(LINK + '/campanha-voucher-disponivel');
    const menuNetshoes = $('#voucher_netshoes');
    const dado = resposta.dado.dado;
    if (dado.temVoucher == 'sim') {
        menuNetshoes.classList.remove('display_none');
    }
});
