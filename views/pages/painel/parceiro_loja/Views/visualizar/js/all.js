// @template "painel"
// @system "Popup"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const parceiro = $('#input_visualizar_id').valor();
    const botaoAbrirAuditoria = $('#botao_fazer_auditoria');
    const botaoSalvarAuditoria = $('.botao_salvar_auditoria');
    const botaoFechar = $('#bloco_auditoria_geral .fechar');

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
        const resultado = inputResultado.valor();
        const mensagem = inputMensagem.valor();
        if (vazio(resultado)) {
            Alerta.notificacao('Escolha um resultado para a auditoria.', false);
        } else if (vazio(mensagem)) {
            Alerta.notificacao('Digite uma mensagem para a auditoria.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/parceiro-loja',
            {
                resultado,
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
    });
    const zerarAuditoria = () => {
        inputResultado.valor('');
        inputPadrao.valor('');
        inputMensagem.valor('');
    };
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
