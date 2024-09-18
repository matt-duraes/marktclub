<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;
use System\Trait\Model\QuantidadeTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DestaqueModel extends ORM
{
    use QuantidadeTrait;
    use ValidarEmpresaTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    private int $idEmpresa;

    public function __construct(
        private Categoria $categoria = new Categoria(null),
        private ?string $subcategoria = null,
        private Quantidade $quantidade = new Quantidade(null),
        private Ordem $ordem = new Ordem('randomico'),
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function listarDados(): array
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'imagem_logo', 'desconto'])
            ->where($this->pegarWhere())
            ->limit(0, $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();
        return $this->montarRetorno($dado);
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'       => $r->uuid,
                'titulo'   => $r->titulo,
                'imagem'   => arquivoPrivado($r->imagem_logo),
                'desconto' => $r->desconto
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $idEmpresa = $this->idEmpresa;
        $where = [
            ['id_admin_empresa', 'json', $idEmpresa],
            ['destaque', 'json', $idEmpresa],
            ['tipo_loja', new TipoLoja(TipoLoja::LOJA)],
            ['status', new Status(Status::CONCLUIDO)]
        ];
        return $where;
    }
}
