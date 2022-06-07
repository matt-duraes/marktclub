<?php

use Erro\Retorno\ErroRetorno;
use Erro\Retorno\AlertaRetorno;
use Erro\Retorno\ExcecaoRetorno;
use Erro\Retorno\ThrowableRetorno;
use Erro\Retorno\ErroLegadoRetorno;

set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');

function imprimirErro($retorno)
{
    if (is_array($retorno)) {
        echo json_encode($retorno, JSON_PARTIAL_OUTPUT_ON_ERROR);
    } elseif (is_object($retorno) && defined('SISTEMA') && SISTEMA != 'producao' && method_exists($retorno, '__toString')) {
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
function exceptionHandler($error)
{
    if ($error instanceof \Erro\Excecao) {
        $retorno = (new ExcecaoRetorno($error))->html();
    } elseif ($error instanceof \Erro\Alerta) {
        $retorno = (new AlertaRetorno($error))->html();
    } elseif ($error instanceof \Erro\Erro) {
        $retorno = (new ErroRetorno($error))->html();
    } else {
        $retorno = (new ThrowableRetorno($error))->html();
    }
    imprimirErro($retorno);
}

function errorHandler(int $tipo, string $mensagem, string $arquivo, int $linha)
{
    ob_start();
    debug_print_backtrace();
    $traceString = ob_get_contents();
    ob_end_clean();
    imprimirErro((new ErroLegadoRetorno($tipo, $mensagem, $arquivo, $linha, debug_backtrace(), $traceString))->html());
};
