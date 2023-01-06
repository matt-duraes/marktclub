// @template "painel"
// @import "detalhe_demanda"
// @import "nova_demanda"
// @import "editar_tarefa"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

    /*
    |--------------------------------------------------------------------------
    | ABRIR ADD NOVO
    |--------------------------------------------------------------------------
    */
    const botaoAdd = document.getElementById('botao_add_tarefa');
    const PaginaAddTarefa = new Pagina('nova-tarefa', LINK + '/demanda/nova', {}, true, true, demandaNova);

    botaoAdd.addEventListener('click', () => {
        PaginaAddTarefa.abrir();
    });

    /*
    |--------------------------------------------------------------------------
    | ABRIR DETALHE DA DEMANDA
    |--------------------------------------------------------------------------
    */
    const tarefaLista = document.querySelectorAll('#bloco_demanda_index article');
    tarefaLista.forEach(tarefa => {
        const id = tarefa.getAttribute('data-id');
        const PaginaDetalhe = new Pagina(
            'tarefa-' + id,
            LINK + '/demanda/tarefa/' + id,
            {},
            true,
            true,
            detalheDemanda
        );
        tarefa.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });

    const marcacaoEquipe = ['andre.rodrigues', 'mateus.cunha', 'mateus.duram'];
    const texto = document.getElementById('input_historico_novo');
    let marcacaoAtiva = false;
    let marcacaoNome = '';
    texto.addEventListener('keydown', e => {
        const tecla = e.key;
        if (!marcacaoAtiva && tecla == '@') {
            marcacaoAtiva = true;
        } else if (marcacaoAtiva && /^[a-z\.]{1}$/.test(tecla)) {
            marcacaoNome += tecla;
            buscarListaUsuario();
        } else if (marcacaoAtiva && tecla == 'Backspace') {
            marcacaoNome = marcacaoNome.slice(0, -1);
        }
    });

    const buscarListaUsuario = () => {
        let nomeExiste = [];
        marcacaoEquipeIndice.find(el => {
            const nome = converterNome(el);
            const nomeComperacao = converterNome(marcacaoNome);
            if (nome.startsWith(nomeComperacao)) {
                nomeExiste.push(el);
            }
        });
        // console.log(nomeExiste);
    };
    const converterNome = nome => {
        return nome
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase();
    };
});
