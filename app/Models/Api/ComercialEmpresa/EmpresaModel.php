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

final class EmpresaModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $_tabela = TABELA_COMERCIAL_EMPRESA;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'titulo', 'razao_social', 'cnpj', 'data_criacao', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista ?? []);
        return $dado;
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        $Status = new Status();

        foreach ($lista as $r) {
            $titulo = !empty($r->titulo) ? $r->titulo : $r->razao_social;
            $retorno[] = [
                'id' => $r->cod,
                'titulo' => $titulo,
                'cnpj' => $r->cnpj,
                'data_criacao' => $r->data_criacao,
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
        return [];
    }
    private function validarRequest()
    {
    }
}
