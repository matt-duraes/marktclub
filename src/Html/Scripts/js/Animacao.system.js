class Animacao {

    constructor() {

        throw new Error('A class Animacao não pode ser instanciada.');

    }

    static negar(bloco) {

        bloco.classList.add('shake');
        bloco.classList.add('animated');

        setTimeout(() => {
            bloco.classList.remove('shake');
            bloco.classList.remove('animated');
        }, 500);

    }

    static aparecer(bloco) {

        bloco.classList.add('bounceIn');

        setTimeout(() => {
            bloco.classList.remove('bounceIn');
        }, 500);

    }

}