// @template "painel"
// @system "Popup"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const parceiro = $('#input_visualizar_id').valor();
    const botaoAbrirAuditoria = $('#botao_fazer_auditoria');
    const botaoSalvarAuditoria = $('.botao_salvar_auditoria');
    const botaoFechar = $('#bloco_auditoria_geral .fechar');
    const botaoCancelarLoja = $('#botao_cancelar_loja');
    const botaoSemInteresse = $('#botao_sem_interesse');
    const botaoSalvarCancelarLoja = $('#botao_salvar_cancelar_loja');

    const PaginaAuditoria = new Popup('Auditoria ' + parceiro, 'bloco_auditoria_geral', false, true);
    botaoAbrirAuditoria.evento('click', () => {
        zerarAuditoria();
        PaginaAuditoria.abrir();
    });

    const inputResultado = $('#input_auditoria_resultado');
    const inputPadrao = $('#input_auditoria_padrao');
    const inputMensagem = $('#input_auditoria_mensagem');
    formSelectOption(inputPadrao, historicoSelect);

    botaoFechar.evento('click', () => {
        PaginaAuditoria.fechar();
    });
    inputPadrao.evento('formChange', () => {
        const valor = inputPadrao.valor();
        const mensagem = historicoMensagem[valor] || '';
        if (vazio(mensagem)) {
            return;
        }
        inputMensagem.focus();
        inputMensagem.valor(mensagem.trim());
    });

    botaoSalvarAuditoria.evento('click', async () => {
        const auditoria = inputResultado.valor();
        const mensagem = inputMensagem.valor();
        if (vazio(auditoria)) {
            Alerta.notificacao('Escolha um resultado para a auditoria.', false);
            return;
        } else if (vazio(mensagem)) {
            Alerta.notificacao('Digite uma mensagem para a auditoria.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/parceiro-loja',
            {
                parceiro,
                auditoria,
                mensagem,
                indice: 'auditoria',
            },
            'Ocorreu um erro ao salvar a auditoria, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        zerarAuditoria();
        PaginaAuditoria.fechar();
        if (auditoria != 'sem_problema') {
            removerStatusProblema();
        }
        adicionarHistorico(resposta.dado.id, resposta.dado.mensagem);
    });
    const adicionarHistorico = (id, mensagem) => {
        const nome = $('#USUARIO_NOME').valor();
        const imagem = $('#USUARIO_IMAGEM').valor();
        adicionarNovaMensagem(id, mensagem, imagem, nome, false);
    };
    const removerStatusProblema = () => {
        const statusProblema = $('.botao_status[data-status="problema"]');
        if (statusProblema) {
            statusProblema.remover();
        }
    };
    const zerarAuditoria = () => {
        inputResultado.valor('');
        inputPadrao.valor('');
        inputMensagem.valor('');
    };

    /*
    |--------------------------------------------------------------------------
    | POPUP CANCELAR
    |--------------------------------------------------------------------------
    */
    const PopupCancelarLoja = new Popup('Cancelar ' + parceiro, 'bloco_cancelar_loja', true, false);
    let status;

    if (botaoCancelarLoja) {
        botaoCancelarLoja.evento('click', () => {
            PopupCancelarLoja.abrir();
            status = 'cancelado';
        });
    }

    if (botaoSemInteresse) {
        botaoSemInteresse.evento('click', async () => {
            PopupCancelarLoja.abrir();
            status = 'sem-interesse';
        });
    }

    botaoSalvarCancelarLoja.evento('click', async () => {
        const motivo = $('#input_cancelar_motivo').valor();
        if (vazio(motivo)) {
            Alerta.notificacao('Selecione um motivo para cancelar a loja.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + `/app/ajax/parceiro-loja`, {
            indice: 'cancelar-loja',
            id: parceiro,
            // eslint-disable-next-line
            status: status,
            cancelar_motivo: motivo,
        });
        Loading.hide();
        if (false === resposta) {
            return;
        }

        PopupCancelarLoja.fechar();
        const confirmacao = await Alerta.mensagem('Status alterado', 'O status foi alterado com sucesso!', true);
        if (confirmacao) {
            window.location.reload();
        }
    });
});

