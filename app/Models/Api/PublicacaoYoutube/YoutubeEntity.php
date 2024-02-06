<?php

namespace App\Models\Api\PublicacaoYoutube;

use ORM\Entity;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class YoutubeEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_YOUTUBE;
    protected array $ormSalvar = [];
    protected array $ormBuscar = [];

    public function __construct()
    {
        $this->validarEmpresa();
    }
}

