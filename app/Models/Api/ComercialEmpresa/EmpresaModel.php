<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\ComercialEmpresa\Ordem;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\ComercialEmpresa\Helper;
use App\Classes\ComercialEmpresa\Status;
use System\Interface\ModelListarInterface;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;

final class EmpresaModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'titulo', 'razao_social', 'cnpj', 'data_criacao', 'prospeccao_status', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista ?? []);
        return $dado;
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        $Status = new Status();
        $ProspeccaoStatus = new ProspeccaoStatus();

        foreach ($lista as $r) {
            $titulo = !empty($r->titulo) ? $r->titulo : $r->razao_social;
            $retorno[] = [
                'id' => $r->cod,
                'titulo' => $titulo,
                'cnpj' => $r->cnpj,
                'data_criacao' => $r->data_criacao,
                'prospeccao_status' => $ProspeccaoStatus->indice($r->prospeccao_status),
                'status' => $Status->indice($r->status)
            ];
        }

        return criptografarDado(
            dado: $retorno,
            criptografia: Helper::CRIPTOGRAFAR,
            lista: true
        );
    }

    private function pegarWhere()
    {
        $where = [];
        $status = new Status($this->request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }
        $prospeccaoStatus = new ProspeccaoStatus($this->request->prospeccao_status);
        if ($prospeccaoStatus->valido()) {
            $where[] = ['prospeccao_status', $prospeccaoStatus->numero()];
        }
        return $where;
    }
    private function validarRequest()
    {
        $status = new Status($this->request->status);
        if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Campo inválido!', 'O status enviado não é válido.');
        }
        $prospeccaoStatus = new ProspeccaoStatus($this->request->prospeccao_status);
        if (!$prospeccaoStatus->vazio() && !$prospeccaoStatus->valido()) {
            mensagemErro('Campo inválido!', 'O status da prospecção enviada não é válida.');
        }
    }
}
