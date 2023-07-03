<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\CryptHelper;

final class CarteirinhaModel
{
    protected string $chave;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $curl = new ApiHelper('admin:chave_publica');
        $chave = $curl->get('/admin/chave-publica')
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
        $api = new ApiHelper('carteirinha:buscar');

        $dado = $api->validar('Página não encontrada!', status: 404)
            ->get('/carteirinha/' . $id)
            ->object();

        return $this->montarRetorno($dado);
    }

    /**
     * @param $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($dado): object|array
    {
        $curl = new ApiHelper('admin:chave_privada');
        $chave = $curl->get('/admin/chave-privada')
            ->object()->dado->chave ?? '';
        $criptografa = new CryptHelper(chavePrivada: $chave);

        if ($dado->dado) {
            $r = $dado->dado[0];
            $retorno = (object)[
                'nome'            => $criptografa->decode($r->usuario->nome) ?? '',
                'matricula'       => $criptografa->decode($r->usuario->matricula) ?? '',
                'cpf'             => $criptografa->decode($r->usuario->documento) ?? '',
                'rg'              => $criptografa->decode($r->usuario->documento_rg) ?? '',
                'endereco_estado' => $r->usuario->endereco_estado ?? '',
                'texto'           => (object)[
                    'principal' => $r->texto->principal,
                    'perdido'   => $r->texto->perdido
                ],
                'empresa' => (object)[
                    'nome' => $r->empresa->nome
                ],
                'imagem' => (object)[
                    'logo'   => $r->imagem->logo,
                    'frente' => $r->imagem->frente,
                    'fundo'  => $r->imagem->fundo
                ],
                'data' => (object)[
                    'aniversario' => $r->data->aniversario ?? false,
                    'filiacao'    => $r->data->data_filiacao ?? false,
                    'emissao'     => $r->data->emissao ?? false,
                ]
            ];
        }

        return $retorno;
    }
}
