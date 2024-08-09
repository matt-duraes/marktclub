<?php

namespace App\Models\Api\Parceiro\Externo\Trait;

use Modules\Data;
use App\Classes\ParceiroLoja\Status;
use App\Classes\Parceiro\Externo\Ordem;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroCashback\Categoria;

trait PropriedadeTrait
{
    public string $equipe;
    public Data $data_criacao_de;
    public Data $data_criacao_ate;
    public Status $status;
    public Ordem $ordem;
    public Categoria $categoria_principal;
    public string $pesquisa;
    public Indicador $tipo_indicador;
    public array $endereco_estado;
}
