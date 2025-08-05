<?php

namespace App\Models\Api\Saude\Contratacao\Proasa;

use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use App\Models\Api\Saude\Contratacao\Proasa\ApiAbstract;

final class Salvar extends ApiAbstract
{
    public bool $salvou = false;

    public function __construct(
        private Nome $Nome,
        private Email $Email,
        private Telefone $Telefone
    )
    {
        parent::__construct();
        $existe = (new Buscar(Telefone: $Telefone))->existe;
        if($existe) {
            $this->salvou = true;
            return;
        }
        $this->salvarUsuario();
    }

    private function salvarUsuario()
    {
        $salvar = $this
            ->header($this->headerAcceptJson())
            ->json($this->pegarDadoUsuario())
            ->post($this->uri('/contacts'))
            ->array();
        $this->salvou = validarIndiceExiste($salvar, 'name') && !empty($salvar['name']);
    }

    private function pegarDadoUsuario()
    {
        return [
            "contact" => [
                "emails" => [
                    [
                        "email" => $this->Email->email()
                    ]
                ],
                "name" => $this->Nome->nome(),
                "phones" => [
                    [
                        "phone" => $this->Telefone->numero()
                    ]
                ]
            ]
        ];
    }
}
