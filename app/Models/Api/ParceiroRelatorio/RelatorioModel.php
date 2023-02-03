<?php

namespace App\Models\Api\ParceiroRelatorio;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class RelatorioModel extends ORM
{
    protected string $_tabela = TABELA_ANALYTICS_LOJA_VENDA;

    use PaginaTrait;
    use QuantidadeTrait;

    private int $idEmpresa;
    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->idEmpresa = TOKEN['empresa']->get('id');
    }

    public function listarDado(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'data_relatorio'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->tabela(TABELA_PARCEIRO_NOVO)
            ->join('id', 'id_parceiro_loja')
            ->campo(['titulo'], 'parceiro')
            ->tabela(TABELA_EMPRESA_NOVO)
            ->join('id', 'id_admin_empresa')
            ->campo(['nome_fantasia'], 'empresa')
            ->read();

        if (object_key_exists('lista', $dado)) {
            $dado->lista = $this->montarDado($dado->lista);
        }

        return $dado;
    }
    private function montarDado($dado): array
    {
        if (empty($dado)) {
            return [];
        }

        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'parceiro' => $r->parceiro_titulo,
                'empresa' => $r->empresa_nome_fantasia,
                'data_relatorio' => $r->data_relatorio
            ];
        }
        return $retorno;
    }

    private function validarRequest()
    {
    }

    private function pegarWhere(): array
    {
        return [
            ['id_admin_empresa', $this->idEmpresa]
        ];
    }
}
