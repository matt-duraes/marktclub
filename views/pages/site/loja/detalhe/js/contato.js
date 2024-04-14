window.addEventListener('load', () => {
    const idLoja = $('#input_loja_id').value;
    const popupBusca = (id, mensagem, tipo) => {
        const bloco = $('#' + id);
        if (!bloco) {
            return;
        }
        const PopupAbrir = new Popup(tipo + '-parceiro', id, true, false);
        const botao = $('#botao_' + tipo + '_abrir');
        const padrao = $('#bloco_' + tipo + '_padrao');
        const lista = $('#bloco_' + tipo + '_lista');

        let pagina = 0;
        botao.evento('click', () => {
            pagina = 0;
            buscarContato(true);
        });
        const buscarContato = async abrir => {
            pagina++;
            Loading.show();
            const resposta = await ajaxPost(
                LINK + '/contato/lista',
                {
                    local: 'loja',
                    id: idLoja,
                    tipo,
                    pagina,
                },
                mensagem
            );
            Loading.hide();
            if (false === resposta) {
                return;
            }

            if (true === abrir) {
                PopupAbrir.abrir();
            }
            lista.html('');
            for (const item of resposta.dado.lista) {
                adicionarContato(padrao, lista, item);
            }
        };
    };
    popupBusca('bloco_telefone', 'Erro ao buscar lista de telefone, por favor, tente novamente.', 'telefone');
    popupBusca('bloco_email', 'Erro ao buscar lista de e-mail, por favor, tente novamente.', 'email');

    const adicionarContato = (padrao, lista, item) => {
        const clone = padrao.clonar();
        const blocoValor = $('p', clone);
        blocoValor.texto(item.valor);
        $('strong', clone).texto(item.titulo);
        lista.final(clone);
        const copiar = $('i', clone);
        copiar.evento('click', () => {
            blocoValor.copiar('Contato copiado com sucesso!');
        });
    };
});
