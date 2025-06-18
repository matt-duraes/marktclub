<?php

namespace App\Models\Site\Saude\endereco;

use Modules\EnderecoEstado;
use App\Helpers\ClubeApiHelper;

final class CidadeModel extends ClubeApiHelper
{
    public array $retorno = [];
    public function __construct(
        EnderecoEstado $Estado
    )
    {

    }
}
