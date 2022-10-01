// @template "painel"
// @system "Icone"
// @system "Form"
// @system "Calendario"

window.addEventListener('load', () => {
    const LINK = document.querySelector('#LINK').value;

    const googleClientId = document.getElementById('GOOGLE_CLIENT_ID').value;
    const client = google.accounts.oauth2.initTokenClient({
        // eslint-disable-next-line camelcase
        client_id: googleClientId,
        scope: 'https://www.googleapis.com/auth/calendar.readonly',
        callback: response => {
            console.log(response);
        },
    });

    return;
    const usuarioEmail = document.querySelector('#USUARIO_EMAIL').value;
    const usuarioImagem = document.querySelector('#USUARIO_IMAGEM').value;
    const blocoAgendaSemana = document.querySelector('#bloco_agenda_semana');
    const blocoAgendaConteudo = document.querySelector('#bloco_agenda_conteudo');
    const blocoConectar = document.querySelector('#bloco_conectar');
    const botaoSincronizarAgenda = document.querySelector('#botao_sincronizar_agenda');
    const botaoDessincronizarAgenda = document.querySelector('#botao_dessincronizar_agenda');

    let dadoAgenda = {};
    let eventoId = '';

    const bodyHtml = document.querySelector('body');

    Calendario.init({
        de: 'input_agenda_data_inicial',
        ate: 'input_agenda_data_final',
    });

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */
    const blocoHeaderH1 = document.querySelector('#header_template .bloco_app h1');
    blocoHeaderH1.insertAdjacentHTML(
        'afterend',
        `
            <div id="bloco_agenda_header">
                <div class="botao_hoje">HOJE</div>
                <div class="seta anterior">${Icone.setaEsquerda(14)}</div>
                <div class="seta proximo">${Icone.setaDireita(14)}</div>
                <p></p>
            </div>
        `
    );

    const blocoHeader = document.querySelector('#bloco_agenda_header');
    const botaoHeaderHoje = blocoHeader.querySelector('.botao_hoje');
    const botaoHeaderAnterior = blocoHeader.querySelector('.anterior');
    const botaoHeaderProximo = blocoHeader.querySelector('.proximo');
    const blocoHeaderMesAno = blocoHeader.querySelector('p');

    /*
    |--------------------------------------------------------------------------
    | HTML DO DETALHE DO EVENTO
    |--------------------------------------------------------------------------
    */
    const blocoDetalhe = document.querySelector('#bloco_agenda_detalhe');
    const botaoDetalheFechar = blocoDetalhe.querySelector('.fechar');
    const botaoDetalheDeletar = blocoDetalhe.querySelector('.deletar');
    const botaoDetalheEditar = blocoDetalhe.querySelector('.editar');
    const blocoDetalheTitulo = blocoDetalhe.querySelector('header h1');
    const blocoDetalheData = blocoDetalhe.querySelector('header .data');
    const blocoDetalheTexto = blocoDetalhe.querySelector('header .texto');

    const blocoDetalheParticipar = blocoDetalhe.querySelector('.bloco_participar');
    const botaoDetalheParticiparSim = blocoDetalhe.querySelector('#botao_confirmar_sim');
    const botaoDetalheParticiparNao = blocoDetalhe.querySelector('#botao_confirmar_nao');
    const botaoDetalheParticiparTalvez = blocoDetalhe.querySelector('#botao_confirmar_talvez');

    const blocoDetalheVideo = blocoDetalhe.querySelector('.video');
    const blocoDetalheVideoImagem = blocoDetalhe.querySelector('.video .imagem img');
    const blocoDetalheVideoLink = blocoDetalhe.querySelector('.video a');
    const blocoDetalheVideoCopiar = blocoDetalhe.querySelector('.video .copiar');
    const blocoDetalheLocal = blocoDetalhe.querySelector('.local');
    const blocoDetalheLocalTexto = blocoDetalhe.querySelector('.local p');
    const blocoDetalheConvidado = blocoDetalhe.querySelector('.convidado');
    const blocoDetalheConvidadoTitulo = blocoDetalhe.querySelector('.convidado h2');
    const blocoDetalheConvidadoLista = blocoDetalhe.querySelector('.convidado .lista');
    const blocoDetalheDono = blocoDetalhe.querySelector('.dono');
    const blocoDetalheDonoImagem = blocoDetalhe.querySelector('.dono figure');
    const blocoDetalheDonoTexto = blocoDetalhe.querySelector('.dono p');
    const blocoDetalheArquivo = blocoDetalhe.querySelector('.arquivo');
    const blocoDetalheArquivoLista = blocoDetalhe.querySelector('.arquivo .lista');
    const blocoDetalheAgenda = blocoDetalhe.querySelector('.agenda');

    /*
    |--------------------------------------------------------------------------
    | HTML DO ADD/EDITAR
    |--------------------------------------------------------------------------
    */
    const blocoSalvar = document.querySelector('#bloco_agenda_salvar');
    const blocoConvidado = blocoSalvar.querySelector('.convidado');
    const blocoConvidadoLista = blocoSalvar.querySelector('.convidado .lista');
    const blocoSalvarAtencao = blocoSalvar.querySelector('.atencao');
    const botaoSalvarVoltar = blocoSalvar.querySelector('.voltar');
    const botaoSalvarFechar = blocoSalvar.querySelector('.fechar');
    const botaoSalvarEvento = blocoSalvar.querySelector('#botao_salvar_evento');
    const botaoEditarEvento = blocoSalvar.querySelector('#botao_editar_evento');
    const inputTitulo = blocoSalvar.querySelector('#input_agenda_titulo');
    const blocoSalvarData = blocoSalvar.querySelector('.data');
    const inputDataInicial = blocoSalvar.querySelector('#input_agenda_data_inicial');
    const inputDataFinal = blocoSalvar.querySelector('#input_agenda_data_final');
    const inputHoraInicial = blocoSalvar.querySelector('#input_agenda_hora_inicial');
    const inputHoraFinal = blocoSalvar.querySelector('#input_agenda_hora_final');
    const inputDescricao = blocoSalvar.querySelector('#input_agenda_descricao');
    const inputLocal = blocoSalvar.querySelector('#input_agenda_local');
    const blocoSalvarVideo = blocoSalvar.querySelector('.video');
    const inputVideo = blocoSalvar.querySelector('#input_agenda_video');
    const inputConvidado = blocoSalvar.querySelector('#input_agenda_convidado');
    const blocoNotificar = blocoSalvar.querySelector('#bloco_agenda_notificar');
    const inputNotificar = blocoSalvar.querySelector('#input_agenda_notificar');

    /*
    |--------------------------------------------------------------------------
    | VALIDAR HORA DO EVENTO
    |--------------------------------------------------------------------------
    */
    inputHoraInicial.addEventListener('focus', () => {
        inputHoraInicial.select();
    });
    inputHoraFinal.addEventListener('focus', () => {
        const dataInicio = inputDataInicial.value.trim();
        const dataFinal = inputDataFinal.value.trim();

        if (dataInicio != dataFinal) {
            inputHoraFinal.select();
            return;
        }

        const inicio = inputHoraInicial.value.trim();
        const final = inputHoraFinal.value.trim();

        let hora, minuto;
        if (!/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9])$/.test(inicio)) {
            return;
        } else if (final == '') {
            hora = parseInt(inicio.split(':')[0]) + 1;
            if (hora >= 23) {
                inputHoraFinal.value = '';
                return;
            }

            minuto = inicio.split(':')[1];
            hora = hora < 10 ? '0' + hora : hora;
            inputHoraFinal.value = hora + ':' + minuto;
            inputHoraFinal.select();
            return;
        } else if (!/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9])$/.test(final)) {
            inputHoraFinal.value = '';
            return;
        }

        const inicioExplode = inicio.split(':');
        const finalExplode = final.split(':');
        if (
            parseInt(inicioExplode[0]) > parseInt(finalExplode[0]) ||
            (parseInt(inicioExplode[0]) == parseInt(finalExplode[0]) &&
                parseInt(inicioExplode[1]) >= parseInt(finalExplode[1]))
        ) {
            hora = parseInt(inicioExplode[0]);
            minuto = parseInt(inicioExplode[1]) + 10;
            if (hora == 23 && minuto > 59) {
                inputHoraFinal.value = '';
                return;
            }
            if (minuto > 59) {
                hora++;
                minuto = minuto - 60;
            }
            if (hora > 23) {
                inputHoraFinal.value = '';
                return;
            }

            hora = hora < 10 ? '0' + hora : hora;
            minuto = minuto < 10 ? '0' + minuto : minuto;
            inputHoraFinal.value = hora + ':' + minuto;
        }
        inputHoraFinal.select();
    });
    inputHoraInicial.addEventListener('blur', () => {
        const valor = inputHoraInicial.value.trim();
        if (valor != '' && !/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9])$/.test(valor)) {
            inputHoraInicial.value = '';
            Alerta.notificacao('Digite uma hora de início do evento válida!', false);
        }
    });
    inputHoraFinal.addEventListener('blur', () => {
        const valor = inputHoraFinal.value.trim();
        if (valor != '' && !/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9])$/.test(valor)) {
            inputHoraFinal.value = '';
            Alerta.notificacao('Digite uma hora de final do evento válida!', false);
            return;
        }

        const dataInicio = inputDataInicial.value.trim();
        const dataFinal = inputDataFinal.value.trim();
        if (dataInicio != dataFinal) {
            return;
        }

        const inicio = inputHoraInicial.value.trim();
        if (inicio == '') {
            return;
        }

        const inicioExplode = inicio.split(':');
        const finalExplode = inputHoraFinal.value.trim().split(':');
        if (
            parseInt(inicioExplode[0]) > parseInt(finalExplode[0]) ||
            (parseInt(inicioExplode[0]) == parseInt(finalExplode[0]) &&
                parseInt(inicioExplode[1]) >= parseInt(finalExplode[1]))
        ) {
            inputHoraFinal.value = '';
            Alerta.notificacao('A hora de final do evento não pode ser menor ou igual a hora de início.', false);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | MONTAR HTML
    |--------------------------------------------------------------------------
    */
    const listaMesExtenso = {
        1: 'Janeiro',
        2: 'Fevereiro',
        3: 'Março',
        4: 'Abril',
        5: 'Maio',
        6: 'Junho',
        7: 'Julho',
        8: 'Agosto',
        9: 'Setembro',
        10: 'Outubro',
        11: 'Novembro',
        12: 'Dezembro',
    };
    let mesAtualEmUso;
    let anoAtualEmUso;
    const dateHojeGeral = new Date();
    const anoHojeGeral = dateHojeGeral.getFullYear();
    const mesHojeGeral =
        dateHojeGeral.getMonth() + 1 < 10 ? '0' + (dateHojeGeral.getMonth() + 1) : dateHojeGeral.getMonth() + 1;
    const diaHojeGeral = dateHojeGeral.getDate();
    const diaHoje = anoHojeGeral + '-' + mesHojeGeral + '-' + diaHojeGeral;

    const montarHtmlDaAgenda = (anoAtual, mesAtual) => {
        blocoAgendaConteudo.innerHTML = '';

        const dataHoje = new Date();
        if (anoAtual == undefined) {
            anoAtual = dataHoje.getFullYear();
        }
        if (mesAtual == undefined) {
            mesAtual = dataHoje.getMonth() + 1 < 10 ? '0' + (dataHoje.getMonth() + 1) : dataHoje.getMonth() + 1;
        }
        const mesAnterior = mesAtual == 1 ? 12 : parseInt(mesAtual) - 1;
        const anoAnterior = mesAtual == 1 ? parseInt(anoAtual) - 1 : anoAtual;
        const mesPosterior = mesAtual == 12 ? 1 : parseInt(mesAtual) + 1;
        const anoPosterior = mesAtual == 12 ? parseInt(anoAtual) + 1 : anoAtual;

        const quantidadeDiasDoMes = new Date(anoAtual, mesAtual, 0).getDate();
        const quantidadeDiasDoMesAnterior = new Date(anoAnterior, mesAnterior, 0).getDate();
        const primeiroDiaDaSemana = new Date(anoAtual, mesAtual - 1, 1).getDay();
        const ultimoDiaDaSemana = new Date(anoAtual, mesAtual - 1, quantidadeDiasDoMes).getDay();

        let i, data, dia, classeBotao, botaoMais;
        let mes = mesAnterior < 10 ? '0' + mesAnterior : mesAnterior;
        const arrayDiasAnterior = [];
        for (i = 0; i < primeiroDiaDaSemana; ++i) {
            dia = quantidadeDiasDoMesAnterior - i;
            data = anoAnterior + '-' + mes + '-' + dia;
            classeBotao = '';
            botaoMais = '';
            if (diaHoje <= data) {
                classeBotao = 'botao_adicionar_evento_geral';
                botaoMais = `<div class="mais botao_adicionar_evento">${Icone.mais(12)}</div>`;
            }
            arrayDiasAnterior.push(`
            <div class="data ${classeBotao} agenda_outro_mes data-${data}" data-data="${data}">
                <div class="conteudo">
                    ${botaoMais}
                    <div class="numero">${dia}</div>
                    <div class="lista"></div>
                </div>
            </div>
        `);
        }
        blocoAgendaConteudo.insertAdjacentHTML('beforeend', arrayDiasAnterior.reverse().join(''));

        mes = mesAtual;
        for (i = 1; i <= quantidadeDiasDoMes; ++i) {
            dia = i >= 10 ? i : '0' + i;
            data = anoAtual + '-' + mes + '-' + dia;
            botaoMais = '';
            classeBotao = '';
            if (diaHoje <= data) {
                botaoMais = `<div class="mais botao_adicionar_evento">${Icone.mais(12)}</div>`;
                classeBotao = 'botao_adicionar_evento_geral';
            }
            blocoAgendaConteudo.insertAdjacentHTML(
                'beforeend',
                `
                <div class="data ${classeBotao} agenda_mes_atual data-${data}" data-data="${data}">
                    <div class="conteudo">
                        ${botaoMais}
                        <div class="numero">${dia}</div>
                        <div class="lista"></div>
                    </div>
                </div>
            `
            );
        }

        mes = mesPosterior < 10 ? '0' + mesPosterior : mesPosterior;
        for (i = 1; i <= 6 - ultimoDiaDaSemana; ++i) {
            data = anoPosterior + '-' + mes + '-0' + i;
            classeBotao = '';
            botaoMais = '';
            if (diaHoje <= data) {
                classeBotao = 'botao_adicionar_evento_geral';
                botaoMais = `<div class="mais botao_adicionar_evento">${Icone.mais(12)}</div>`;
            }
            blocoAgendaConteudo.insertAdjacentHTML(
                'beforeend',
                `
                <div class="data ${classeBotao} agenda_outro_mes data-${data}" data-data="${data}">
                    <div class="conteudo">
                        ${botaoMais}
                        <div class="numero">${i}</div>
                        <div class="lista"></div>
                    </div>
                </div>
            `
            );
        }

        const blocoDataAtual = document.querySelector('.data-' + diaHoje);
        if (blocoDataAtual) {
            blocoDataAtual.classList.add('data_hoje');
        }

        blocoHeaderMesAno.innerText = listaMesExtenso[parseInt(mesAtual)] + ' de ' + anoAtual;
        mesAtualEmUso = parseInt(mesAtual);
        anoAtualEmUso = parseInt(anoAtual);

        buscarAgenda(anoAtual + '-' + mesAtual + '-01', anoAtual + '-' + mesAtual + '-' + quantidadeDiasDoMes);
    };

    /*
    |--------------------------------------------------------------------------
    | AÇÕES PARA MUDAR DATA DO CALENDARIO
    |--------------------------------------------------------------------------
    */
    botaoHeaderHoje.addEventListener('click', () => {
        const data = new Date();
        let mes = data.getMonth() + 1;
        mes = mes < 10 ? '0' + mes : mes;
        montarHtmlDaAgenda(data.getFullYear(), mes);
    });
    botaoHeaderAnterior.addEventListener('click', () => {
        let mes = mesAtualEmUso - 1;
        let ano = anoAtualEmUso;
        if (mes == 0) {
            mes = 12;
            ano--;
        }
        if (mes < 10) {
            mes = '0' + mes;
        }
        montarHtmlDaAgenda(ano, mes);
    });
    botaoHeaderProximo.addEventListener('click', () => {
        let mes = mesAtualEmUso + 1;
        let ano = anoAtualEmUso;
        if (mes == 13) {
            mes = 1;
            ano++;
        }
        if (mes < 10) {
            mes = '0' + mes;
        }
        montarHtmlDaAgenda(ano, mes);
    });

    /*
    |--------------------------------------------------------------------------
    | GOOGLE CALENDARIO
    |--------------------------------------------------------------------------
    */
    const discovery = ['https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest'];
    const scopes = 'https://www.googleapis.com/auth/calendar.events';
    const clientId = '665762641381-8tl8n4qqeh0a14jhksiubhott1bqn2n2.apps.googleusercontent.com';
    const apiKey = 'AIzaSyAEkKrTBAkEI5dxuG1yCVAYX8ZaTxchdjo';

    const initClient = async () => {
        await gapi.client.init({
            apiKey: apiKey,
            clientId: clientId,
            discoveryDocs: discovery,
            scope: scopes,
        });
        const logado = gapi.auth2.getAuthInstance().isSignedIn.get();
        if (logado) {
            montarHtmlDaAgenda();
            blocoAgendaSemana.classList.add('show');
            blocoAgendaConteudo.classList.add('show');
            botaoDessincronizarAgenda.classList.add('show');
        } else {
            blocoConectar.classList.add('show');
            botaoDessincronizarAgenda.classList.remove('show');
        }
    };
    gapi.load('client:auth2', initClient);
    const validarSeEstaLogado = () => {
        const logado = gapi.auth2.getAuthInstance().isSignedIn.get();
        if (!logado) {
            Alerta.notificacao(
                'Sua sessão com o Google Calendario foi finalizada, refaça seu o login para continuar.',
                false
            );
            blocoConectar.classList.add('show');
            blocoAgendaSemana.classList.remove('show');
            blocoAgendaConteudo.classList.remove('show');
            botaoDessincronizarAgenda.classList.remove('show');
            return false;
        }
        return true;
    };

    botaoDessincronizarAgenda.addEventListener('click', async () => {
        const resposta = await Alerta.confirmar(
            'Desconectar',
            `
                Deseja desconectar sua agenda do painel? Você não conseguirá mais acompanhar
                sua agenda pelo painel mas poderá reconectá-la novamente.
            `,
            '!'
        );
        if (resposta) {
            gapi.auth2.getAuthInstance().signOut();
            blocoAgendaSemana.classList.remove('show');
            blocoAgendaConteudo.classList.remove('show');
            blocoConectar.classList.add('show');
            botaoDessincronizarAgenda.classList.remove('show');
        }
    });
    botaoSincronizarAgenda.addEventListener('click', async () => {
        const token = await gapi.auth2.getAuthInstance().signIn();
        montarHtmlDaAgenda();
        blocoAgendaSemana.classList.add('show');
        blocoAgendaConteudo.classList.add('show');
        blocoConectar.classList.remove('show');
        botaoDessincronizarAgenda.classList.add('show');
    });

    const pegarAccessToken = async () => {
        return new Promise(resolve => {
            const lista = gapi.auth2.getAuthInstance().currentUser.get();
            Object.keys(lista).forEach(item => {
                if (item == 'access_token') {
                    resolve(lista[item]);
                }
                if (typeof lista[item] == 'object') {
                    Object.keys(lista[item]).forEach(item2 => {
                        if (item2 == 'access_token') {
                            resolve(lista[item][item2]);
                        }
                    });
                }
            });
        });
    };

    const buscarAgenda = async (de, ate) => {
        if (!validarSeEstaLogado()) {
            return;
        }
        Loading.show();

        const body = new FormData();
        body.append('data_inicial', de);
        body.append('data_final', ate);
        const response = await fetch(LINK + '/agenda/buscar', {
            headers: {
                Authorization: await pegarAccessToken(),
            },
            body,
            method: 'POST',
        });

        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        if (response.status != 201) {
            Alerta.notificacao(
                json.mensagem != undefined
                    ? json.mensagem
                    : 'Ocorreu um erro ao sincronizar sua agenda, por favor, recarregue a página e tente novamente.',
                false
            );
            Loading.hide();
            return;
        }
        adicionarEventoNaAgenda(json.lista);
    };

    const adicionarEventoNaAgenda = evento => {
        let bloco, blocoEvento, blocoStatus;
        evento.forEach(item => {
            bloco = document.querySelector('.data-' + item.data.inicial + ' .lista');
            blocoEvento = bloco.querySelector('.botao[data-id="' + item.id + '"]');
            if (blocoEvento) {
                blocoEvento.querySelector('p').innerText = item.chamada;
                blocoStatus = blocoEvento.querySelector('.status');
                if (blocoStatus) {
                    blocoStatus.className = 'status ' + item.status;
                }
            } else {
                blocoStatus = '';
                if (item.status != '') {
                    blocoStatus = `<div class="status ${item.status}"></div>`;
                }
                bloco.insertAdjacentHTML(
                    'beforeend',
                    `
                    <div class="botao botao_detalhe_agenda" data-id="${item.id}">
                        ${blocoStatus} <p>${item.chamada}</p>
                    </div>
                `
                );
            }
            dadoAgenda[item.id] = item;
        });
        Loading.hide();
    };

    blocoAgendaConteudo.addEventListener('click', e => {
        const target = e.target;
        let botaoAbrirDetalhe = null;
        if (target.classList.contains('botao_detalhe_agenda')) {
            botaoAbrirDetalhe = target.classList.contains('botao_detalhe_agenda');
        } else if (target.closest('.botao_detalhe_agenda')) {
            botaoAbrirDetalhe = target.closest('.botao_detalhe_agenda');
        }
        if (botaoAbrirDetalhe) {
            abrirDadosDoEvento(botaoAbrirDetalhe.getAttribute('data-id'));
        }
        if (target.classList.contains('botao_adicionar_evento') || target.closest('.botao_adicionar_evento')) {
            const data = target.closest('.data').getAttribute('data-data');
            abrirAdicionarEvento(
                {
                    data: {
                        inicial: data,
                        final: data,
                    },
                },
                'salvar'
            );
        }
    });
    blocoAgendaConteudo.addEventListener('dblclick', e => {
        const target = e.target;
        let botaoAbrirDetalhe = null;
        if (target.classList.contains('botao_adicionar_evento_geral')) {
            botaoAbrirDetalhe = target.classList.contains('botao_adicionar_evento_geral');
        } else if (target.closest('.botao_adicionar_evento_geral')) {
            botaoAbrirDetalhe = target.closest('.botao_adicionar_evento_geral');
        }
        if (botaoAbrirDetalhe) {
            const data = botaoAbrirDetalhe.getAttribute('data-data');
            abrirAdicionarEvento(
                {
                    data: {
                        inicial: data,
                        final: data,
                    },
                },
                'salvar'
            );
        }
    });

    /*
    |--------------------------------------------------------------------------
    | ABRIR
    |--------------------------------------------------------------------------
    */
    const abrirDadosDoEvento = id => {
        const dado = dadoAgenda[id];
        if (!dado) {
            return;
        }

        bodyHtml.classList.add('body_agenda');

        blocoDetalhe.classList.add('show');
        setTimeout(() => {
            blocoDetalhe.classList.add('animar');
        }, 50);

        eventoId = dado.id;

        blocoDetalheTitulo.innerText = dado.titulo;
        blocoDetalheAgenda.setAttribute('href', dado.link);
        if (dado.inicial != '') {
            blocoDetalheData.innerText = dado.inicio;
            blocoDetalheData.classList.add('show');
        }
        if (dado.descricao != '') {
            blocoDetalheTexto.innerHTML = dado.descricao;
            blocoDetalheTexto.classList.add('show');
        }

        if (dado.video.link != '') {
            blocoDetalheVideo.classList.add('show');
            blocoDetalheVideoImagem.setAttribute('src', dado.video.imagem);
            blocoDetalheVideoLink.setAttribute('href', dado.video.link);
            blocoDetalheVideoLink.innerText = dado.video.nome;
        }
        if (dado.local != '') {
            blocoDetalheLocal.classList.add('show');
            blocoDetalheLocalTexto.innerText = dado.local;
        }
        if (dado.dono.nome != '') {
            blocoDetalheDono.classList.add('show');
            blocoDetalheDonoTexto.innerText = dado.dono.nome;
            blocoDetalheDonoImagem.style.backgroundImage = 'url(' + dado.dono.imagem + ')';
        }
        if (dado.convidado.length > 1) {
            blocoDetalheConvidado.classList.add('show');
            blocoDetalheConvidadoTitulo.innerText =
                dado.convidado.length == 1 ? '1 Convidado' : dado.convidado.length + ' Convidados';
            let check, convidadoEu;
            dado.convidado.forEach(item => {
                check = '';
                convidadoEu = '';
                if (item.status == 'sim') {
                    check = `<span class="check sim">${Icone.check(10)}</span>`;
                } else if (item.status == 'nao') {
                    check = `<span class="check nao">${Icone.fechar(8)}</span>`;
                } else if (item.status == 'talvez') {
                    check = `<span class="check talvez">${Icone.menos(1.5)}</span>`;
                }
                if (item.eu) {
                    blocoDetalheParticipar.classList.add('show');
                    convidadoEu = 'sou_eu';
                }
                if (item.eu && item.status == 'sim') {
                    botaoDetalheParticiparSim.classList.add('hover');
                } else if (item.eu && item.status == 'nao') {
                    botaoDetalheParticiparNao.classList.add('hover');
                } else if (item.eu && item.status == 'talvez') {
                    botaoDetalheParticiparTalvez.classList.add('hover');
                }
                blocoDetalheConvidadoLista.insertAdjacentHTML(
                    'afterbegin',
                    `
                        <div class="linha ${convidadoEu}">
                            <figure style="background-image: url(${item.imagem})"></figure>
                            <p>${item.nome}</p>
                            ${check}
                        </div>
                    `
                );
            });
        }

        if (dado.arquivo.length > 0) {
            blocoDetalheArquivo.classList.add('show');
            let imagem;
            dado.arquivo.forEach(item => {
                imagem = '';
                if (item.imagem != '') {
                    imagem = `<img src="${item.imagem}" height="14">`;
                }
                blocoDetalheArquivoLista.insertAdjacentHTML(
                    'afterbegin',
                    `
                        <a class="arquivo" href="${item.link}" target="_blank" rel="noopener noreferrer">${imagem}${item.nome}</a>
                    `
                );
            });
        }
    };

    botaoDetalheFechar.addEventListener('click', () => {
        fecharDadosDoEvento(true);
    });
    blocoDetalhe.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_agenda_detalhe') {
            fecharDadosDoEvento(true);
        }
    });
    const fecharDadosDoEvento = zerarId => {
        blocoDetalhe.classList.remove('animar');
        setTimeout(() => {
            if (zerarId) {
                bodyHtml.classList.remove('body_agenda');
                eventoId = '';
            }
            blocoDetalhe.classList.remove('show');
            blocoDetalheTitulo.innerText = '';
            blocoDetalheAgenda.setAttribute('href', '');
            blocoDetalheData.innerText = '';
            blocoDetalheData.classList.remove('show');
            blocoDetalheTexto.innerText = '';
            blocoDetalheTexto.classList.remove('show');
            blocoDetalheVideo.classList.remove('show');
            blocoDetalheVideoImagem.setAttribute('src', '');
            blocoDetalheVideoLink.setAttribute('href', '');
            blocoDetalheVideoLink.innerText = '';
            blocoDetalheLocal.classList.remove('show');
            blocoDetalheLocalTexto.innerText = '';
            blocoDetalheDono.classList.remove('show');
            blocoDetalheDonoTexto.innerText = '';
            blocoDetalheConvidado.classList.remove('show');
            blocoDetalheConvidadoLista.innerHTML = '';
            blocoDetalheArquivo.classList.remove('show');
            blocoDetalheArquivoLista.innerHTML = '';
            blocoDetalheParticipar.classList.remove('show');
            botaoDetalheParticiparSim.classList.remove('hover');
            botaoDetalheParticiparNao.classList.remove('hover');
            botaoDetalheParticiparTalvez.classList.remove('hover');
        }, 300);
    };

    blocoDetalheVideoCopiar.addEventListener('click', () => {
        if (navigator.clipboard.writeText(blocoDetalheVideoLink.getAttribute('href'))) {
            Alerta.notificacao('Link copiado com sucesso!', true);
        } else {
            Alerta.notificacao('Ocorreu um erro ao copiar link', false);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    botaoDetalheDeletar.addEventListener('click', async () => {
        const resposta = await Alerta.confirmar(
            'Deletar evento!',
            `
                Tem certeza que deseja deletar esse evento? Ele irá ser deletado do seu Google agenda também
                e essa ação não poderá ser desfeita.
            `,
            '!'
        );
        if (resposta) {
            deletarEvento();
        }
    });
    const deletarEvento = async () => {
        if (!validarSeEstaLogado()) {
            return;
        }
        Loading.show();
        const body = new FormData();
        body.append('id', eventoId);

        const resposta = await fetch(LINK + '/agenda/deletar', {
            headers: {
                Authorization: await pegarAccessToken(),
            },
            body,
            method: 'POST',
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();
        if (resposta.status != 204) {
            Alerta.notificacao(json.mensagem != undefined ? json.mensagem : 'Erro ao deletar esse evento.', false);
            return;
        }

        const botao = blocoAgendaConteudo.querySelector('.botao_detalhe_agenda[data-id="' + eventoId + '"]');
        if (botao) {
            botao.parentNode.removeChild(botao);
        }
        eventoId = '';
        Alerta.notificacao('Evento deletado com sucesso!', true);
        fecharDadosDoEvento(true);
    };

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */

    /**
     * Adiciona convidado
     */
    inputConvidado.addEventListener('keyup', e => {
        const email = inputConvidado.value.trim();
        if (e.key == 'Enter' && validarEmail(email)) {
            adicionarNovoConvidado(email);
        }
    });
    inputConvidado.addEventListener('blur', () => {
        const email = inputConvidado.value.trim();
        if (validarEmail(email)) {
            adicionarNovoConvidado(email);
        } else {
            inputConvidado.value = '';
        }
    });
    const adicionarNovoConvidado = email => {
        inputConvidado.value = '';

        const jaExiste = blocoConvidadoLista.querySelector('.item[data-email="' + email + '"]');
        if (jaExiste) {
            return;
        }

        let classe = 'outro';
        let imagem = 'https://localhost.com:4200/images/painel/usuario_padrao_preto.png';
        if (email == usuarioEmail) {
            classe = 'sou_eu';
            imagem = usuarioImagem;
        }
        blocoConvidadoLista.insertAdjacentHTML(
            'beforeend',
            `
                <div class="item ${classe}" data-email="${email}">
                    <figure style="background-image: url(${imagem})"></figure>
                    <p>${email}</p><i class="botao_remover_convidado">${Icone.fechar()}</i>
                </div>
            `
        );
        validarBotaoDeNotificar();
    };
    const validarEmail = email => {
        return /([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|"([]!#-[^-~ \t]|(\\[\t -~]))+")@([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\[[\t -Z^-~]*])/.test(
            email
        );
    };

    /**
     * Remove convidado
     */
    blocoConvidadoLista.addEventListener('click', e => {
        const target = e.target;
        if (!target.classList.contains('botao_remover_convidado') && !target.closest('.botao_remover_convidado')) {
            return;
        }
        const bloco = target.closest('.item');
        if (bloco) {
            bloco.parentNode.removeChild(bloco);
            validarBotaoDeNotificar();
        }
    });
    const validarBotaoDeNotificar = () => {
        const quantidade = blocoConvidadoLista.querySelectorAll('.item.outro').length;
        if (quantidade > 0) {
            blocoNotificar.classList.add('show');
        } else {
            blocoNotificar.classList.remove('show');
            inputNotificar.checked = false;
        }
    };

    /**
     * Salvar
     */
    const abrirAdicionarEvento = (dado, acao) => {
        blocoSalvar.classList.add('show');
        bodyHtml.classList.add('body_agenda');

        inputDataInicial.value = converterDataParaBr(dado.data.inicial);
        inputDataFinal.value = converterDataParaBr(dado.data.final);

        if (acao == 'editar') {
            botaoSalvarVoltar.classList.add('show');
            inputHoraInicial.value = dado.hora.inicial;
            inputHoraFinal.value = dado.hora.final;
            inputTitulo.value = dado.titulo;
            inputLocal.value = dado.local;
            inputDescricao.value = dado.descricao.replace(/\<\\? ?br\>/gi, '\n');
            inputDescricao.style.height = 0;

            const descricaoHeight = inputDescricao.scrollHeight < 45 ? 45 + 'px' : inputDescricao.scrollHeight + 'px';
            inputDescricao.style.height = descricaoHeight;

            if (dado.dono.eu) {
                blocoSalvarData.classList.add('show');
                blocoConvidado.classList.add('show');

                let convidadoClasse;
                dado.convidado.forEach(item => {
                    convidadoClasse = 'outro';
                    if (item.eu) {
                        convidadoClasse = 'sou_eu';
                    }
                    blocoConvidadoLista.insertAdjacentHTML(
                        'afterbegin',
                        `
                            <div class="item ${convidadoClasse}" data-email="${item.email}">
                                <figure style="background-image: url(${item.imagem})"></figure>
                                <p>${item.email}</p>
                                <i class="botao_remover_convidado">${Icone.fechar()}</i>
                            </div>
                        `
                    );
                });
                if (dado.convidado.length > 1 || (dado.convidado.length == 1 && !dado.convidado[0].eu)) {
                    blocoNotificar.classList.add('show');
                }

                blocoSalvarVideo.classList.add('show');
                inputVideo.checked = dado.video.link != '' ? true : false;
                blocoSalvarAtencao.classList.remove('show');
            } else {
                blocoConvidado.classList.remove('show');
                blocoSalvarAtencao.classList.add('show');
            }

            botaoEditarEvento.classList.add('show');
            botaoSalvarEvento.classList.remove('show');
        } else {
            blocoConvidado.classList.add('show');

            blocoSalvarData.classList.add('show');
            blocoSalvarVideo.classList.add('show');

            botaoEditarEvento.classList.remove('show');
            botaoSalvarEvento.classList.add('show');
        }

        setTimeout(() => {
            blocoSalvar.classList.add('animar');
        }, 50);
    };
    const converterDataParaBr = data => {
        const explode = data.split('-');
        return explode[2] + '/' + explode[1] + '/' + explode[0];
    };
    const fecharAdicionarEvento = zerarId => {
        blocoSalvar.classList.remove('animar');
        setTimeout(() => {
            botaoSalvarVoltar.classList.remove('show');
            blocoSalvar.classList.remove('show');
            inputTitulo.value = '';
            inputDataInicial.value = '';
            inputDataFinal.value = '';
            inputHoraInicial.value = '';
            inputHoraFinal.value = '';
            inputDescricao.value = '';
            inputLocal.value = '';
            inputVideo.checked = false;
            inputConvidado.value = '';
            blocoConvidadoLista.innerHTML = '';
            inputDescricao.style.height = 45 + 'px';
            inputNotificar.checked = false;

            if (zerarId) {
                bodyHtml.classList.remove('body_agenda');
                eventoId = '';
            }

            blocoSalvarAtencao.classList.remove('show');
            blocoSalvarData.classList.remove('show');
            blocoSalvarVideo.classList.remove('show');
            botaoEditarEvento.classList.remove('show');
            botaoSalvarEvento.classList.remove('show');
            blocoNotificar.classList.remove('show');
        }, 300);
    };

    botaoSalvarFechar.addEventListener('click', () => {
        fecharAdicionarEvento(true);
    });
    botaoSalvarVoltar.addEventListener('click', () => {
        fecharAdicionarEvento(false);
        abrirDadosDoEvento(eventoId);
    });
    blocoSalvar.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_agenda_salvar') {
            fecharAdicionarEvento(true);
        }
    });

    botaoSalvarEvento.addEventListener('click', async () => {
        if (!validarSeEstaLogado() || botaoSalvarEvento.classList.contains('aguarde')) {
            return;
        }
        botaoSalvarEvento.classList.add('aguarde');

        const body = new FormData();
        body.append('titulo', inputTitulo.value);
        body.append('data_inicial', inputDataInicial.value);
        body.append('data_final', inputDataFinal.value);
        body.append('hora_inicial', inputHoraInicial.value);
        body.append('hora_final', inputHoraFinal.value);
        body.append('descricao', inputDescricao.value);
        body.append('local', inputLocal.value);
        body.append('video', inputVideo.checked ? 1 : 0);

        const convidado = blocoConvidadoLista.querySelectorAll('.item p');
        if (convidado.length == 0) {
            body.append('convidado', '');
        } else {
            convidado.forEach(item => {
                body.append('convidado[]', item.innerText);
            });
        }

        const resposta = await fetch(LINK + '/agenda/salvar', {
            headers: {
                Authorization: await pegarAccessToken(),
            },
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        botaoSalvarEvento.classList.remove('aguarde');
        if (resposta.status != 201) {
            Alerta.notificacao(
                json.mensagem != undefined
                    ? json.mensagem
                    : 'Ocorreu um erro ao salvar evento, por favor, tente novamente.',
                false
            );
            return;
        }

        Alerta.notificacao('Evento salvo com sucesso!', true);
        adicionarEventoNaAgenda(json.evento);
        fecharAdicionarEvento(true);
    });

    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */
    botaoDetalheEditar.addEventListener('click', () => {
        abrirAdicionarEvento(dadoAgenda[eventoId], 'editar');
        fecharDadosDoEvento(false);
    });
    botaoEditarEvento.addEventListener('click', async () => {
        const evento = dadoAgenda[eventoId];
        if (evento.dono.eu && evento.video.link != '' && !inputVideo.checked) {
            const resposta = await Alerta.confirmar(
                'ATUALIZAR',
                'Você irá atualizar esse evento removendo a video chamada, tem certeza que deseja fazer isso?',
                '!'
            );
            if (resposta) {
                enviarEventoParaEditar();
            }
            return;
        }
        enviarEventoParaEditar();
    });
    const enviarEventoParaEditar = async () => {
        if (
            !validarSeEstaLogado() ||
            eventoId == undefined ||
            eventoId == '' ||
            botaoEditarEvento.classList.contains('aguarde')
        ) {
            return;
        }
        botaoEditarEvento.classList.add('aguarde');

        const body = new FormData();
        body.append('id', eventoId);
        body.append('titulo', inputTitulo.value);
        body.append('data_inicial', inputDataInicial.value);
        body.append('data_final', inputDataFinal.value);
        body.append('hora_inicial', inputHoraInicial.value);
        body.append('hora_final', inputHoraFinal.value);
        body.append('descricao', inputDescricao.value);
        body.append('local', inputLocal.value);
        body.append('video', inputVideo.checked ? 1 : 0);
        body.append('notificar', inputNotificar.checked ? 1 : 0);

        const convidado = blocoConvidadoLista.querySelectorAll('.item p');
        if (convidado.length == 0) {
            body.append('convidado', '');
        } else {
            convidado.forEach(item => {
                body.append('convidado[]', item.innerText);
            });
        }

        const resposta = await fetch(LINK + '/agenda/editar', {
            headers: {
                Authorization: await pegarAccessToken(),
            },
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        botaoEditarEvento.classList.remove('aguarde');
        if (resposta.status != 201) {
            Alerta.notificacao(
                json.mensagem != undefined
                    ? json.mensagem
                    : 'Ocorreu um erro ao editar evento, por favor, tente novamente.',
                false
            );
            return;
        }

        Alerta.notificacao('Evento editado com sucesso!', true);
        adicionarEventoNaAgenda(json.evento);
        fecharAdicionarEvento(true);
    };

    botaoDetalheParticiparSim.addEventListener('click', () => {
        confirmarParticipacaoNoEvento('sim');
    });
    botaoDetalheParticiparNao.addEventListener('click', () => {
        confirmarParticipacaoNoEvento('nao');
    });
    botaoDetalheParticiparTalvez.addEventListener('click', () => {
        confirmarParticipacaoNoEvento('talvez');
    });
    const confirmarParticipacaoNoEvento = async valor => {
        if (!validarSeEstaLogado()) {
            return;
        }
        Loading.show();

        const body = new FormData();
        body.append('id', eventoId);
        body.append('confirmar', valor);

        const resposta = await fetch(LINK + '/agenda/confirmar', {
            headers: {
                Authorization: await pegarAccessToken(),
            },
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();
        if (resposta.status != 201) {
            Alerta.notificacao(
                json.mensagem != undefined ? json.mensagem : 'Ocorreu um erro ao enviar resposta de participação.',
                false
            );
            return;
        }
        dadoAgenda[item.id] = json[0];

        botaoDetalheParticiparSim.classList.remove('hover');
        botaoDetalheParticiparNao.classList.remove('hover');
        botaoDetalheParticiparTalvez.classList.remove('hover');

        const bloco = blocoDetalheConvidadoLista.querySelector('.sou_eu .check');
        if (!bloco) {
            return;
        }

        adicionarEventoNaAgenda(json.evento);

        let icone;
        if (valor == 'sim') {
            icone = Icone.check(10);
            botaoDetalheParticiparSim.classList.add('hover');
        } else if (valor == 'nao') {
            icone = Icone.fechar(8);
            botaoDetalheParticiparNao.classList.add('hover');
        } else if (valor == 'talvez') {
            icone = Icone.menos(1.5);
            botaoDetalheParticiparTalvez.classList.add('hover');
        }
        bloco.classList.remove('sim');
        bloco.classList.remove('nao');
        bloco.classList.remove('talvez');
        bloco.classList.add(valor);
        bloco.innerHTML = icone;
    };
});
