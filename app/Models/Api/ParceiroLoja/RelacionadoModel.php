<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
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
        $this->parceiro = $this->campo(['categoria_principal', 'tipo_loja'])->where(['uuid', $id])->primeiro();
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
            ['id_admin_empresa', 'json', $this->idEmpresa],
            ['status', $status->numero()]
        ];

        $parceiro = $this->parceiro;
        if (empty($parceiro)) {
            return $where;
        }

        $tipo = new TipoLoja($parceiro->tipo_loja);
        $where[] = ['tipo_loja', $tipo->numero()];
        if ($tipo->indice() != TipoLoja::LOJA) {
            return $where;
        }
        $where[] = ['categoria_principal', $parceiro->categoria_principal];

        return $where;
    }
}
