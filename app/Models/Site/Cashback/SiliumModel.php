<?php

namespace App\Models\Site\Cashback;

use App\Classes\SiliumComissao\Ordem;
use App\Classes\SiliumDeposito\TipoOperacao;
use App\Helpers\ClubeApiHelper;
use Erro\Excecao;

final class SiliumModel extends ClubeApiHelper
{
    /**
     * @return array
     * @throws Excecao
     */
    public function buscarDados(): array
    {
        return [
            'extrato_compra' => $this->extratoCompra(),
            'extrato_saque'  => $this->extratoSaque(),
            'saldo'          => $this->saldo()
        ];
    }

    /**
     * @return mixed
     * @throws Excecao
     */
    public function extratoCompra(): mixed
    {
        $dado = $this
            ->validar('Não foi possível pegar extrato!', status: 404)
            ->json([
                'pagina'  => 1,
                'cliente' => sessao('USUARIO.id'),
                'ordem'   => Ordem::MAIS_NOVO
            ])
            ->get('/silium-comissao')
            ->object();
        return $dado->dado->lista;
    }

    /**
     * @return mixed
     * @throws Excecao
     */
    public function extratoSaque(): mixed
    {
        $dado = $this
            ->json([
                'pagina'        => 1,
                'usuario'       => sessao('USUARIO.id'),
                'tipo_operacao' => TipoOperacao::SAQUE,
                'ordem'         => Ordem::MAIS_NOVO
            ])
            ->get('/silium-deposito')
            ->object();
        return $dado->dado->lista;
    }

    /**
     * @return mixed
     * @throws Excecao
     */
    public function saldo(): mixed
    {
        $dado = $this
            ->validar('Não foi possível resgatar saldo!', status: 404)
            ->get('/silium-saldo/' . sessao('USUARIO.id'))
            ->object();
        return $dado->dado->saldo_silium;
    }

    /**
     * @return mixed
     * @throws Excecao
     */
    public function pontosResgate(): mixed
    {
        $dado = $this
            ->validar('Não foi possível pegar os pontos necessários!', status: 404)
            ->get('/silium-admin')
            ->object();
        return $dado->dado;
    }

    /**
     * @param $dados
     *
     * @return object|bool|array
     * @throws Excecao
     */
    public function solicitarDeposito($dados): object|bool|array
    {
        return $this
            ->validar('Ocorreu um erro ao fazer a solicitação')
            ->body([
                'tipo_operacao' => TipoOperacao::SAQUE,
                'usuario'       => sessao('USUARIO.id'),
                'email'         => $dados['email'],
                'pontuacao'     => $dados['pontos'],
                'nome_titular'  => $dados['titular'],
                'documento_cpf' => $dados['cpf'],
                'banco'         => $dados['banco'],
                'agencia'       => $dados['agencia'],
                'conta'         => $dados['contaBancaria'],
                'tipo_conta'    => $dados['tipoConta'],
                'tipo_resgate'  => $dados['tipoResgate']
            ])
            ->post('/silium-deposito')
            ->object();
    }
}
