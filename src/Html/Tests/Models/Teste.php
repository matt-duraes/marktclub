<?php

function __executarTeste($listaTeste)
{
    $retornoFinal = (object)[
        'todos' => 0,
        'passou' => 0,
        'falhou' => 0,
        'lista' => []
    ];

    foreach ($listaTeste as $arquivo) {
        if (sessaoExiste('TOKEN_LOGIN_PAINEL_TEST')) {
            sessaoDeletar('TOKEN_LOGIN_PAINEL_TEST');
        }

        if (!file_exists(ROOT . '/tests/Api/' . $arquivo . '.php')) {
            mensagemErro('Erro!', 'O arquivo ' . $arquivo . ' não existe para fazer os testes.');
        }
        $classNome = '\Tests\Api\\' . $arquivo;

        $listaTodos = (object)[
            'arquivo' => $arquivo,
            'class' => $classNome . '::class',
            'test' => (object)[
                'todos' => [],
                'passou' => [],
                'falhou' => []
            ]
        ];

        if (!class_exists($classNome)) {
            mensagemErro('Erro!', 'A classe ' . $classNome . ' não existe para fazer os testes.');
        }

        $metodos = get_class_methods($classNome);
        $class = new $classNome();

        foreach ($metodos as $metodo) {
            if (!preg_match('/Test$/', $metodo)) {
                continue;
            }
            try {
                $dado = $class->$metodo();
                if (!($dado instanceof \Tests\Tests)) {
                    $erroNaoTest = (object)[
                        'tipo' => 'erro_geral',
                        'nome' => __converterNomeDoTeste($metodo),
                        'mensagem' => 'O método ' . $metodo . ' não está retornando um \Tests\Tests'
                    ];
                    $listaTodos->test->todos[] = $erroNaoTest;
                    $listaTodos->test->falhou[] = $erroNaoTest;
                    continue;
                }
                $retorno = $dado->test();
                $curl = $retorno->curl;
                $resposta = [
                    'tipo' => 'test',
                    'nome' => __converterNomeDoTeste($metodo),
                    'status' => $retorno->status,
                    'metodo' => $retorno->metodo,
                    'url' => $retorno->url,
                    'json' => $curl ? $retorno->curl->json() : [],
                    'body' => $curl ? $retorno->curl->body() : [],
                    'parametro' => $curl ? $retorno->curl->parametro() : [],
                    'header' => $curl ? $retorno->curl->header() : [],
                    'resposta' => $curl ? $retorno->curl->object() : [],
                ];

                if (count($retorno->todos) > 0) {
                    $retornoFinal->todos += count($retorno->todos);
                    $listaTodos->test->todos[] = (object)($resposta + ['test' => $retorno->todos]);
                }
                if (count($retorno->passou) > 0) {
                    $retornoFinal->passou += count($retorno->passou);
                    $listaTodos->test->passou[] = (object)($resposta + ['test' => $retorno->passou]);
                }
                if (count($retorno->falhou) > 0) {
                    $retornoFinal->falhou += count($retorno->falhou);
                    $listaTodos->test->falhou[] = (object)($resposta + ['test' => $retorno->falhou]);
                }
                continue;
            } catch (\Throwable $th) {
                $retornoFinal->todos++;
                $retornoFinal->falhou++;
                $erroGeral = (object)[
                    'tipo' => 'erro',
                    'nome' => __converterNomeDoTeste($metodo),
                    'mensagem' => $th->getMessage(),
                    'linha' => $th->getLine(),
                    'arquivo' => $th->getFile(),
                    'trace' => explode(PHP_EOL, $th->getTraceAsString())
                ];
                $listaTodos->test->todos[] = $erroGeral;
                $listaTodos->test->falhou[] = $erroGeral;
            }
        }
        $retornoFinal->lista[] = $listaTodos;
    }
    return $retornoFinal;
}
$Testes = __executarTeste($listaTeste);

function __converterNomeDoTeste($nome)
{
    $nome = preg_replace(['/([A-Z]{1})/', '/Test$/'], [' $0', ''], $nome);
    return ucfirst(mb_strtolower($nome, 'UTF-8'));
}
