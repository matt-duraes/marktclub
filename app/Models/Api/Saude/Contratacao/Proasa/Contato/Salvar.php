<?php

namespace App\Models\Api\Saude\Contratacao\Proasa\Contato;

use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use App\Models\Api\Saude\Contratacao\Proasa\ApiAbstract;

final class Salvar extends ApiAbstract
{
    public string $id = '';

    public function __construct(
        private Nome $Nome,
        private Email $Email,
        private Telefone $Telefone
    )
    {
        parent::__construct();
        $Contato = new Buscar(Telefone: $Telefone);
        if($Contato->existe) {
            $this->atualizarUsuario($Contato->id);
            return;
        }
        $this->salvarUsuario();
    }

    private function atualizarUsuario(string $id): void
    {
        $salvar = $this
            ->header($this->headerAcceptJson())
            ->json($this->pegarDadoUsuario())
            ->put($this->uri('/contacts/' . $id))
            ->array();
        $this->validarDadoSalvo($salvar);
    }

    private function salvarUsuario(): void
    {
        $salvar = $this
            ->header($this->headerAcceptJson())
            ->json($this->pegarDadoUsuario())
            ->post($this->uri('/contacts'))
            ->array();

        $this->validarDadoSalvo($salvar);
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
