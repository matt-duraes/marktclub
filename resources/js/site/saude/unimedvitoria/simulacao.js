// @resource "site/stepper"

window.addEventListener('load', () => {
    const planoUnimed = document.getElementById('bloco_plano_unimed');
    const simulacaoUnimed = document.getElementById('simulacao_unimed');
    const contratacaoUnimed = document.getElementById('bloco_contratacao_unimed');

    btnProximoStepper.forEach((btn, index) => {

        btn.addEventListener("click", function(e){
            e.preventDefault();
            switch (index) {
                case 0:

                    if(planoUnimed && simulacaoUnimed){
                        bullets[0].classList.add("ativo");
                        progressChecks[0].classList.add("ativo");
                        progressTexts[0].classList.add("ativo");

                        boxProximo(planoUnimed, simulacaoUnimed);

                    }
                break;
                case 1:

                    if(simulacaoUnimed && contratacaoUnimed ) {
                        bullets[1].classList.add("ativo");
                        progressChecks[1].classList.add("ativo");
                        progressTexts[1].classList.add("ativo");

                        boxProximo(simulacaoUnimed, contratacaoUnimed);
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

                    if(simulacaoUnimed && planoUnimed ) {
                        bullets[0].classList.remove("ativo");
                        progressChecks[0].classList.remove("ativo");
                        progressTexts[0].classList.remove("ativo");

                        boxAnterior(simulacaoUnimed, planoUnimed);
                    }

                break;
                case 1:


                    if(simulacaoUnimed && planoUnimed ) {
                        bullets[1].classList.remove("ativo");
                        progressChecks[1].classList.remove("ativo");
                        progressTexts[1].classList.remove("ativo");

                        boxAnterior(contratacaoUnimed, simulacaoUnimed);
                    }


                break;
                default:
                break;
            }
        });
    });

    const btnSubmitVitoria = document.querySelector("#enviar_contratacao_vitoria");
    if(btnSubmitVitoria){
        btnSubmit.addEventListener("click", function(e){
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
