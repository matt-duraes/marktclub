<?php

namespace App\Models\Api\ParceiroRelatorio;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\ParceiroRelatorio\Ordem;

final class RelatorioModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_ANALYTICS_LOJA_VENDA;
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
            ->order(new Ordem($this->request->ordem))
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('id', 'id_parceiro_loja')
            ->campo(['titulo'], 'parceiro')
            ->tabela(TABELA_COMERCIAL_EMPRESA)
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
                'id'             => $r->uuid,
                'parceiro'       => $r->parceiro_titulo,
                'empresa'        => $r->empresa_nome_fantasia,
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
        $where = [];

        $de = $this->converterData($this->request->data_relatorio_de);
        $ate = $this->converterData($this->request->data_relatorio_ate);

        if (validarDate($de) && validarDate($ate)) {
            $where[] = ['data_relatorio', 'between', [$de, $ate . ' 23:59:59']];
        } elseif (validarDate($de)) {
            $where[] = ['data_relatorio', '>=', $de];
        } elseif (validarDate($ate)) {
            $where[] = ['data_relatorio', '<=', $ate];
        }
        return $where;
    }

    private function converterData($data)
    {
        if (empty($data)) {
            return '';
        }

        $data = explode('-', $data);
        if (count($data) != 3) {
            return $data;
        }

        return $data[0] . '-' . $data[1] . '-01';
    }
}
