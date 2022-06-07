<?php

namespace System\Config;

final class FunctionLoading
{
    public function __construct()
    {
        $diretorio = __DIR__ . '/../Function';
        $lista = array_diff(scandir($diretorio), ['.', '..']);
        if ($lista) {
            foreach ($lista as $arquivo) {
                if (file_exists($diretorio . '/' . $arquivo)) {
                    require_once $diretorio . '/' . $arquivo;
                }
            }
        }
    }
}
