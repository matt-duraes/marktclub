<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Classes\ParceiroLoja\Tipo;
use System\Trait\Model\OrdemTrait;
use App\Classes\ParceiroLoja\Ordem;
use System\Trait\Model\PaginaTrait;
use App\Classes\ParceiroLoja\Status;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\ParceiroLoja\Estabelecimento;
use App\Models\Api\Demanda\Trait\EmpresaTrait;

class LojaModel extends ORM
{
    use EmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    public function __construct(
        private ?Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    public function pegarRetorno(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'titulo', 'url', 'desconto', 'imagem', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->where($this->pegarWhere())
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno($lista): array
    {
        $retorno = [];
        $Status = new Status();

        foreach ($lista as $r) {
            $retorno[] = [
                'id'       => $r->cod,
                'titulo'   => $r->titulo,
                'desconto' => $r->desconto,
                'imagem'   => LINK_ARQUIVO . '/parceiro/' . $r->imagem,
                'url'      => $r->url,
                'status'   => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    protected function validarRequest(): void
    {
        $tipo = new Tipo($this->request->tipo);
        if (!$tipo->vazio() && !$tipo->valido()) {
            mensagemErro('Erro!', 'O campo tipo não é um valor válido.');
        }
        $estabelecimento = new Estabelecimento($this->request->estabelecimento);
        if (!$estabelecimento->vazio() && !$estabelecimento->valido()) {
            mensagemErro('Erro!', 'O campo estabelecimento não é um valor válido.');
        }
        $status = new Status($this->request->status);
        if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Erro!', 'O campo status não é um valor válido.');
        }
    }

    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao ? [$this->ormWherePadrao] : [];
        $tipo = new Tipo($this->request->tipo);
        if ($tipo->valido()) {
            $where[] = ['tipo', $tipo->numero()];
        }

        $estabelecimento = new Estabelecimento($this->request->estabelecimento);
        if ($estabelecimento->valido()) {
            $where[] = ['estabelecimento', $estabelecimento->numero()];
        }

        $status = new Status($this->request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }
        return $where;
    }
}
