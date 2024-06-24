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
        if (!vazio(cargoSigla) && !vazio(cargoNome)) {
            nomeFinal += ` | <b style="color: #FF6F00;">${cargoSigla}</b> - ${cargoNome}`;
        } else if (!vazio(cargoSigla)) {
            nomeFinal += ` | <span style="color: #999">${cargoSigla}</span>`;
        } else if (!vazio(cargoNome)) {
            nomeFinal += ` | <span style="color: #999">${cargoNome}</span>`;
        }

        let telefoneFinal = '+55 (61) ';
        if (!vazio(celular)) {
            telefoneFinal += `<b>${celular}</b>`;
        }
        if (!vazio(celular) && !vazio(telefone)) {
            telefoneFinal += ' | ' + telefone;
        } else if (!vazio(telefone)) {
            telefoneFinal += `<b>${telefone}</b>`;
        }

        const texto = assinaturaHtml()
            .replace('{{NOME}}', nomeFinal)
            .replace('{{TELEFONE}}', telefoneFinal)
            .replace('{{EMAIL}}', email + '@youhuul.com.br');

        navigator.clipboard.writeText(texto);
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
        blocoEmail.texto(email + '@youhuul.com.br');

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
