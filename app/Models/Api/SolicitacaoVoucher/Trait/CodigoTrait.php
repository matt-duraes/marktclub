<?php

trait CodigoTrait
{
    private function gerarCodigoUnico()
    {
        $codigo = strCodigo();
        if ($this->existe(['codigo', $codigo])) {
            return $this->gerarCodigoUnico();
        }
        return $codigo;
    }
}
