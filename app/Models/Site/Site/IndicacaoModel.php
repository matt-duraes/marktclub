<?php

namespace App\Models\Site\Site;

use Erro\Excecao;
use App\Helpers\ClubeApiHelper;

final class IndicacaoModel extends ClubeApiHelper
{
    protected string $chave;

    /**
     * @return object|array
     * @throws Excecao
     */
    public function indicarAmigo($request)
    {
        $dado = $this
            ->validar('Ocorreu um erro ao salvar sua indicação', status: 404)
            ->body([
                'usuario'  => sessao('USUARIO.id'),
                'nome'     => $this->Crypt->encode($request->nome),
                'email'    => $this->Crypt->encode($request->email),
                'telefone' => $this->Crypt->encode($request->telefone),
            ])
            ->post('/usuario-indicacao')
            ->object();
        return $dado;
    }
}
