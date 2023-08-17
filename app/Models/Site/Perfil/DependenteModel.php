<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use App\Helpers\ClubeApiHelper;
use App\Classes\UsuarioCliente\TipoUsuario;

final class DependenteModel extends ClubeApiHelper
{
    public function __construct()
    {
        if (sessao('USUARIO.tipo') == TipoUsuario::DEPENDENTE) {
            mensagemStatus(404);
        }
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    public function getDado()
    {
        $dado = $this
            ->validar('Página não encontrada!', status: 404)
            ->json([
                'usuario' => $this->idUsuario
            ])
            ->get('/usuario-dependente')
            ->object();

        return $dado->dado ?? [];
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDado(Request $request): Response
    {
        $salvar = $this
            ->validar('Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.')
            ->body([
                'nome'    => $this->Crypt->encode($request->nome),
                'email'   => $this->Crypt->encode($request->email),
                'cpf'     => $this->Crypt->encode($request->cpf),
                'usuario' => $this->idUsuario
            ])
            ->post('/usuario-dependente')
            ->object();
        return $this->montarRetornoPostDado($salvar);
    }

    /**
     * @param $dado
     *
     * @return Response
     * @throws Excecao
     */
    private function montarRetornoPostDado($dado): Response
    {
        $retorno = [];
        if ($dado->dado) {
            $r = $dado->dado;
            $retorno = (object)[
                'id'   => $r->id,
                'nome' => $this->Crypt->decode($r->nome),
            ];
        }
        return mensagemSucesso([
            'id'   => $retorno->id,
            'nome' => $retorno->nome
        ], 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDeletar(Request $request): Response
    {
        $this
            ->delete('/usuario-dependente/' . $request->id);
        return new Response(status: 204);
    }
}
