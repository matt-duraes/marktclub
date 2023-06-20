<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Helpers\SocialHelper;
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
        // $chave = $Curl->get('/admin/chave-publica')
        $chave = $Curl->get('/admin/chave-publica')->header([
            'Authorization'  => 'Bearer eyJraWQiOiI0MGI1YTlhMC0xYjAxLTRlMmYtOThmZi00ZTRiODcxZTVkYzciLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2xvY2FsaG9zdDo0MDAwL2FwaSIsInN1YiI6InRlc3RlIiwiYXVkIjoid2ViIiwiaWF0IjoxNjg2ODYxNDIwLCJleHAiOjE2ODY5MTE0MjAsImF6cCI6IjYzOTE3OTMxOTMtc3pJYW91a2lJaCQ2MlNII21JUFBZa3JubiR5bEhMMkxpMypUNFNNUXlodjFVVWVpUnhBKiU4eU93ZFN0MUJrQkVQVEkjJU9ETFUubG9jYWxob3N0LmNvbSIsInNjb3BlIjoidG9rZW5fY3JlZGVudGlhbDpzYWx2YXIgdXN1YXJpb19jbGllbnRlOnNhbHZhciB1c3VhcmlvX2NsaWVudGU6YXR1YWxpemFyIHVzdWFyaW9fY2xpZW50ZTpsaXN0YXIgdXN1YXJpb19jbGllbnRlOmJ1c2NhciB1c3VhcmlvX2NsaWVudGU6ZGVsZXRhciB1c3VhcmlvX2NsaWVudGU6ZGVsZXRhcl9jcGYgdXN1YXJpb19jbGllbnRlOmRvd25sb2FkIHVzdWFyaW9fZGVwZW5kZW50ZTpzYWx2YXIgdXN1YXJpb19kZXBlbmRlbnRlOmxpc3RhciB1c3VhcmlvX2RlcGVuZGVudGU6ZGVsZXRhciB1c3VhcmlvX2VxdWlwZTpzYWx2YXIgdXN1YXJpb19lcXVpcGU6YXR1YWxpemFyIHVzdWFyaW9fZXF1aXBlOmxpc3RhciB1c3VhcmlvX2VxdWlwZTpidXNjYXIgdXN1YXJpb19lcXVpcGU6ZGVsZXRhciB1c3VhcmlvX2VxdWlwZTp2YWxpZGFyX3NlbmhhIHVzdWFyaW9fbGVhZDpzYWx2YXIgdXN1YXJpb19sZWFkOmF0dWFsaXphciB1c3VhcmlvX2xlYWQ6bGlzdGFyIHVzdWFyaW9fbGVhZDpidXNjYXIgdXN1YXJpb19sZWFkOmRlbGV0YXIgdXN1YXJpb19wYWdhbWVudG86bGlzdGFyIHVzdWFyaW9fcGFnYW1lbnRvOmJ1c2NhciB1c3VhcmlvX3BhZ2FtZW50bzpzYWx2YXIgdXN1YXJpb19wYWdhbWVudG86YXR1YWxpemFyIHVzdWFyaW9faW5kaWNhY2FvOnNhbHZhciB1c3VhcmlvX2luZGljYWNhbzphdHVhbGl6YXIgdXN1YXJpb19pbmRpY2FjYW86bGlzdGFyIHVzdWFyaW9faW5kaWNhY2FvOmJ1c2NhciB1c3VhcmlvX2luZGljYWNhbzpkZWxldGFyIHVzdWFyaW9fZ3J1cG86c2FsdmFyIHVzdWFyaW9fZ3J1cG86YXR1YWxpemFyIHVzdWFyaW9fZ3J1cG86bGlzdGFyIHVzdWFyaW9fZ3J1cG86YnVzY2FyIHVzdWFyaW9fZ3J1cG86ZGVsZXRhciB0YWJlbGFfdXN1YXJpbzpzYWx2YXIgdGFiZWxhX3VzdWFyaW86YmxvcXVlYXIgc29saWNpdGFjYW9fdm91Y2hlcjpsaXN0YXIgc29saWNpdGFjYW9fdm91Y2hlcjpidXNjYXIgc29saWNpdGFjYW9fc2FsYXZpcDpsaXN0YXIgc29saWNpdGFjYW9fdm91Y2hlcjpkb3dubG9hZCBzb2xpY2l0YWNhb192b3VjaGVyOnNhbHZhciBzb2xpY2l0YWNhb19zYWxhdmlwOmRvd25sb2FkIHZvdWNoZXI6c2FsdmFyIHZvdWNoZXI6dmVyaWZpY2FyIHZvdWNoZXI6dmFsaWRhciByZWxhdG9yaW9fYW5hbHl0aWNzOmxpc3RhciByZWxhdG9yaW9fYW5hbHl0aWNzOmRvd25sb2FkIHJlbGF0b3Jpb19hY2Vzc286bGlzdGFyIHJlbGF0b3Jpb191c3VhcmlvOmxpc3RhciByZWxhdG9yaW9fbG9qYV92ZW5kYTpsaXN0YXIgbG9naW46cGFpbmVsIGxvZ2luOmFwaSBsb2dpbjpjbHViZSBsb2dpbjp0b2tlbiBsb2dpbjpkaWdpbyBhZG1pbjpjaGF2ZV9wdWJsaWNhIGFkbWluOmNoYXZlX3ByaXZhZGEgY29udmVuaW9fcGFyY2Vpcm86ZGVzdGFxdWUgcGFyY2Vpcm9fcmVsYXRvcmlvOnNhbHZhciBwYXJjZWlyb19yZWxhdG9yaW86YXR1YWxpemFyIHBhcmNlaXJvX3JlbGF0b3JpbzpsaXN0YXIgcGFyY2Vpcm9fcmVsYXRvcmlvOmJ1c2NhciBwYXJjZWlyb19yZWxhdG9yaW86ZGVsZXRhciBwdWJsaWNhY2FvX25vdGljaWE6c2FsdmFyIHB1YmxpY2FjYW9fbm90aWNpYTphdHVhbGl6YXIgcHVibGljYWNhb19ub3RpY2lhOmxpc3RhciBwdWJsaWNhY2FvX25vdGljaWE6YnVzY2FyIHB1YmxpY2FjYW9fbm90aWNpYTpkZWxldGFyIGNhbXBhbmhhX3NvcnRlaW86YnVzY2FyIGNhbXBhbmhhX3NvcnRlaW86c29ydGVhciBjYW1wYW5oYV9zb3J0ZWlvOnJlc3VsdGFkbyBhcHBfYXBpOmxpc3RhciBhcHBfYXBpOmJ1c2NhciBhcHBfYXBpOnNhbHZhciBhcHBfYXBpOmF0dWFsaXphciBhcHBfYXBpOmRlbGV0YXIgYXBwX3VzdWFyaW86bGlzdGFyIGFwcF91c3VhcmlvOmJ1c2NhciBhcHBfdXN1YXJpbzpzYWx2YXIgYXBwX3VzdWFyaW86YXR1YWxpemFyIGFwcF91c3VhcmlvOmRlbGV0YXIgY29tZXJjaWFsX2VtcHJlc2E6bGlzdGFyIGNvbWVyY2lhbF9lbXByZXNhOmJ1c2NhciBjb21lcmNpYWxfZW1wcmVzYTpzYWx2YXIgY29tZXJjaWFsX2VtcHJlc2E6YXR1YWxpemFyIGNvbWVyY2lhbF9lbXByZXNhOmRlbGV0YXIgbG9nX2Vycm86bGlzdGFyIGxvZ19lcnJvOmJ1c2NhciBsb2dfZXJybzphdHVhbGl6YXIgcG9udG9fY3ZzOmxpc3RhciBwb250b19jdnM6YnVzY2FyIHBvbnRvX2N2czpzYWx2YXIgcG9udG9fY3ZzOmF0dWFsaXphciIsImd0eSI6ImNsaWVudC1jcmVkZW50aWFscyJ9.kDfcICslXAVspcHLyOLRLCXyUXg1m1UAuG5ewBay5Tw'
        ])->object()->dado->chave ?? '';
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
    public function postImagemSocial(Request $request): Response
    {

        $imagem = $this->pegarIdRedeSocial($request);
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';

        $Api->body([
            'foto_perfil' => $imagem ? $Crypt->encode($imagem) : null,
        ])->put('/usuario-cliente/' . $id);

        if ($status == 204) {
            return new Response(status: 204);
        }

        mensagemErro(
            'Erro!',
            'Ocorreu um erro ao tentar salvar as informações, por favor, tente novamente.'
        );
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
    private function vincularImagem(SocialHelper $Social, $rede): Response
    {
        var_dump('123');
        exit;
        return mensagemSucesso([
            'imagem' => ($rede == 'google') ? $Social->imagem() : ''
        ], 201);
    }
}
