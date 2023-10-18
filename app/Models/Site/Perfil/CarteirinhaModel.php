<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use App\Helpers\ClubeApiHelper;

final class CarteirinhaModel extends ClubeApiHelper
{
    public function getDado(): object|array
    {
        $dado = $this
            ->validar('Página não encontrada!', status: 404)
            ->get('/usuario-carteirinha/' . sessao('USUARIO.id'))
            ->object();
        if(empty($dado->dado)) {
            return $dado;
        }
        return $this->montarRetorno($dado);
    }

    /**
     * @param $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($dado): object|array
    {
        $r = end($dado->dado);
        return (object)[
            'nome'            => $this->Crypt->decode($r->usuario->nome) ?? '',
            'matricula'       => $this->Crypt->decode($r->usuario->matricula) ?? '',
            'cpf'             => $this->Crypt->decode($r->usuario->cpf) ?? '',
            'data_nascimento' => $this->Crypt->decode($r->usuario->data_nascimento) ?? '',
            'data_filiacao'   => $this->Crypt->decode($r->usuario->data_filiacao) ?? '',
            'estado'          => $this->Crypt->decode($r->usuario->estado) ?? '',
            'empresa'         => (object)[
                'nome' => $r->empresa->nome
            ],
            'imagem' => (object)[
                'logo_principal'   => $r->imagem->logo_principal,
                'logo_secundaria'  => $r->imagem->logo_secundaria,
                'bg_frente'        => $r->imagem->bg_frente,
                'bg_fundo'         => $r->imagem->bg_fundo
            ],
            'data' => (object)[
                'emissao'     => $r->data->emissao ?? false,
            ]
        ];
    }
}
