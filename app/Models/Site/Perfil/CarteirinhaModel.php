<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use App\Helpers\ClubeApiHelper;

final class CarteirinhaModel extends ClubeApiHelper
{
    public function getDado(): object|array
    {
        return (object) [
            'nome'            => 'João das Neves',
            'cpf'             => '07385666167',
            'endereco_estado' => 'Brasília-DF',
            'imagem'          => (object) [
                'logo'   => 'teste.png',
            ],
        ];
    }

    /**
     * @param $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($dado): object|array
    {
        $r = $dado->dado[0];
        return (object)[
            'nome'            => $this->Crypt->decode($r->usuario->nome) ?? '',
            'matricula'       => $this->Crypt->decode($r->usuario->matricula) ?? '',
            'cpf'             => $this->Crypt->decode($r->usuario->documento) ?? '',
            'rg'              => $this->Crypt->decode($r->usuario->documento_rg) ?? '',
            'endereco_estado' => $r->usuario->endereco_estado ?? '',
            'texto'           => (object)[
                'principal' => $r->texto->principal,
                'perdido'   => $r->texto->perdido
            ],
            'empresa' => (object)[
                'nome' => $r->empresa->nome
            ],
            'imagem' => (object)[
                'logo'   => $r->imagem->logo,
                'frente' => $r->imagem->frente,
                'fundo'  => $r->imagem->fundo
            ],
            'data' => (object)[
                'aniversario' => $r->data->aniversario ?? false,
                'filiacao'    => $r->data->data_filiacao ?? false,
                'emissao'     => $r->data->emissao ?? false,
            ]
        ];
    }
}
