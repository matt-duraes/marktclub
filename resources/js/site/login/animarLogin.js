/*
|--------------------------------------------------------------------------
| TROCA OS BLOCOS COM ANIMAÇÃO
|--------------------------------------------------------------------------
*/

let animarBloco = function (de, para, focus, direcao) {
    const animationDuration = 300;
    const framesPerSecond = 60;
    const frameStep = 1000 / framesPerSecond;
    const numSteps = Math.ceil(animationDuration / frameStep);
    const stepSize = 100 / numSteps;
    let currentStep = 0;

    function animateFrame() {
        currentStep++;
        if (currentStep <= numSteps) {
            const percentage = currentStep * stepSize;
            const distance = direcao === 'esquerda' ? -percentage : percentage;
            de.style.left = distance + '%';
            para.style.left = 100 + distance + '%';
            requestAnimationFrame(animateFrame);
        } else {
            de.style.display = 'none';
            para.style.left = '0';
            para.style.opacity = 1;
            para.style.display = 'flex';
            if (focus !== undefined && focus !== '') {
                focus.focus();
            }
        }
    }

    requestAnimationFrame(animateFrame);
};

/*
|--------------------------------------------------------------------------
| LOADING GERAL DO LOGIN
|--------------------------------------------------------------------------
*/

let loading = {
    show: function (form, botao) {
        const loadingSpan = form.querySelector('.auth_loading span');
        loadingSpan.style.width = 0;
        loadingSpan.style.opacity = 1;
        loadingSpan.animate({ width: '80%' }, { duration: 20000 });
    },
    hide: function (form, botao) {
        const loadingSpan = form.querySelector('.auth_loading span');
        loadingSpan.animate({ width: '100%' }, { duration: 300 }, function () {
            loadingSpan.animate({ opacity: 0 }, { duration: 300 }, function () {
                loadingSpan.style.width = 0;
                loadingSpan.style.opacity = 1;
            });
        });
    },
};
