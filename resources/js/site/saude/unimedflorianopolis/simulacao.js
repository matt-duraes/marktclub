window.addEventListener('load', () => {

    const tipoInputs = document.getElementsByName("tipo");
    tipoInputs.forEach(function (tipoInput) {
        tipoInput.addEventListener("change", function () {
            let valor = this.value;
            let blocoCentralPlano = document.getElementById("bloco_central_plano");
            let enfermaria30 = blocoCentralPlano.querySelector("#bloco_simulacao .button.enfermaria_30");
            let enfermaria50 = blocoCentralPlano.querySelector("#bloco_simulacao .button.enfermaria_50");
            let apartamento = blocoCentralPlano.querySelector("#bloco_simulacao .button.apartamento");
            let enfermaria = blocoCentralPlano.querySelector("#bloco_simulacao .button.enfermaria");

            enfermaria30.style.display = "none";
            enfermaria50.style.display = "none";
            if (valor === "estadual" || valor === "nacional") {
                enfermaria.style.display = "block";
                apartamento.style.display = "block";
            } else if (valor === "regional") {
                enfermaria.style.display = "none";
                apartamento.style.display = "none";
                enfermaria30.style.display = "block";
                enfermaria50.style.display = "block";
            }
        });
    });

    const planoFloripa = document.getElementById('bloco_plano_floripa');
    const centralPlano = document.getElementById('bloco_central_plano');
    const simulacaoFloripa = document.getElementById('simulacao_florianopolis');
    const contratacaoFloripa = document.getElementById('bloco_contratacao_floripa');

    btnProximoStepper.forEach((btn, index) => {

        btn.addEventListener("click", function(e){
            e.preventDefault();
            switch (index) {
                case 0:

                    if(planoFloripa && centralPlano){
                        boxProximo(planoFloripa, centralPlano);
                    }

                break;
                case 1:

                    if(simulacaoFloripa && centralPlano){
                        bullets[0].classList.add("ativo");
                        progressChecks[0].classList.add("ativo");
                        progressTexts[0].classList.add("ativo");
                        boxProximo(centralPlano, simulacaoFloripa);
                    }

                break;
                case 2:

                    if(planoFloripa && contratacaoFloripa){
                        bullets[1].classList.add("ativo");
                        progressChecks[1].classList.add("ativo");
                        progressTexts[1].classList.add("ativo");
                        boxProximo(simulacaoFloripa, contratacaoFloripa);
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

                    if(centralPlano && planoFloripa){

                        bullets[0].classList.remove("ativo");
                        progressChecks[0].classList.remove("ativo");
                        progressTexts[0].classList.remove("ativo");
                        boxProximo(centralPlano, planoFloripa);

                    }

                break;
                case 1:

                    if(simulacaoFloripa && centralPlano){

                        bullets[0].classList.remove("ativo");
                        progressChecks[0].classList.remove("ativo");
                        progressTexts[0].classList.remove("ativo");
                        boxProximo(simulacaoFloripa, centralPlano);

                    }

                break;
                case 2:

                    if(contratacaoFloripa && simulacaoFloripa){

                        bullets[1].classList.remove("ativo");
                        progressChecks[1].classList.remove("ativo");
                        progressTexts[1].classList.remove("ativo");
                        boxProximo(contratacaoFloripa, simulacaoFloripa);

                    }

                break;
                default:
                break;
            }
        });
    });

    const btnSubmitFloripa = document.querySelector("#enviar_contratacao_floripa");
    if(btnSubmitFloripa){
        btnSubmitFloripa.addEventListener("click", function(e){
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
