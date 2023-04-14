<?php

namespace Painel\ComercialProspeccao\Models;

use Helpers\ApiHelper;
use Helpers\CryptHelper;
use App\Classes\ComercialEmpresa\Status;

final class ProspeccaoModel
{
    private CryptHelper $Crypt;
    private ApiHelper $Api;

    public function __construct()
    {
        $this->Api = new ApiHelper(token: true);
        $chave = $this->Api->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $this->Crypt = new CryptHelper(chavePrivada: $chave);
    }
    public function buscarProspeccao($prospeccao)
    {
        $dado = $this->Api
            ->json([
                'pagina' => 1,
                'quantidade' => 50,
                'prospeccao_status' => $prospeccao,
                'status' => Status::PROSPECCAO
            ])
            ->get('/comercial-empresa')
            ->array();

        if (!array_key_exists('status', $dado) || $dado['status'] != 'sucesso') {
            return [];
        }

        return $this->montarDado($dado['dado']['lista']);
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id' => $r['id'],
                'usuario' => (object)[
                    'id' => $r['usuario']['id'] ?? '',
                    'nome' => $this->Crypt->decode($r['usuario']['nome']),
                    'imagem' => $this->Crypt->decode($r['usuario']['imagem'])
                ],
                'titulo' => $this->Crypt->decode($r['titulo']),
                'cnpj' => strCnpj($this->Crypt->decode($r['cnpj'])),
                'data_criacao' => dataBr($r['data_criacao']),
                'status' => $r['prospeccao_status']
            ];
        }
        return $retorno;
    }
}