const historicoSelect = {
    '': 'Escolha uma opção',
    emailRecebido: `E-mail recebido`,
    propostaCobrancaRetorno: `Proposta - Cobrança de retorno`,
    propostaEnviada: `Proposta enviada`,
    convenioDiretoMarcacao: `Convênio direto - Marcação`,
    prospeccaoTelefoneResposta: `Prospecção telefone - Resposta`,
    prospeccaoWhatsapp: `Prospecção WhatsApp`,
    prospeccaoTelefoneSemSucesso: `Prospecção Telefone - Sem sucesso`,
    encerramentoConvenioDireto: `Encerramento - Convênio direto`,
    encerramentoSemInteresse: `Encerramento - Sem interesse`,
    encerramentoEmpresaFechada: `Encerramento - Empresa fechada`,
    encerramentoSemContato: `Encerramento - Sem contato`,
    encerramentoPropostaNaoRespondida: `Encerramento - Proposta não respondida`,
    auditoriaConcluida: `Auditoria concluida`,
    auditoriaEmailWhatsappEnviado: `Auditoria - E-mail WhatsApp envaido`,
    auditoriaProblemaSemReconhecimento: `Auditoria - Probema - Sem reconhecimento`,
    auditoriaProblemaSemRetorno: `Auditoria - Problema sem retorno do parceiro`,
    auditoriaDescontoAutomaticoInativo: `Auditoria - Desconto automatico inativo`,
    auditoriaDescontoAutomaticoAtivo: `Auditoria - Desconto automatico ativo`,
    auditoriaCupomInativoExpirado: `Auditoria - Cupom inativo ou expirado`,
    auditoriaCupomAtivo: `Auditoria - Cupom ativo`,
};
const historicoMensagem = {
    prospeccaoWhatsapp: `A mensagem de WhatsApp foi encaminhada para o prospectado (ou cliente), por meio do número de telefone (XX) - xxxx-xxxx, para o intermediário XXXX.\n1. Aguardando retorno.\n2. Porém, foi visualizada, mas não respondida.\n3. Solicitando retorno da proposta.`,
    emailRecebido: `O e-mail do parceiro, foi recebido através do endereço de correio eletrônico XXXX. pelo intermediário XXXX.\n1. O contrato encontra-se em análise no setor jurídico.\n2. A proposta foi encaminhada para o setor responsável.\n3. . O contrato encontra-se preenchido e disponivel na plataforma de assinatura.`,
    propostaCobrancaRetorno: `O e-mail com a solicitação de retorno sobre a proposta, foi encaminhado para o prospectado (ou parceiro), através do endereço de correio eletrônico XXXX, para o intermediário XXXX, aguardando retorno.`,
    propostaEnviada: `O e-mail com proposta foi encaminhado para o prospectado (ou parceiro), por meio do endereço de correio eletrônico XXX, para o intermediário XXXX, aguardando retorno.`,
    prospeccaoTelefoneResposta: `O contato foi realizado com o prospectado (ou parceiro), através do número de telefone (XX) – xxxx-xxxx, foi obtida comunicação com o Sr(a). XXXX.\n1. Não demonstrou interesse com a parceria.\n2. solicitou o envio da proposta por e-mail.\n3. Foi recebida a seguinte resposta “xxxxxxxxxxxxxxxxxx”, aguardando retorno.`,
    auditoriaConcluida: `Foi realizado contato com o número (XX) – xxxx-xxxx, pelo intermediário XXXX.\n1. Os descontos e procedimentos estão confirmados.\n2. Após negociação houve alteração no desconto para XX.\n3. Após negociação houve alteração no procedimento para XX.\n4. Após conversa houve alteração nos contatos e/ou endereços.`,
    convenioDiretoMarcacao: `Marcação de Convênio Direto Realizada.`,
    encerramentoConvenioDireto: `Parceria cancelada, pois se trata de convenio direto de uma entidade que não faz mais parte do nosso rol de clientes. ID da Entidade XXX.`,
    auditoriaEmailWhatsappEnviado: `E-MAIL: O e-mail com as informações do parceiro, para futura confirmação, foi encaminhado através do endereço de correio eletrônico XXXX, aguardando retorno.\nWHATSAPP: A mensagem WhatsApp com as informações do parceiro, para futura confirmação, foi encaminhado através do número de telefone XXXX, aguardando retorno.`,
    auditoriaProblemaSemReconhecimento: `O status de funcionamento do parceiro, encontra-se com problema. Foi realizado contato por e-mail através do endereço de correio eletrônico XXXX, de telefone por meio do número (XX) - xxxx-xxxx, mediante número de WhatsApp (XX) - xxxx-xxxx e redes sociais XXX, para o intermediário XXXX, porém não houve reconhecimento da parceria.`,
    prospeccaoTelefoneSemSucesso: `O Contato com o prospectado (ou parceiro), foi realizado através do número de telefone (XX) – xxxx-xxxx, pelo intermediário XXXX, entretanto xxxxxx.\nMOTIVOS - SEM SUCESSO:\n1- não foi obtida resposta para o nosso atendimento.\n2- ligação encontra-se indisponível.\n3- a chamada foi encaminhada para caixa postal.`,
    encerramentoSemInteresse: `Ao entrar em contato com o prospectado XXX, através do endereço de correio eletrônico XXXX, de telefone por meio do número (XX) - xxxx-xxxx, mediante número de WhatsApp (XX) - xxxx-xxxx e redes sociais XXX, foi constatado que a empresa não possui interesse pois xxxxxxxx, por essa razão a parceria se encontra encerrada\nMOTIVOS - STATUS SEM INTERESSE:\n1- o estabelecimento não fecha parceria com clubes de benefícios.\n2- o estabelecimento não fecha parceria.\n3- o estabelecimento só firma parceria com contrapartida financeira.\n4- o estabelecimento somente firma parceria com transferência de dados.`,
    encerramentoEmpresaFechada: `Ao tentar estabelecer contato com a empresa prospectada através do endereço de correio eletrônico XXXX, de telefone por meio do número (XX) - xxxx-xxxx, mediante número de WhatsApp (XX) - xxxx-xxxx e redes sociais XXX, foi constatado que o estabelecimento se encontra fechado temporária ou permanentemente, por consequência a parceria está encerrada.`,
    encerramentoEmpresaFechada: `Não foi possível estabelecer contato com o parceiro prospectado através do endereço de correio eletrônico XXXX, de telefone por meio do número (XX) - xxxx-xxxx, mediante número de WhatsApp (XX) - xxxx-xxxx e redes sociais XXX, por esse motivo a parceria está encerrada.`,
    encerramentoPropostaNaoRespondida: `Ao entrar em contato com o prospectado, através do endereço de correio eletrônico XXXX, de telefone por meio do número (XX) - xxxx-xxxx, mediante número de WhatsApp (XX) - xxxx-xxxx e redes sociais XXX, não foi obtida nenhuma resposta, por esse motivo a parceria está encerrada.`,
    auditoriaProblemaSemRetorno: `O status de funcionamento do parceiro, encontra-se com problema. Foi realizada tentativa de contato por e-mail através do endereço de correio eletrônico XXXX, de telefone por meio do número (XX) - xxxx-xxxx, mediante número de WhatsApp (XX) - xxxx-xxxx e redes sociais XXX, porém não houve retorno ou atendimento.`,
    auditoriaDescontoAutomaticoInativo: `O status de funcionamento do parceiro, encontra-se com problemas. O Desconto não está sendo aplicado.`,
    auditoriaDescontoAutomaticoAtivo: `O status de funcionamento do parceiro, encontra-se ativo. O desconto é aplicado automaticamente.`,
    auditoriaCupomInativoExpirado: `O status de funcionamento do parceiro, encontra-se com problemas. O Cupom está inativo ou expirado.`,
    auditoriaCupomAtivo: `O status de funcionamento do parceiro, encontra-se ativo. O cupom está sendo aplicado corretamente.`,
};

