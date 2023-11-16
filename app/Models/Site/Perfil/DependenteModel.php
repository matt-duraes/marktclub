<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use App\Helpers\ClubeApiHelper;
use App\Classes\UsuarioCliente\Status;
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

        return $this->montarDado($dado->dado ?? []);
    }

    private function montarDado($dado)
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'           => $r->id,
                'nome'         => $r->nome,
                'status'       => $r->status,
                'status_texto' => $Status->nome($r->status)
            ];
        }
        return $retorno;
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
            ->validar('Ocorre um erro ao salvar seu dependente, por favor, tente novamente.', login: true)
            ->body([
                'nome'    => $this->Crypt->encode($request->nome),
                'email'   => $this->Crypt->encode($request->email),
                'cpf'     => $this->Crypt->encode($request->cpf),
                'usuario' => sessao('USUARIO.id')
            ])
            ->post('/usuario-dependente')
            ->object()->dado;

        return [
            'id'           => $dado->id,
            'nome'         => $this->Crypt->decode($dado->nome),
            'status'       => $dado->status,
            'status_texto' => (new Status($dado->status))->nome()
        ];
    }

    public function reenviarConvite(Request $request): array
    {
        $dado = $this
            ->validar('Ocorreu um erro ao reenviar convite, por favor, tente novamente.')
            ->body([
                'usuario' => $request->id
            ])
            ->post('/usuario-dependente/email')
            ->object()->dado;
        return $dado;
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function deletarDependente(Request $request): void
    {
        $this
            ->validar('Erro ao deletar dependente, por favor, tente novamente.', login: true)
            ->delete('/usuario-dependente/' . $request->id);
    }
}
