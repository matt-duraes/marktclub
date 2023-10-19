<?php

namespace App\Models\Site\PontoCvs;

use App\Helpers\ClubeApiHelper;

final class BuscarModel extends ClubeApiHelper
{
    public function buscarDados($pagina = 1)
    {
        $dados = $this
            ->json([
                'pagina'         => 1,
                'quantidade'     => 5,
                'cpf'            => $this->Crypt->encode(sessao('USUARIO.cpf')),
                'ordem'          => 'mais-novo',
            ])
            ->get('/ponto-cvs')
            ->object();

        if (!empty($dados->dado)) :
            foreach ($dados->dado->lista as $r) :
                $r->usuario_nome = $this->Crypt->decode($r->usuario_nome)($r->usuario_nome);
                $r->usuario_email = $this->Crypt->decode($r->usuario_email)($r->usuario_email);
            endforeach;
        endif;

        return $dados;
    }

    public function solicitarPontoCvs($dado)
    {
        $ponto = $dado->ponto ?? false;
        $nome = !empty($dado->nome) ? $this->Crypt->encode($dado->nome) : false;
        $email = !empty($dado->email) ? $this->Crypt->encode($dado->email) : false;
        $cpf = $this->Crypt->encode(sessao('USUARIO.cpf'));

        $buscar = $this
            ->validar('Erro ao fazer a requisição!', status: 400)
            ->body([
                'ponto_solicitado' => $ponto,
                'nome'             => $nome,
                'cpf'              => $cpf,
                'email'            => $email,
            ])
            ->post('/ponto-cvs')
            ->object();

        return $buscar;
    }

    public function extrato()
    {
        $buscar = $this
            ->validar('Erro ao fazer a requisição!', status: 400)
            ->json([
                'pagina' => 1,
                'cpf'    => $this->Crypt->encode(sessao('USUARIO.cpf')),
                'ordem'  => 'mais-novo'
            ])
            ->get('/ponto-cvs')
            ->object();

        return $buscar->dado->extrato ?? [];
    }
}
