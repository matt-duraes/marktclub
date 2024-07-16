window.addEventListener('load', () => {
    const botaoCopiar = $('#botao_copiar_assinatura');
    const botaoAbrir = $('#botao_abrir_assinatura');
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

    const pegarNomeFinal = (nome, cargoSigla, cargoNome) => {
        let nomeFinal = nome;
        if (!vazio(cargoSigla) && !vazio(cargoNome)) {
            nomeFinal += ` | <b style="color: #caaa00;">${cargoSigla}</b>${cargoNome}`;
        } else if (!vazio(cargoSigla)) {
            nomeFinal += ` | <span style="color: #999">${cargoSigla}</span>`;
        } else if (!vazio(cargoNome)) {
            nomeFinal += ` | <span style="color: #999">${cargoNome}</span>`;
        }
        return nomeFinal;
    };

    const pegarTelefoneFinal = (telefone, celular) => {
        let telefoneFinal = '+55 (61) ';
        if (!vazio(celular)) {
            telefoneFinal += `<b>${celular}</b>`;
        }
        if (!vazio(celular) && !vazio(telefone)) {
            telefoneFinal += telefone;
        } else if (!vazio(telefone)) {
            telefoneFinal += `<b>${telefone}</b>`;
        }
        return telefoneFinal;
    };

    const validarCampoObrigatorio = () => {
        if (
            vazio(inputNome.valor()) ||
            (vazio(inputCargoNome.valor()) && vazio(inputCargoSigla.valor())) ||
            (vazio(inputTelefone.valor()) && vazio(inputCelular.valor())) ||
            vazio(inputEmail.valor())
        ) {
            Alerta.notificacao('Preencha os campos obrigatórios para continuar.', false);
            return false;
        }
        return true;
    };

    const gerarLink = (nome, cargoSigla, cargoNome, celular, telefone, email) => {
        botaoAbrir.attr(
            'href',
            `${LINK}/assinatura/html?nome=${encodeURI(nome)}&cargoSigla=${encodeURI(cargoSigla)}&cargoNome=${encodeURI(
                cargoNome
            )}&telefone=${encodeURI(telefone)}&celular=${encodeURI(celular)}&email=${encodeURI(email)}`
        );
    };

    const copiarAssinatura = (nome, cargoSigla, cargoNome, celular, telefone, email) => {
        if (!validarCampoObrigatorio()) {
            return;
        }
        const nomeFinal = pegarNomeFinal(nome, cargoSigla, cargoNome);
        const telefoneFinal = pegarTelefoneFinal(telefone, celular);

        const texto = assinaturaHtml()
            .replace('{{NOME}}', nomeFinal)
            .replace('{{TELEFONE}}', telefoneFinal)
            .replace('{{EMAIL}}', !vazio(email) ? email + '@youhuul.com.br' : '');

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
        blocoEmail.texto(!vazio(email) ? email + '@youhuul.com.br' : '');

        if (copiar) {
            copiarAssinatura(nome, cargoSigla, cargoNome, celular, telefone, email);
        }
        gerarLink(nome, cargoSigla, cargoNome, celular, telefone, email);
    };

    inputLista.evento('keyup', () => {
        montarPrevia();
    });

    botaoCopiar.evento('click', () => {
        montarPrevia(true);
    });
    botaoAbrir.evento('click', e => {
        if (!validarCampoObrigatorio()) {
            e.preventDefault();
            return;
        }
    });
});
