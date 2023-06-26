<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\SocialHelper;
use Helpers\CryptHelper;
use Http\Request;
use Http\Response;

final class DadosModel
{
    protected string $chave;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl
            ->get('/admin/chave-publica')
            ->object()->dado->chave ?? '';
        $this->chave = $chave;
    }


    /**
     * @return object|array
     * @throws Excecao
     */
    public function getDado(): object|array
    {
        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';
        $Api = new ApiHelper('usuario_cliente:buscar');

        $dado = $Api->validar('Página não encontrada!', status: 404)
            ->get('/usuario-cliente/' . $id)
            ->object();
        return $this->montarRetorno($dado);
    }

    /**
     * @param  $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($dado): object|array
    {
        $Curl = new ApiHelper('admin:chave_privada');
        $chave = $Curl->get('/admin/chave-privada')
            ->object()->dado->chave ?? '';
        $Crypt = new CryptHelper(chavePrivada: $chave);

        $retorno = [];
        if ($dado->dado) {
            $r = $dado->dado;
            $retorno = (object)[
                'id'                   => $r->id,
                'nome'                 => $Crypt->decode($r->nome) ?? '',
                'cpf'                  => $Crypt->decode($r->cpf) ?? '',
                'email_pessoal'        => $Crypt->decode($r->email_pessoal) ?? '',
                'email_trabalho'       => $Crypt->decode($r->email_trabalho) ?? '',
                'telefone_trabalho'    => $Crypt->decode($r->telefone_trabalho) ?? '',
                'telefone_pessoal'     => $Crypt->decode($r->telefone_pessoal) ?? '',
                'estado_civil'         => $Crypt->decode($r->estado_civil) ?? '',
                'genero'               => $Crypt->decode($r->genero) ?? '',
                'data_nascimento'      => $Crypt->decode($r->data_nascimento) ?? '',
                'endereco_cep'         => $Crypt->decode($r->endereco_cep) ?? '',
                'endereco_logradouro'  => $Crypt->decode($r->endereco_logradouro) ?? '',
                'endereco_numero'      => $Crypt->decode($r->endereco_numero) ?? '',
                'endereco_complemento' => $Crypt->decode($r->endereco_complemento) ?? '',
                'endereco_bairro'      => $Crypt->decode($r->endereco_bairro) ?? '',
                'endereco_cidade'      => $Crypt->decode($r->endereco_cidade) ?? '',
                'endereco_estado'      => $Crypt->decode($r->endereco_estado) ?? ''
            ];
        }

        return $retorno;
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDado(Request $request): Response
    {
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $Api = new ApiHelper('usuario_cliente:atualizar');

        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';

        $salvar = $Api->body([
            'nome'                 => $Crypt->encode($request->nome),
            'data_nascimento'      => $Crypt->encode($request->data_nascimento),
            'genero'               => $Crypt->encode($request->genero),
            'estado_civil'         => $Crypt->encode($request->estado_civil),
            'email_pessoal'        => $Crypt->encode($request->email_pessoal),
            'email_trabalho'       => $Crypt->encode($request->email_trabalho),
            'telefone_trabalho'    => $Crypt->encode($request->telefone_trabalho),
            'telefone_pessoal'     => $Crypt->encode($request->telefone_pessoal),
            'endereco_estado'      => $Crypt->encode($request->endereco_estado),
            'endereco_cep'         => $Crypt->encode($request->endereco_cep),
            'endereco_logradouro'  => $Crypt->encode($request->endereco_logradouro),
            'endereco_bairro'      => $Crypt->encode($request->endereco_bairro),
            'endereco_numero'      => $Crypt->encode($request->endereco_numero),
            'endereco_complemento' => $Crypt->encode($request->endereco_complemento),
            'endereco_cidade'      => $Crypt->encode($request->endereco_cidade)
        ])->put('/usuario-cliente/' . $id);

        respostaJson(
            $salvar,
            'Ocorre um erro ao atualizar seus dados, por favor, tente novamente.'
        );

        return new Response(status: 204);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postImagemSocial(Request $request)
    {
        $imagem = $this->pegarIdRedeSocial($request);
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';

        $dadosApi = (new ApiHelper())
        ->body([
            'foto_perfil' => $imagem ? $Crypt->encode($imagem) : null,
        ])
        ->put('/usuario-cliente/' . $id);

        if($imagem) {
            sessao('USUARIO.imagem', $imagem);
            return mensagemSucesso([
                'imagem' => $imagem
            ], status: 201);
        }
    }

    /**
     * @param  $request
     *
     * @return Response|void
     * @throws Excecao
     */
    private function pegarIdRedeSocial($request)
    {
        $Social = new SocialHelper(
            $request->rede,
            $request->id,
            $request->token,
            $request->code
        );

        if ($request->acao == 'imagem') {
            return $this->vincularImagem($Social, $request->rede);
        }
    }

    /**
     * @param  SocialHelper  $Social
     * @param                $rede
     *
     * @return Response
     * @throws Excecao
     */
    private function vincularImagem(SocialHelper $Social, $rede)
    {
        return  ($rede == 'google') ? $Social->imagem() : '';
    }
}
