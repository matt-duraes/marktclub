window.addEventListener('load', () => {
    let requisicaoId;

    const pegarElementoModelo = id => {
        const bloco = document.getElementById(id);
        bloco.removeAttribute('id');
        return bloco;
    };

    const blocoAbaModelo = pegarElementoModelo('bloco_aba_modelo');
    const blocoAbaLista = document.getElementById('bloco_aba_lista');
    const botaoNovaAba = document.getElementById('botao_nova_aba');

    const blocoRequestModelo = pegarElementoModelo('bloco_request_modelo');
    const blocoRequestLista = document.getElementById('bloco_request_lista');

    const blocoVazio = document.getElementById('bloco_vazio');

    /*
    |--------------------------------------------------------------------------
    | MENU
    |--------------------------------------------------------------------------
    */
    // Abre e fecha grupo
    const menuGrupoLista = document.querySelectorAll('.bloco_menu .grupo');
    menuGrupoLista.forEach(grupo => {
        const botao = grupo.querySelector('.nome');
        botao.addEventListener('click', e => {
            if (e.target.classList.contains('.nome') || e.target.closest('.nome')) {
                grupo.classList.toggle('fechado');
            }
        });
    });
    // Abre rota
    const menuRotaLista = document.querySelectorAll('.bloco_menu .request');
    menuRotaLista.forEach(botao => {
        botao.addEventListener('click', () => {
            const id = botao.getAttribute('data-id');
            const metodo = botao.getAttribute('data-metodo');
            const uri = botao.getAttribute('data-uri');
            adicionarNovaAba(id, false, metodo, uri);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ABA
    |--------------------------------------------------------------------------
    */
    const fecharAba = aba => {
        const ativo = aba.classList.contains('ativa');
        aba.parentNode.removeChild(aba);
        if (ativo) {
            abrirPrimeiraAba();
        }
    };
    const abrirPrimeiraAba = () => {
        const lista = blocoAbaLista.querySelectorAll('.aba');
        if (lista.length == 0) {
            blocoVazio.classList.remove('display_none');
            blocoRequestLista.classList.add('display_none');
            return;
        }
        lista[0].classList.add('ativa');
    };
    blocoAbaLista.addEventListener('click', e => {
        if (e.target.classList.contains('fechar') || e.target.closest('.fechar')) {
            fecharAba(e.target.closest('.aba'));
            return;
        }
        const aba = e.target.closest('.aba');
        const id = aba.getAttribute('data-id');
        const metodo = aba.getAttribute('data-metodo');
        const uri = aba.getAttribute('data-uri');
        adicionarNovaAba(id);
    });

    const adicionarNovaAba = (id, metodo, uri) => {
        requisicaoId = id;
        const abaAtiva = blocoAbaLista.querySelector('.aba.ativa');
        const abaExiste = blocoAbaLista.querySelector('#bloco_aba_' + id);
        if (abaAtiva && abaExiste && abaAtiva == abaExiste) {
            abaExiste.scrollIntoView();
            return;
        } else if (abaAtiva) {
            abaAtiva.classList.remove('ativa');
        }

        abrirRota(id);
        if (abaExiste) {
            abaExiste.classList.add('ativa');
            abaExiste.scrollIntoView();
            return;
        }

        const clone = blocoAbaModelo.cloneNode(true);
        clone.setAttribute('id', 'bloco_aba_' + id);
        clone.setAttribute('data-id', id);
        clone.querySelector('.metodo').innerText = metodo;
        clone.querySelector('.uri').innerText = uri;
        blocoAbaLista.appendChild(clone);
        blocoAbaLista.scrollLeft = blocoAbaLista.scrollWidth;
    };
    botaoNovaAba.addEventListener('click', () => {
        const id = 'id_' + Math.floor(Date.now() * Math.random()).toString(36);
        adicionarNovaAba(id, 'GET', 'Temporario');
    });

    /*
    |--------------------------------------------------------------------------
    | PEGAR DADO ROTA
    |--------------------------------------------------------------------------
    */
    const abrirRota = id => {
        const blocoExiste = blocoRequestLista.querySelector('#bloco_request_' + id);
        const blocoAtivo = blocoRequestLista.querySelector('.bloco_request.ativo');

        if (blocoExiste && blocoExiste == blocoAtivo) {
            return;
        } else if (blocoExiste) {
            blocoExiste.classList.remove('display_none');
            blocoExiste.classList.add('ativo');
        }

        if (blocoAtivo) {
            blocoAtivo.classList.add('display_none');
            blocoAtivo.classList.remove('ativo');
        }

        if (blocoExiste) {
            return;
        }

        const clone = blocoRequestModelo.cloneNode(true);
        clone.setAttribute('id', 'bloco_request_' + id);
        blocoRequestLista.appendChild(clone);
        blocoRequestLista.classList.remove('display_none');
        blocoVazio.classList.add('display_none');

        buscarDadoRequest(id, clone);
    };

    const buscarDadoRequest = async (id, bloco) => {
        bloco.classList.add('loading');

        const body = new FormData();
        body.append('acao', 'buscar');
        body.append('id', id);
        const resposta = await fetch('__postman', {
            method: 'POST',
            body,
        });
        let json;
        try {
            json = await resposta.json();
        } catch (e) {
            json = {};
        }
        if (json.uri == undefined) {
            Alerta.notificacao('Erro o carregar dados, por favor, tente novamente.', false);
            fecharAba(document.querySelector('#bloco_app_' + id));
            return;
        }
        const inputToken = bloco.querySelector('.input_token');
        const inputMetodo = bloco.querySelector('.input_metodo');
        const inputUri = bloco.querySelector('.input_uri');
        inputToken.value = json.token;
        inputMetodo.value = json.metodo;
        inputUri.value = json.uri;
    };
});
