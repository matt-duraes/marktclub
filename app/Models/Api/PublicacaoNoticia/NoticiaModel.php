<?php

namespace App\Models\Api\PublicacaoNoticia;

use stdClass;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Order\OrderInterface;
use Status\StatusInterface;
use Modules\ModuleInterface;
use App\Classes\Geral\Status;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\PublicacaoNoticia\Tipo;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\PublicacaoNoticia\Ordem;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class NoticiaModel extends GeralModel implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private ?string $pesquisa = null,
        private Data $data_inicio_de = new Data(null),
        private Data $data_inicio_ate = new Data(null),
        private Botao $publicado = new Botao(null),
        private Botao $home = new Botao(null),
        private Tipo $tipo = new Tipo(null),
        private Ordem $ordem = new Ordem(null),
        private Status $status = new Status(null),
        private Botao $restrita = new Botao(null),
        private Botao $site = new Botao(null),
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(self::CAMPO)
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montardado($dado->lista);
        return $dado;
    }

    private function pegarWhere()
    {
        $where = $this->ormWherePadrao;
        $publicado = $this->publicado->valido();

        if ($this->status->valido() && !$publicado) {
            $where[] = ['status', $this->status->numero()];
        }
        if ($this->data_inicio_de->eDate() && $this->data_inicio_ate->eDate() && !$publicado) {
            $where[] = [
                'data_inicio',
                'between',
                [$this->data_inicio_de->date(), $this->data_inicio_ate->date() . ' 23:59:59']
            ];
        } elseif ($this->data_inicio_de->eDate() && !$publicado) {
            $where[] = ['data_inicio', '>=', $this->data_inicio_de->date()];
        } elseif ($this->data_inicio_ate->valido() && !$publicado) {
            $where[] = ['data_inicio', '<=', $this->data_inicio_ate->date() . ' 23:59:59'];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }
        if ($this->home->valido()) {
            $where[] = ['home', $this->home->numero()];
        }
        if ($this->restrita->valido()) {
            $where[] = ['permissao_restrita', $this->restrita->numero()];
        }
        if ($this->site->valido()) {
            $where[] = ['permissao_site', $this->site->numero()];
        }

        if ($publicado && $this->publicado->valor() == Botao::SIM) {
            $where[] = [
                [
                    'OR',
                    ['data_inicio', 'null'],
                    ['data_inicio', ''],
                    ['data_inicio', '<=', hoje() . ' 23:59:59'],
                ],
                [
                    'OR',
                    ['data_final', 'null'],
                    ['data_final', ''],
                    ['data_final', '>=', hoje()],
                ],
                ['status', (new Status(Status::ATIVO))->numero()]
            ];
        } elseif ($publicado && $this->publicado->valor() == Botao::NAO) {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', (new Status(Status::ATIVO))->numero()]
            ];
        }
        if (!empty($this->pesquisa)) {
            $where[] = ['titulo_grande', 'like', '%' . $this->pesquisa . '%'];
        }
        return $where;
    }

    private function validarCampoModulo(string $campo, ModuleInterface|OrderInterface|StatusInterface $modulo)
    {
        if ($modulo->vazio() || $modulo->valido()) {
            return $this;
        }
        mensagemErro('Campo inválido!', 'O campo ' . $campo . ' não é válido.');
    }
}
