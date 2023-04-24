// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"

window.addEventListener('load', () => {
    const botaoFavorito = document.getElementById('botao_favorito');
    const favorito = document.getElementById('favorito');
    const parceiro = document.getElementById('parceiro');
    const botaoEnvia = document.getElementById('botao_envia_preferencia');

    btnProximoStepper.forEach((btn, index) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            switch (index) {
                case 0:
                    if (favorito && parceiro) {
                        boxProximo(parceiro, favorito);
                    }

                    break;
                default:
                    break;
            }
        });
    });

    btnAnteriorStepper.forEach((btn, index) => {
        btn.addEventListener('click', function (event) {
            event.preventDefault();
            switch (index) {
                case 1:
                    if (favorito && parceiro) {
                        boxProximo(favorito, parceiro);
                    }

                    break;
                default:
                    break;
            }
        });
    });

    if (botaoEnvia) {
        botaoEnvia.addEventListener('click', function (e) {
            event.preventDefault();

            setTimeout(function () {
                Alerta.mensagem('Enviado com sucesso', 'A personalização foi enviada com sucesso!', true);
            }, 200);
        });
    }
});
