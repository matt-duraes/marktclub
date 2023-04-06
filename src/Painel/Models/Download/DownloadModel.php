<?php

namespace PainelModel\Download;

use Helpers\ExcelHelper;

final class DownloadModel
{
    public string $arquivo;
    public string $link;

    private array $titulo;

    public function __construct(
        private array $lista,
        private array $replace,
        private string $app
    ) {
        $this->setarTitulo();
        $this->gerarArquivo();
    }

    private function setarTitulo()
    {
        $replace = $this->replace;
        $titulo = [];

        foreach (array_keys($this->lista[0]) as $val) {
            $titulo[] = array_key_exists($val, $replace) ? $replace[$val] : $val;
        }

        $this->titulo = $titulo;
    }
    private function gerarArquivo()
    {
        $Excel = new ExcelHelper(border: true);
        $Excel->titulo($this->titulo);
        foreach ($this->lista as $linha) {
            $Excel->linha($linha);
        }

        $nome = 'relatorio-' . $this->app . '-' . date('Y-m-d-h-m-s');
        $Excel->download($nome);
    }
}