window.addEventListener('load', () => {
    const vinculo = document.querySelector('#input_visualizar_id').value;
    const linhaTempo = $$('.bloco_linha_tempo_geral');
    linhaTempo.forEach(bloco => {
        carregarBlocoLinhaTempo(bloco, vinculo);
    });
});
const carregarBlocoLinhaTempo = (bloco, vinculo) => {
    const local = $('input[name="local_principal"]', bloco).valor();

    const botaoAbrir = $('.botao_linha_tempo', bloco);
    const botaoFechar = $('.bloco_visualizar_popup_geral .fechar', bloco);
    const blocoPopup = $('.bloco_visualizar_popup_geral', bloco);

    const blocoPadrao = $('.bloco_linha_tempo_padrao', bloco);
    blocoPadrao.classe('bloco_linha_tempo_padrao', false);
    const blocoLista = $('.bloco_lista_item', bloco);
    const blocoZero = $('.bloco_zero', bloco);
    const botaoMais = $('.botao_mais', bloco);

    /*
    |--------------------------------------------------------------------------
    | ABRIR/FECHAR POPUP
    |--------------------------------------------------------------------------
    */
    let popupBuscar = true;
    botaoAbrir.addEventListener('click', async () => {
        if (popupBuscar && !(await buscarLista(1))) {
            return;
        }
        popupBuscar = false;
        abrirBlocoPopup();
    });
    const abrirBlocoPopup = () => {
        blocoPopup.aparecer();
        setTimeout(() => {
            blocoPopup.classe('ativo', true);
        }, 40);
    };

    botaoFechar.addEventListener('click', () => {
        fecharBlocoPopup();
    });
    blocoPopup.evento('target', () => {
        fecharBlocoPopup();
    });
    const fecharBlocoPopup = () => {
        blocoPopup.classe('ativo', false);
        setTimeout(() => {
            blocoPopup.sumir();
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | BUSCAR LISTA
    |--------------------------------------------------------------------------
    */
    let paginaAtual;
    const buscarLista = async pagina => {
        return new Promise(async resolve => {
            paginaAtual = pagina;
            Loading.show();
            const resposta = await ajaxPost(LINK + '/sistema-data/buscar-lista', {
                pagina,
                vinculo,
                /* eslint-disable */
                local_principal: local,
                /* eslint-enable */
            });
            Loading.hide();
            if (false === resposta) {
                resolve(false);
                return;
            }
            resolve(true);
            if (resposta.dado.lista.length == 0 && pagina == 1) {
                blocoZero.aparecer();
            }
            const paginaTotal = resposta.dado.pagina.total;
            if (paginaTotal > 1 && pagina < paginaTotal) {
                botaoMais.aparecer();
            } else {
                botaoMais.sumir();
            }
            adicionarItem(resposta.dado.lista);
        });
    };
    botaoMais.evento('click', () => {
        buscarLista(paginaAtual + 1);
    });

    const adicionarItem = lista => {
        for (const item of lista) {
            const clone = blocoPadrao.clonar();
            $('figure', clone).css('background-image', 'url(' + item.equipe.imagem + ')');
            $('h3', clone).texto(item.equipe.nome);
            $('p', clone).texto(item.mensagem);
            $('time', clone).texto(item.data);
            blocoLista.final(clone);
        }
    };
};
