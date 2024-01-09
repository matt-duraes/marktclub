<?php

function __executarTeste($diretorio, $classe)
{
    if (!file_exists(ROOT . '/tests/Backend/' . $diretorio . '/' . $classe . '.php')) {
        mensagemErro('Erro!', 'A classe ' . $classe . ' não existe para fazer os testes.');
    }
    $classNome = '\Tests\\' . $diretorio . '\\' . $classe;

    $listaTodos = (object)[
        'arquivo' => $classe,
        'class'   => $classNome . '::class',
        'test'    => (object)[
            'todos'  => [],
            'passou' => [],
            'falhou' => []
        ]
    ];

    if (!class_exists($classNome)) {
        mensagemErro('Erro!', 'A classe ' . $classNome . ' não existe para fazer os testes.');
    }

    $metodos = get_class_methods($classNome);
    $class = new $classNome();
    $automatico = $class->automatico;

    $metodos = array_filter($metodos, function ($item) {
        return !in_array(
            $item,
            [
                'listarTodosOsRegistrosTest', 'buscarPrimeiroRegistroTest',
                'salvarNovoRegistroTest', 'buscarRegistroAposSalvarTest',
                'atualizarPrimeiroCampoDoRegistroSalvoTest', 'verificaSeAtualizouPrimeiroRegistroTest'
            ]
        );
    });

    if (str_contains($automatico, 'u')) {
        $metodos = array_merge(['atualizarPrimeiroCampoDoRegistroSalvoTest', 'verificaSeAtualizouPrimeiroRegistroTest'], $metodos);
    }
    if (str_contains($automatico, 'r')) {
        $metodos = array_merge(['listarTodosOsRegistrosTest', 'buscarPrimeiroRegistroTest'], $metodos);
    }
    if (str_contains($automatico, 'c')) {
        $metodos = array_merge(['salvarNovoRegistroTest', 'buscarRegistroAposSalvarTest'], $metodos);
    }

    foreach ($metodos as $metodo) {
        if (!preg_match('/Test$/', $metodo)) {
            continue;
        }
        try {
            $dado = $class->$metodo();
            if (!($dado instanceof \Tests\Tests)) {
                $erroNaoTest = (object)[
                    'tipo'     => 'erro_geral',
                    'nome'     => __converterNomeDoTeste($metodo),
                    'mensagem' => 'O método ' . $metodo . ' não está retornando um \Tests\Tests'
                ];
                $listaTodos->test->todos[] = $erroNaoTest;
                $listaTodos->test->falhou[] = $erroNaoTest;
                continue;
            }
            $retorno = $dado->test();
            $curl = $retorno->curl;
            $resposta = [
                'tipo'      => 'test',
                'nome'      => __converterNomeDoTeste($metodo),
                'status'    => $retorno->status,
                'metodo'    => $retorno->metodo,
                'url'       => $retorno->url,
                'json'      => $curl ? $retorno->curl->json() : [],
                'body'      => $curl ? $retorno->curl->body() : [],
                'parametro' => $curl ? $retorno->curl->parametro() : [],
                'header'    => $curl ? $retorno->curl->header() : [],
                'resposta'  => $curl ? $retorno->curl->object() : [],
            ];

            if (count($retorno->todos) > 0) {
                $listaTodos->test->todos[] = (object)($resposta + ['test' => $retorno->todos]);
            }
            if (count($retorno->passou) > 0) {
                $listaTodos->test->passou[] = (object)($resposta + ['test' => $retorno->passou]);
            }
            if (count($retorno->falhou) > 0) {
                $listaTodos->test->falhou[] = (object)($resposta + ['test' => $retorno->falhou]);
            }
            continue;
        } catch (\Throwable $th) {
            $erroGeral = (object)[
                'tipo'     => 'erro',
                'nome'     => __converterNomeDoTeste($metodo),
                'mensagem' => $th->getMessage(),
                'linha'    => $th->getLine(),
                'arquivo'  => $th->getFile(),
                'trace'    => explode(PHP_EOL, $th->getTraceAsString())
            ];
            $listaTodos->test->todos[] = $erroGeral;
            $listaTodos->test->falhou[] = $erroGeral;
        }
    }
    if (method_exists($class, 'finalizarTeste')) {
        $class->finalizarTeste();
    }

    return jsonEncode($listaTodos);
}
return __executarTeste($_POST['diretorio'], $_POST['classe']);

function __converterNomeDoTeste($nome)
{
    $nome = preg_replace(['/([A-Z]{1})/', '/Test$/'], [' $0', ''], $nome);
    return ucfirst(mb_strtolower($nome, 'UTF-8'));
}
