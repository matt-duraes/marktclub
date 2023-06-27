<?php

namespace System\Html\Postman\Models;

final class RequisicaoBuscar
{
    private Requisicao $Requisicao;
    private string $path = ROOT . '/postman/';
    private string $id;
    private string $pai;
    public function __construct($post)
    {
        $this->id = $post['id'];
        $this->pai = $post['pai'];

        $this->Requisicao = (new Requisicao($this->path, $this->pai, $this->id));
    }
    public function retorno()
    {
        return $this->Requisicao->requisicao;
    }
}
