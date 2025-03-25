<?php

namespace Painel\ComercialProspeccao\Models;

use Helpers\ApiHelper;
use Helpers\CryptHelper;
use App\Classes\ComercialEmpresa\Status;
use PainelModel\Perfil\Empresa;
use PainelModel\Perfil\Equipe;

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

    public function listar($prospeccao = '', $status = Status::PROSPECCAO)
    {
        $usuario = painelPermissao('comercial_empresa_gerente', false) ? '' : sessao('USUARIO.id');
        $dado = $this->Api
            ->json([
                'pagina'            => 1,
                'quantidade'        => 1000,
                'usuario'           => $usuario,
                'prospeccao_status' => $prospeccao,
                'status'            => $status
            ])
            ->get('/comercial-empresa')
            ->array();

        if (!array_key_exists('status', $dado) || $dado['status'] != 'sucesso') {
            return [];
        }

        return $this->montarLista($dado['dado']['lista']);
    }

    private function montarLista($dado)
    {
        $retorno = [];
        $Empresa = new Empresa();
        $Equipe = new Equipe();
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'           => $r['id'],
                'titulo'       => $this->Crypt->decode($r['titulo']),
                'empresa'      => $Empresa->unico($r['empresa_id']),
                'equipe'       => $Equipe->unico($r['equipe_id']),
                'cnpj'         => strCnpj($this->Crypt->decode($r['cnpj'])),
                'data_criacao' => dataBr($r['data_criacao']),
                'status'       => $r['prospeccao_status']
            ];
        }
        return $retorno;
    }

    public function buscar(string $id)
    {
        $dado = (new ApiHelper(token: true))
            ->validar('Erro ao buscar empresa, por favor, tente novamente.')
            ->get('/comercial-empresa/' . $id)
            ->object()->dado;

        return $this->montarBusca($dado);
    }

    private function montarBusca($dado)
    {
        return (object)[
            'id'                   => $dado->id,
            'responsavel_nome'     => $this->Crypt->decode($dado->responsavel_nome),
            'responsavel_email'    => $this->Crypt->decode($dado->responsavel_email),
            'responsavel_telefone' => $this->Crypt->decode($dado->responsavel_telefone),
        ];
    }
}
