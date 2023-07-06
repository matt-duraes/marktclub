<?php

namespace System\Html\Postman\Models;

final class RequisicaoDeletar
{
    private Requisicao $Requisicao;
    private string $path = ROOT . '/postman/';
    private string $id;
    private string $pai;

    public function __construct($post)
    {
        $this->id = $post['id'];
        $this->pai = $post['pai'];

        (new Requisicao($this->path, $this->pai, $this->id))->deletar();
    }

    public function retorno()
    {
        return [];
    }
}
