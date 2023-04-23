const slidePage = document.querySelector('.slidePage');
const btnAnteriorStepper = document.querySelectorAll('.voltar');
const btnProximoStepper = document.querySelectorAll('.proxima');
const progressTexts = document.querySelectorAll('.step p');
const progressChecks = document.querySelectorAll('.step .check');
const bullets = document.querySelectorAll('.step .bullet');

function boxProximo(atual, proximo) {
    atual.style.opacity = 0;
    atual.style.marginLeft = '-300px';
    setTimeout(function () {
        atual.style.display = 'none';
    }, 300);

    proximo.style.display = 'flex';
    proximo.style.opacity = 0;
    proximo.style.marginLeft = '300px';
    proximo.style.marginTop = -(atual.offsetHeight + 40) + 'px';
    setTimeout(function () {
        proximo.style.opacity = 1;
        proximo.style.marginLeft = '0';
        proximo.style.marginTop = '0';
    }, 300);

    document.querySelector('body, html').scrollTop = document.querySelector('.containerStepper').offsetTop - 100;
}
function boxAnterior(atual, proximo) {
    atual.style.marginTop = -(proximo.offsetHeight + 40) + 'px';
    atual.style.opacity = 0;
    atual.style.marginLeft = '300px';
    setTimeout(function () {
        atual.style.display = 'none';
    }, 300);

    proximo.style.display = 'flex';
    proximo.style.opacity = 0;
    proximo.style.marginLeft = '-300px';
    setTimeout(function () {
        proximo.style.opacity = 1;
        proximo.style.marginLeft = '0';
    }, 300);

    document.querySelector('body, html').scrollTop = document.querySelector('.containerStepper').offsetTop - 100;
}
