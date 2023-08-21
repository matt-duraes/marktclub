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
    public function listarDependente()
    {
        $dado = $this
            ->json([
                'usuario' => sessao('USUARIO.id')
            ])
            ->get('/usuario-dependente')
            ->object();

        return $dado->dado ?? [];
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws Excecao
     */
    public function salvarDependente(Request $request): array
    {
        $dado = $this
            ->validar('Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.', login: true)
            ->body([
                'nome'    => $this->Crypt->encode($request->nome),
                'email'   => $this->Crypt->encode($request->email),
                'cpf'     => $this->Crypt->encode($request->cpf),
                'usuario' => sessao('USUARIO.id')
            ])
            ->post('/usuario-dependente')
            ->object()->dado;

        return [
            'id'   => $dado->id,
            'nome' => $this->Crypt->decode($dado->nome),
        ];
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function deletarDependente(Request $request): Response
    {
        $this
            ->validar('Erro ao deletar dependente, por favor, tente novamente.', login: true)
            ->delete('/usuario-dependente/' . $request->id);
        return new Response(status: 204);
    }
}
