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
            ->get('/carteirinha-clube')
            ->object();
        return $this->montarRetorno($dado->dado);
    }

    /**
     * @param $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($dado): object|array
    {
        return (object)[
            'usuario' => (object) [
                'nome'            => $this->Crypt->decode($dado->usuario->nome) ?? '',
                'matricula'       => $this->Crypt->decode($dado->usuario->matricula) ?? '',
                'cpf'             => $this->Crypt->decode($dado->usuario->cpf) ?? '',
                'data_nascimento' => $this->Crypt->decode($dado->usuario->data_nascimento) ?? '',
                'data_filiacao'   => $this->Crypt->decode($dado->usuario->data_filiacao) ?? '',
                'estado'          => $this->Crypt->decode($dado->usuario->estado) ?? '',
            ],
            'empresa'         => (object)[
                'nome' => $dado->empresa->nome
            ],
            'imagem' => (object)[
                'logo_principal'   => $dado->imagem->logo_principal,
                'logo_secundaria'  => $dado->imagem->logo_secundaria,
                'bg_frente'        => $dado->imagem->bg_frente,
                'bg_fundo'         => $dado->imagem->bg_fundo
            ],
            'data_emissao'     => $dado->data_emissao ?? '',
        ];
    }
}
