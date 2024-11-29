<?php

namespace App\Models\Api\ParceiroLoja;

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Quantidade;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\QuantidadeTrait;

class DestaqueModel extends ORM
{
    use QuantidadeTrait;
    use ValidarEmpresaTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    private int $idEmpresa;

    /**
     * @param Quantidade  $quantidade
     * @param Categoria   $categoria
     * @param string|null $subcategoria
     * @param Ordem       $ordem
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Categoria $categoria = new Categoria(),
        private readonly ?string $subcategoria = null,
        private readonly Ordem $ordem = new Ordem('randomico')
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function listarDados(): array
    {
        $destaques = $this
            ->campo([
                'uuid', 'titulo', 'imagem_logo', 'desconto'
            ])
            ->where($this->pegarWhere())
            ->limit(0, $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();
        return $this->montarRetorno($destaques);
    }

    /**
     * @return array[]
     */
    private function pegarWhere(): array
    {
        $idEmpresa = $this->idEmpresa;
        $where = [
            ['id_admin_empresa', 'json', $idEmpresa],
            ['destaque', 'json', $idEmpresa],
            ['tipo_loja', new TipoLoja(TipoLoja::LOJA)],
            ['status', new Status(Status::CONCLUIDO)]
        ];
        if ($this->categoria->valido()) {
            $where[] = ['categoria_lista', 'json', $this->categoria->numero()];
        }
        if (!empty($this->subcategoria)) {
            $subcategoria = $this->pegarSubcategoria($this->subcategoria);
            if (!empty($subcategoria)) {
                $where[] = ['subcategoria_lista', 'json', $subcategoria];
            }
        }
        return $where;
    }

    /**
     * @param string $subcategoria
     *
     * @return string|int|null
     */
    private function pegarSubcategoria(string $subcategoria): string|int|null
    {
        $ormHelper = new OrmHelper(TABELA_PARCEIRO_SUBCATEGORIA);
        return $ormHelper->pegarCampoPor('id', ['titulo', 'LIKE', "%$subcategoria%"]);
    }

    /**
     * @param array $destaques
     *
     * @return array
     */
    private function montarRetorno(array $destaques): array
    {
        $retorno = [];
        foreach ($destaques as $destaque) {
            $retorno[] = [
                'id'       => $destaque->uuid,
                'titulo'   => $destaque->titulo,
                'imagem'   => arquivoPrivado($destaque->imagem_logo),
                'desconto' => $destaque->desconto
            ];
        }
        return $retorno;
    }
}
