<?php

$Doc = new DocumentacaoConfig\Fw('PLUGIN DE ALERTA', 'Cria alertas, notificações e blocos de confirmação.');
$Doc->systemCssJs('Alerta');

$Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Mensagem')
            ->descricao('Gera um alerta de box com título e mensagem.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', 'titulo', 'Título que deve ser mostrado')
                    ->parametro('string', 'mensagem', 'Mensagem que deve ser mostrada')
                    ->parametro('string|bool', 'icone', 'Icone que deve ser mostrando podendo ser true para ok, false para erro, ! para alerta ou vazio')
                    ->parametro('bool', 'fechar', 'Se o alerta pode ser fechado clicando no box preto, por padrão é true');
            })
            ->codigo('Alerta.mensagem(titulo, mensagem, icone, fechar)')
            ->blocoExemplo(function () use ($Doc) {
                $Doc
                    ->exemploBotao('Alerta positivo', 'Alerta.mensagem("Teste", "Olá mundo", true);')
                    ->exemploBotao('Alerta com negativa', 'Alerta.mensagem("Teste", "Olá mundo", false);')
                    ->exemploBotao('Alerta com exclamação', 'Alerta.mensagem("Teste", "Olá mundo", "!");')
                    ->exemploBotao('Alerta sem icone', 'Alerta.mensagem("Teste", "Olá mundo");')
                    ->exemploBotao('Alerta não pode fechar', 'Alerta.mensagem("Teste", "Olá mundo", true, false);');
            })
            ->paragrafo('Você ainda pode fazer uma ação ao aceitar ou fechar o alerta já que ele é uma promisse.')
            ->codigo('const alertaPromise = async () => {' . PHP_EOL . '    const resposta = await Alerta.mensagem("Teste", "Olá mundo", true);' . PHP_EOL . '    if(resposta) {' . PHP_EOL . '        alert("O Alerta foi fechado.")' . PHP_EOL . '    }' . PHP_EOL . '};' . PHP_EOL . 'alertaPromise();')
            ->blocoExemplo(function () use ($Doc) {
                $Doc
                    ->exemploBotao('Alerta com retorno', '
    const resposta = await Alerta.mensagem("Teste", "Olá mundo", true);
    if(resposta) {
        alert("O Alerta foi fechado.")
    }', true);
            });
    });

$Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Notificação')
            ->descricao('Gera um alerta de notificação com uma mensagem.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', 'mensagem', 'Mensagem que deve ser mostrada')
                    ->parametro('string|bool', 'icone', 'Icone que deve ser mostrando podendo ser true para ok, false para erro ou vazio');
            })
            ->codigo('Alerta.notificacao(mensagem, icone)')
            ->exemploBotao('Notificação positiva', 'Alerta.notificacao("Olá mundo", true);')
            ->exemploBotao('Notificação com negativa', 'Alerta.notificacao("Olá mundo", false);')
            ->exemploBotao('Notificação sem icone', 'Alerta.notificacao("Olá mundo");')
            ->paragrafo('Você ainda pode fazer uma ação ou fechar o alerta já que ele é uma promisse.')
            ->codigo('const notificacaoPromise = async () => {' . PHP_EOL . '    const resposta = await Alerta.notificacao("Olá mundo", true);' . PHP_EOL . '    if(resposta) {' . PHP_EOL . '        alert("A notificação foi fechada.")' . PHP_EOL . '    }' . PHP_EOL . '};' . PHP_EOL . 'notificacaoPromise();')
            ->exemploBotao('Notificação com retorno', '
const resposta = await Alerta.notificacao("Olá mundo", true);
if(resposta) {
    alert("A notificação foi fechada.")
}', true);
    });

$Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Confirmar')
            ->descricao('Gera um alerta de box do tipo confirmar com título e mensagem.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', 'titulo', 'Título que deve ser mostrado')
                    ->parametro('string', 'mensagem', 'Mensagem que deve ser mostrada')
                    ->parametro('string|bool', 'icone', 'Icone que deve ser mostrando podendo ser true para ok, false para erro, ! para alerta ou vazio')
                    ->parametro('bool', 'fechar', 'Se o alerta pode ser fechado clicando no box preto, por padrão é true');
            })
            ->codigo('Alerta.confirmar(titulo, mensagem, icone, fechar)')
            ->exemploBotao('Confirmar positivo', 'Alerta.confirmar("Teste", "Olá mundo", true);')
            ->exemploBotao('Confirmar com negativa', 'Alerta.confirmar("Teste", "Olá mundo", false);')
            ->exemploBotao('Confirmar com exclamação', 'Alerta.confirmar("Teste", "Olá mundo", "!");')
            ->exemploBotao('Confirmar sem icone', 'Alerta.confirmar("Teste", "Olá mundo");')
            ->exemploBotao('Confirmar não pode fechar', 'Alerta.confirmar("Teste", "Olá mundo", true, false);')
            ->paragrafo('Você ainda pode fazer uma ação ao aceitar e outra ao negar o alerta já que ele é uma promisse.')
            ->codigo('const confirmarPromise = async () => {' . PHP_EOL . '    const resposta = await Alerta.confirmar("Teste", "Olá mundo", true);' . PHP_EOL . '    if(resposta) {' . PHP_EOL . '        alert("O Alerta foi confirmado.")' . PHP_EOL . '    } else {' . PHP_EOL . '        alert("O alerta foi negado.");' . PHP_EOL . '}' . PHP_EOL . '};' . PHP_EOL . 'alertaPromise();')
            ->exemploBotao('Confirmar com retorno', '
const resposta = await Alerta.confirmar("Teste", "Olá mundo", true);
if(resposta) {
    alert("O Alerta foi confirmado.")
} else {
    alert("O Alerta foi negado.")
}', true);
    });

echo $Doc;
