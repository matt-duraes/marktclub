<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\ParceiroLoja\Trait\ListarCampoTrait;
use App\Models\Api\ParceiroLoja\Trait\MontarRetornoTrait;

final class RelacionadoModel extends ORM
{
    use ListarCampoTrait;
    use MontarRetornoTrait;
    use ValidarEmpresaTrait;

    private int $idEmpresa;
    private stdClass|array $parceiro;
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    public function __construct(string $id)
    {
        parent::__construct();
        $this->validarEmpresa();
        $this->parceiro = $this->campo(['categoria_principal', 'tipo'])->where(['uuid', $id])->primeiro();
    }

    public function listarDados(): array
    {
        $lista = $this
            ->campo($this->pegarCampo())
            ->where($this->pegarWhere())
            ->limit(0, 3)
            ->order('rand')
            ->read();
        return $this->montarRetorno($lista);
    }

    private function pegarWhere()
    {
        $status = new Status(Status::CONCLUIDO);
        $where = [
            ['empresa', 'LIKE', '%"' . $this->idEmpresa . '"%'],
            ['status', $status->numero()]
        ];

        $parceiro = $this->parceiro;
        if (empty($parceiro)) {
            return $where;
        }

        $tipo = new Tipo($parceiro->tipo);
        $where[] = ['tipo', $tipo->numero()];
        if ($tipo->indice() != Tipo::LOJA) {
            return $where;
        }
        $where[] = ['categoria_principal', $parceiro->categoria_principal];

        return $where;
    }
}
