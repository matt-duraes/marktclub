// @template "painel"
// @import "assinatura"

window.addEventListener('load', () => {
    const botao = $('#botao_copiar_assinatura');
    const blocoNome = $('#bloco_assinatura .nome');
    const blocoSeparador = $('#bloco_assinatura .separador');
    const blocoCargoSigla = $('#bloco_assinatura .cargo strong');
    const blocoCargoNome = $('#bloco_assinatura .cargo span');
    const blocoDdi = $('#bloco_assinatura .ddi');
    const blocoCelular = $('#bloco_assinatura .celular');
    const blocoTelefone = $('#bloco_assinatura .telefone');
    const blocoEmail = $('#bloco_assinatura .email');

    const inputNome = $('#input_assinatura_nome');
    const inputCargoSigla = $('#input_assinatura_cargo_sigla');
    const inputCargoNome = $('#input_assinatura_cargo_nome');
    const inputCelular = $('#input_assinatura_celular');
    const inputTelefone = $('#input_assinatura_telefone');
    const inputEmail = $('#input_assinatura_email');

    const inputLista = $$(`
        #input_assinatura_nome, #input_assinatura_cargo_sigla, #input_assinatura_cargo_nome,
        #input_assinatura_celular, #input_assinatura_telefone, #input_assinatura_email
    `);

    const copiarAssinatura = (nome, cargoSigla, cargoNome, celular, telefone, email) => {
        if (
            vazio(nome) ||
            (vazio(cargoSigla) && vazio(cargoNome)) ||
            (vazio(celular) && vazio(telefone)) ||
            vazio(email)
        ) {
            Alerta.notificacao('Preencha os campos obrigatórios para continuar.', false);
            return;
        }
        let nomeFinal = nome;
        if (!vazio(nome) && (!vazio(cargoSigla) || !vazio(cargoNome))) {
            nomeFinal += ' | ';
        }
        if (!vazio(cargoSigla)) {
            nomeFinal += `<b style="color: #FF6F00;">${cargoSigla}</b>`;
        }
        if (!vazio(cargoSigla) && !vazio(cargoNome)) {
            cargoSigla = ' - ' + cargoSigla;
        }
        if (!vazio(cargoNome)) {
            nomeFinal += `<span style="color: #999">${cargoSigla}</span>`;
        }

        let telefoneFinal = '+55 (61) ';
        if (!empty(celular)) {
            telefoneFinal += `<b>${celular}</b>`;
        }
        if (!empty(celular) && !empty(telefone)) {
            telefoneFinal += ' | ' + telefone;
        } else if (!empty(telefone)) {
            telefoneFinal += `<b>${telefone}</b>`;
        }

        Alerta.notificacao('Assinatura copiada com sucesso!', true);
    };

    const montarPrevia = copiar => {
        const nome = inputNome.valor().toUpperCase();
        const cargoSigla = inputCargoSigla.valor().toUpperCase();
        let cargoNome = inputCargoNome.valor();
        const email = inputEmail.valor().toLowerCase();
        const celular = inputCelular.valor();
        let telefone = inputTelefone.valor();

        if (!vazio(nome) && (!vazio(cargoSigla) || !vazio(cargoNome))) {
            blocoSeparador.aparecer();
        } else {
            blocoSeparador.sumir();
        }
        if (!vazio(celular) || !vazio(telefone)) {
            blocoDdi.aparecer();
        } else {
            blocoDdi.sumir();
        }
        if (!vazio(cargoNome) && !vazio(cargoSigla)) {
            cargoNome = ' - ' + cargoNome;
        }
        if (!vazio(telefone) && !vazio(celular)) {
            telefone = ' | ' + telefone;
        }

        blocoNome.texto(nome);
        blocoCargoSigla.texto(cargoSigla);
        blocoCargoNome.texto(cargoNome);
        blocoCelular.texto(celular);
        blocoTelefone.texto(telefone);
        blocoEmail.texto(email);

        if (copiar) {
            copiarAssinatura(nome, cargoSigla, cargoNome, celular, telefone, email);
        }
    };
    inputLista.evento('keyup', () => {
        montarPrevia();
    });

    botao.evento('click', () => {
        montarPrevia(true);
    });
});
