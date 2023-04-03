<?php

use Erro\Erro;
use Erro\Alerta;
use Erro\Excecao;
use Erro\Retorno\ErroRetorno;
use Erro\Retorno\AlertaRetorno;
use Erro\Retorno\ExcecaoRetorno;
use JetBrains\PhpStorm\NoReturn;
use Erro\Retorno\ThrowableRetorno;
use Erro\Retorno\ErroLegadoRetorno;

set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');

/**
 * @param $retorno
 * @return void
 */
#[NoReturn] function imprimirErro($retorno): void
{
    if (is_array($retorno)) {
        echo json_encode($retorno, JSON_PARTIAL_OUTPUT_ON_ERROR);
    } elseif (
        is_object($retorno) &&
        defined('SISTEMA') &&
        SISTEMA != 'producao' &&
        method_exists(
            $retorno,
            '__toString'
        )
    ) {
        echo $retorno;
    } elseif (is_object($retorno) && defined('SISTEMA') && SISTEMA != 'producao') {
        echo '<pre>';
        print_r($retorno);
    } elseif (is_object($retorno) && (!defined('SISTEMA') || SISTEMA == 'producao')) {
        echo '';
    } else {
        echo $retorno;
    }
    exit();
}

/**
 * @param $error
 * @return void
 */
#[NoReturn] function exceptionHandler($error): void
{
    if ($error instanceof Excecao) {
        $retorno = (new ExcecaoRetorno($error))->html();
    } elseif ($error instanceof Alerta) {
        $retorno = (new AlertaRetorno($error))->html();
    } elseif ($error instanceof Erro) {
        $retorno = (new ErroRetorno($error))->html();
    } else {
        $retorno = (new ThrowableRetorno($error))->html();
    }
    imprimirErro($retorno);
}

/**
 * @param  int     $tipo
 * @param  string  $mensagem
 * @param  string  $arquivo
 * @param  int     $linha
 * @return void
 */
#[NoReturn] function errorHandler(int $tipo, string $mensagem, string $arquivo, int $linha): void
{
    ob_start();
    debug_print_backtrace();
    $traceString = ob_get_contents();
    ob_end_clean();
    imprimirErro((new ErroLegadoRetorno($tipo, $mensagem, $arquivo, $linha, debug_backtrace(), $traceString))->html());
}
