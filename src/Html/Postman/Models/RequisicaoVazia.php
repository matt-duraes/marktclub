<?php

namespace System\Html\Postman\Models;

final class RequisicaoVazia
{
    private Requisicao $Requisicao;
    public function __construct($post)
    {
        $this->Requisicao = (new Requisicao());
    }
    public function retorno()
    {
        return $this->Requisicao->requisicao;
    }
}
