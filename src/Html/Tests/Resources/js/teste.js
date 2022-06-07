window.addEventListener('load', () => {
    const botaoFalhou = document.querySelector('#botao_falhou');
    const botaoPassou = document.querySelector('#botao_passou');
    const botaoTodos = document.querySelector('#botao_todos');

    const blocoFalhou = document.querySelector('#bloco_falhou');
    const blocoPassou = document.querySelector('#bloco_passou');
    const blocoTodos = document.querySelector('#bloco_todos');

    botaoFalhou.addEventListener('click', () => {
        blocoFalhou.style.display = 'flex';
        blocoPassou.style.display = 'none';
        blocoTodos.style.display = 'none';

        botaoFalhou.classList.add('hover');
        botaoPassou.classList.remove('hover');
        botaoTodos.classList.remove('hover');
    });
    botaoPassou.addEventListener('click', () => {
        blocoFalhou.style.display = 'none';
        blocoPassou.style.display = 'flex';
        blocoTodos.style.display = 'none';

        botaoFalhou.classList.remove('hover');
        botaoPassou.classList.add('hover');
        botaoTodos.classList.remove('hover');
    });
    botaoTodos.addEventListener('click', () => {
        blocoFalhou.style.display = 'none';
        blocoPassou.style.display = 'none';
        blocoTodos.style.display = 'flex';

        botaoFalhou.classList.remove('hover');
        botaoPassou.classList.remove('hover');
        botaoTodos.classList.add('hover');
    });

    const botaoMostrarRequest = document.querySelectorAll('.botao_mostrar_request');
    if (botaoMostrarRequest.length > 0) {
        botaoMostrarRequest.forEach(botao => {
            botao.addEventListener('click', () => {
                botao.classList.toggle('esconder');
                botao.closest('.linha_resposta').querySelector('ul').classList.toggle('mostrar');
            });
        });
    }

    const botaoReload = document.querySelector('#botao_reload');
    botaoReload.addEventListener('click', () => {
        document.querySelector('#bloco_loading').classList.add('mostrar');
        window.location.reload();
    });

    const botaoVoltar = document.querySelector('#botao_voltar');
    botaoVoltar.addEventListener('click', () => {
        document.querySelector('#bloco_loading').classList.add('mostrar');
    });
});
