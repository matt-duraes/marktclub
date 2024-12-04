<?php

namespace System\Html\Postman\Models\Trait;

use System\Html\Postman\Models\Interface\TokenInterface;

trait TokenConstrutorTrait
{
    private function buscarPathArquivo()
    {
        $this->arquivo = listarArquivoDiretorio(ROOT . '/postman/token', final: 'Token', ext: ['php']);
    }

    private function setarClasse()
    {
        $retorno = [];
        foreach ($this->arquivo as $arquivo) {
            $nomeArquivo = preg_replace('/.php$/', '', $arquivo);
            $namespace = '\\Postman\Token\\' . $nomeArquivo;
            if (!class_exists($namespace)) {
                continue;
            };
            $Classe = new $namespace();
            if (!$Classe instanceof TokenInterface) {
                continue;
            }
            $retorno[] = $Classe;
        }
        $this->classe = $this->ordenarClasse($retorno);
    }

    private function ordenarClasse($lista)
    {
        $titulo = [];
        foreach ($lista as $Classe) {
            $titulo[$Classe->titulo] = $Classe;
        }
        asort($titulo);
        $retorno = [];
        foreach ($lista as $Classe) {
            $retorno[$Classe->indice] = $Classe;
        }
        return $retorno;
    }
}
