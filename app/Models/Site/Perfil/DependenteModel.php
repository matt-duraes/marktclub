<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Http\Request;
use Http\Response;

final class DependenteModel
{
    protected string $chave;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl->get('/admin/chave-publica')
            ->object()->dado->chave ?? '';

        $this->chave = $chave;
    }

    /**
     * @throws Excecao
     */
    public function getDado()
    {
        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';
        $Api = new ApiHelper('usuario_dependente:listar');

        $dado = $Api->validar('Página não encontrada!', status: 404)
            ->json([
                'usuario' => $id
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
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $Api = new ApiHelper('usuario_dependente:salvar');

        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';

        $salvar = $Api->body([
            'nome'    => $Crypt->encode($request->nome),
            'email'   => $Crypt->encode($request->email),
            'cpf'     => $Crypt->encode($request->cpf),
            'usuario' => $id
        ])->post('/usuario-dependente')->object();

        respostaJson(
            $salvar,
            'Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.'
        );
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
        $Curl = new ApiHelper('admin:chave_privada');
        $chave = $Curl->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $Crypt = new CryptHelper(chavePrivada: $chave);

        $retorno = [];
        if ($dado->dado) {
            $r = $dado->dado;
            $retorno = (object)[
                'id'   => $r->id,
                'nome' => $Crypt->decode($r->nome),
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
    public function postDeleta(Request $request): Response
    {
        // new CryptHelper(chavePublica: $this->chave);
        $Api = new ApiHelper('usuario_dependente:deletar');
        $salvar = $Api->delete('/usuario-dependente/' . $request->id);

        respostaJson(
            $salvar,
            'Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.'
        );

        return new Response(status: 204);
    }
}
