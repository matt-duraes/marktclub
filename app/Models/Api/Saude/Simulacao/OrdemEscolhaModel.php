<?php

namespace App\Models\Api\Saude\Simulacao;

use ORM\ORM;

final class OrdemEscolhaModel extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_CONVENIO;

    public array $ordem;
    public function __construct(
        string $url
    )
    {
        parent::__construct();
        $this->ordem = jsonDecode($this->campo(['simulacao'])->where(['url', $url])->primeiro('simulacao'), true, true);
    }
}
