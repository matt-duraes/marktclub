<?php

namespace App\Models\Api\Parceiro\Externo\Trait;

use Modules\Data;
use App\Classes\ParceiroLoja\Status;
use App\Classes\Parceiro\Externo\Ordem;
use App\Classes\ParceiroCashback\Categoria;

trait PropriedadeTrait
{
    public string $equipe;
    public Data $data_criacao_de;
    public Data $data_criacao_ate;
    public Status $status;
    public Ordem $ordem;
    public Categoria $categoria;
    public string $pesquisa;
    public array $endereco_estado;
}
