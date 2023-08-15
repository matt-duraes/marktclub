window.addEventListener('load', () => {
    const hashPopupSimple = document.getElementById('hash_simples');
    if (hashPopupSimple) {
        const notificationContainer = document.getElementById('popup_campanha_simples');
        const notification = document.querySelector('.campanha_conteudo');
        const fechaBotaoPopup = document.querySelector('.fechar');

        fechaBotaoPopup.addEventListener('click', () => {
            notificationContainer.style.display = 'none';
        });

        setTimeout(() => {
            notification.style.display = 'flex';
            if (notification) {
                notification.classList.add('animated', 'delay-2s', 'slideInRight');
            }
        }, 5000);
    }

    const carregarFuncoesPopupEnquete = () => {
        const botaoFechar = document.querySelector('.botao_fechar_popup');

        botaoFechar.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    };
    const hashPopupEnquete = document.getElementById('hash_enquete');
    if (hashPopupEnquete) {
        hashPopupEnquete.remove();
        const idPopupEnquete = hashPopupEnquete.getAttribute('data-popup-enquete');
        const PaginaBuscaPopupEnquete = new Pagina(
            'Busca',
            LINK + '/enquete-popup/' + idPopupEnquete,
            {},
            true,
            true,
            carregarFuncoesPopupEnquete
        );

        setTimeout(() => {
            PaginaBuscaPopupEnquete.abrir();
        }, 2000);
    }

    const carregarFuncoesPopupEnqueteImagem = () => {
        const botaoFechar = document.querySelector('.botao_fechar_popup');

        botaoFechar.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    };

    const hashPopupImagem = document.getElementById('hash_imagem');
    if (hashPopupImagem) {
        hashPopupImagem.remove();
        const idPopupEnquete = hashPopupImagem.getAttribute('data-popup-imagem');
        const PaginaBuscaPopupEnquete = new Pagina(
            'Busca',
            LINK + '/enquete-imagem/' + idPopupEnquete,
            {},
            true,
            true,
            carregarFuncoesPopupEnqueteImagem
        );

        setTimeout(() => {
            PaginaBuscaPopupEnquete.abrir();
        }, 2000);

        document.querySelector('body').addEventListener('click', function (event) {
            if (event.target.classList.contains('abreRegulamentoPopup')) {
                document.querySelector('.regulamento_texto').classList.add('mostra');
            }
        });
    }
});
