<?php

namespace App\Models\Api\Saude\Contratacao\Proasa;

use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use App\Models\Api\Saude\Contratacao\Proasa\Contato\Salvar as Contato;
use App\Models\Api\Saude\Contratacao\Proasa\Negociacao\Salvar as Negociacao;

final class Salvar
{
    public bool $salvou = false;

    public function __construct(
        Nome $Nome,
        Email $Email,
        Telefone $Telefone,
        string $empresa,
        array $simulacao
    )
    {
        new Negociacao(
            Contato: new Contato(
                Nome: $Nome,
                Email: $Email,
                Telefone: $Telefone,
            ),
            empresa: $empresa,
            simulacao: $simulacao
        );
        $this->salvou = true;
    }
}
