// @system "Funcao"
// @system "Alerta"
// @system "Ajuda"
// @system "Banner"
// @system "Mascara"
// @system "Calendario"
// @system "Esqueleto"
// @system "Form"
// @system "Player"
// @import "classe"

Alerta.mensagem('Erro', 'Erro', '!');

const botaoAlerta = $('#botao_alerta');
const botaoMensagem = $('#botao_mensagem');
let iNotificacao = 1;
botaoAlerta.evento('click', () => {
    Alerta.notificacao('Ola mundo ' + iNotificacao, true);
    iNotificacao++;
});
botaoAlerta.addEventListener('mouseover', () => {
    Ajuda.show(botaoAlerta, 'Ola mundo');
});
botaoAlerta.addEventListener('mouseout', () => {
    Ajuda.hide();
});
botaoMensagem.evento('click', () => {
    Alerta.confirmar('Ola mundo!', 'Tem certeza que deseja confirmar?', '!');
});

new Banner($('#banner'), 'figure', $('#proximo'), $('#anterior'));

Calendario.init($('#data'));
Calendario.init($('#data_de'), $('#data_ate'));
Calendario.init({
    input: $('#data_hora'),
    hora: true,
});
Calendario.init({
    input: $('#data_minima'),
    dataMinima: '2024-01-01',
});
Calendario.init({
    input: $('#data_maxima'),
    dataMaxima: '2024-01-01',
});
Calendario.init({
    input: $('#data_minima_maxima'),
    dataMinima: '2024-12-01',
    dataMaxima: '2024-12-31',
});
Calendario.init({
    input: $$('.data_geral'),
});

const EsqueletoItem = new Esqueleto($('#esqueleto'), '.esqueleto');
EsqueletoItem.show();
