window.addEventListener('load', () => {

    const tipoInputs = document.getElementsByName("tipo");
    const acomodacaoInput = document.getElementsByName("acomodacao")[0];
    const rjInput = document.querySelector('#bloco_tipo_amil .button.amil_s60qc_rj');
    const jundiaiInput = document.querySelector('#bloco_tipo_amil .button.amil_s60qc_jundiai');
    const spInput = document.querySelector('#bloco_tipo_amil .button.amil_s60qc_sp');

    tipoInputs.forEach(function (tipoInput) {
        tipoInput.addEventListener("change", function () {
            let valor = this.value;
            let coletivo = ["amil_s60qc_rj", "amil_s80qc", "amil_s380qc", "amil_s450qc", "amil_s60qc_jundiai", "amil_s60qc_sp"];
            let individual = ["amil_s80qp", "amil_s380qp", "amil_s450qp", "amil_s750r1", "amil_s750r2"];

            if (valor && coletivo.includes(valor) && acomodacaoInput) {
                acomodacaoInput.value = "coletivo";
            } 
            else if (valor && individual.includes(valor) && acomodacaoInput) {
                acomodacaoInput.value = "individual";
            }
        });
    });


    const regiaoInputs = document.getElementsByName("regiao");
    regiaoInputs.forEach(function (regiaoInput) {
        regiaoInput.addEventListener("change", function () {
            let valor = this.value;

            if (valor == 'brasilia') {
                rjInput.style.display = 'none';
                jundiaiInput.style.display = 'none';
                spInput.style.display = 'none';
            } else if(valor == 'rio-de-janeiro' && rj && jundiai && sp){
                rj.style.display = 'block';
                jundiai.style.display = 'none';
                sp.style.display = 'none';
            } else if(valor == 'sao-paulo' && rj && jundiai && sp){
                rj.style.display = 'none';
                jundiai.style.display = 'block';
                sp.style.display = 'block';
            }
    
        });
    });

    const planoAmil = document.getElementById('bloco_plano_amil');
    const tipoPlano = document.getElementById('bloco_tipo_amil');
    const simulacaoAmil = document.getElementById('simulacao_amil');
    const contratacaoAmil = document.getElementById('bloco_contratacao_amil');

    btnProximoStepper.forEach((btn, index) => {
        btn.addEventListener("click", function(e){
            e.preventDefault();
            switch (index) {
                case 0:

                    if(tipoPlano && planoAmil){
                        boxProximo(planoAmil, tipoPlano);
                    }

                break;
                case 1:

                    if(tipoPlano && simulacaoAmil){
                        bullets[0].classList.add("ativo");
                        progressChecks[0].classList.add("ativo");
                        progressTexts[0].classList.add("ativo");
                        boxProximo(tipoPlano, simulacaoAmil);
                    }

                break;
                case 2:

                    if(simulacaoAmil && contratacaoAmil){
                        bullets[1].classList.add("ativo");
                        progressChecks[1].classList.add("ativo");
                        progressTexts[1].classList.add("ativo");
                        boxProximo(simulacaoAmil, contratacaoAmil);
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

                    if(planoAmil && tipoPlano){

                        boxProximo(tipoPlano, planoAmil);

                    }

                break;
                case 1:

                    if(simulacaoAmil && tipoPlano){

                        bullets[0].classList.remove("ativo");
                        progressChecks[0].classList.remove("ativo");
                        progressTexts[0].classList.remove("ativo");
                        boxProximo(simulacaoAmil, tipoPlano);

                    }

                break;
                case 2:

                    if(contratacaoAmil && simulacaoAmil){

                        bullets[1].classList.remove("ativo");
                        progressChecks[1].classList.remove("ativo");
                        progressTexts[1].classList.remove("ativo");
                        boxProximo(contratacaoAmil, simulacaoAmil);

                    }

                break;
                default:
                break;
            }
        });
    });

    const btnSubmitAmil = document.querySelector("#enviar_contratacao_amil");
    if(btnSubmitAmil) {
        btnSubmitAmil.addEventListener("click", function(e){
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
