<?php

spl_autoload_register(function ($namespace) {
    if (preg_match('/^\\\{0,1}Templates/', $namespace)) {
        $namespace = explode('\\', preg_replace('/^\\\{0,1}Templates\\\/', '', $namespace));
        $path = ROOT . '/views/templates';
        $teste = 1;
    } else {
        $namespace = explode('\\', preg_replace('/^\\\{0,1}Templates\\\/', '', $namespace));
        $path = ROOT . '/views/pages';
        $teste = 2;
    }

    $arquivo = array_pop($namespace);

    foreach ($namespace as $diretorioReal) {
        $diretorioSnake = mb_strtolower(preg_replace(['/([A-Z]{1})/', '/^\_/'], ['_$1', ''], $diretorioReal), 'UTF-8');

        $pathLista = listarArquivoDiretorio($path);
        if (!$pathLista) {
            return;
        }

        foreach ($pathLista as $diretorioTemp) {
            if (strcasecmp($diretorioReal, $diretorioTemp) == 0 || strcasecmp($diretorioSnake, $diretorioTemp) == 0) {
                $path .= '/' . $diretorioTemp;
                break;
            }
        }
    }

    $path = $path . '/' . $arquivo . '.php';
    if (file_exists($path)) {
        require $path;
    }
});
