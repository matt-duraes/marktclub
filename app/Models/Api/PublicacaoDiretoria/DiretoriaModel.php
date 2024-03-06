<?php

namespace App\Models\Api\PublicacaoDiretoria;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\PublicacaoDiretoria\Grupo;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DiretoriaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_DIRETORIA;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private ?string $pesquisa = null,
        private Status $status = new Status(null)
    ) {
        parent::__construct();
        $this->validarDado();
        $this->validarEmpresa();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'nome', 'texto', 'grupo', 'cargo', 'imagem', 'data_criacao', 'status'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('ordem', 'ASC')
            ->read();

        $dado->lista = $this->montardado($dado->lista);
        return $dado;
    }

    private function montardado($lista)
    {
        if (!$lista) {
            return [];
        }

        $Status = new Status();
        $Grupo = new Grupo();
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'nome'         => $r->nome,
                'texto'        => $r->texto,
                'cargo'        => $r->cargo,
                'grupo'        => $Grupo->indice($r->grupo),
                'data_criacao' => $r->data_criacao,
                'imagem'       => !empty($r->imagem) ? arquivoPrivado($r->imagem) : '',
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $where = $this->ormWherePadrao;

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function validarDado()
    {
        //
    }
}
