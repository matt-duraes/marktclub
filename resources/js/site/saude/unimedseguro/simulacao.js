window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

    const planoSeguro = document.getElementById('bloco_plano_seguro');
    const simulacaoSeguro = document.getElementById('simulacao_seguro');
    const contratacaoSeguro = document.getElementById('bloco_contratacao_seguro');

    btnProximoStepper.forEach((btn, index) => {
        btn.addEventListener("click", function(e){
            e.preventDefault();
            switch (index) {
                case 0:

                    if(planoSeguro && simulacaoSeguro) {
                        bullets[0].classList.add("ativo");
                        progressChecks[0].classList.add("ativo");
                        progressTexts[0].classList.add("ativo");
                        boxProximo(planoSeguro, simulacaoSeguro);
                    }

                break;
                case 1:

                    if(simulacaoSeguro && contratacaoSeguro) {
                        bullets[1].classList.add("ativo");
                        progressChecks[1].classList.add("ativo");
                        progressTexts[1].classList.add("ativo");
                        boxProximo(simulacaoSeguro, contratacaoSeguro);
                    }

                break;
                default:
                break;
            }

        });
    });

    btnAnteriorStepper.forEach((btn, index) => {
        btn.addEventListener("click", function(event){
            event.preventDefault();
            switch (index) {
                case 0:

                    if(simulacaoSeguro && planoSeguro) {
                        bullets[0].classList.remove("ativo");
                        progressChecks[0].classList.remove("ativo");
                        progressTexts[0].classList.remove("ativo");
                        boxProximo(simulacaoSeguro, planoSeguro);
                    }

                break;
                case 1:

                    if(contratacaoSeguro && simulacaoSeguro) {
                        bullets[1].classList.remove("ativo");
                        progressChecks[1].classList.remove("ativo");
                        progressTexts[1].classList.remove("ativo");
                        boxProximo(contratacaoSeguro, simulacaoSeguro);
                    }

                break;
                default:
                break;
            }
        });
    });

    const btnSubmitSeguro = document.querySelector("#enviarSimulacaoSeguro");
    if(btnSubmitSeguro) {
        btnSubmitSeguro.addEventListener("click", function(e){
            event.preventDefault();

            bullets[2].classList.add("ativo");
            progressChecks[2].classList.add("ativo");
            progressTexts[2].classList.add("ativo");
            let simulacao = btnSubmitSeguro.getAttribute('data-simulacao');

            setTimeout(function(){
                window.location.assign(LINK + '/saude/contratacao/' + simulacao);
            }, 200);
        });
    }

});
