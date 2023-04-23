// @system "Form"
// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"

window.onload = function () {
    const LINK = document.getElementById('LINK').value;
    const cupomLista = document.querySelectorAll('.bloco_automovel');
    
    const target = document.querySelectorAll('[data-animacao]');
    const animationClass = 'animate';
    
    function animeScroll(){
        const windowTop = window.pageYOffset + 600;
        target.forEach(function(element){
            if((windowTop) > element.offsetTop){
                element.classList.add(animationClass);
            }
        });
    }

    animeScroll();
    window.addEventListener('scroll', function(){
        animeScroll();    
    });
};

