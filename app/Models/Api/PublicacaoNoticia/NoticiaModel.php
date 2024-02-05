<?php

namespace App\Models\Api\PublicacaoNoticia;

use ORM\ORM;
use stdClass;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Order\OrderInterface;
use Status\StatusInterface;
use Modules\ModuleInterface;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\PublicacaoNoticia\Tipo;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\PublicacaoNoticia\Local;
use App\Classes\PublicacaoNoticia\Ordem;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class NoticiaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_NOTICIA;

    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private ?string $pesquisa = null,
        private Data $data_inicio_de = new Data(null),
        private Data $data_inicio_ate = new Data(null),
        private Botao $publicado = new Botao(null),
        private Local $local = new Local(null),
        private Tipo $tipo = new Tipo(null),
        private Ordem $ordem = new Ordem(null),
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
                'uuid', 'titulo_grande', 'titulo_pequeno', 'texto_grande', 'texto_pequeno',
                'data_inicio', 'data_final', 'imagem_grande', 'imagem_pequena', 'url', 'status'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
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
        $retorno = [];
        foreach ($lista as $r) {
            $titulo = $r->titulo_pequeno;
            if (empty($titulo)) {
                $titulo = strCortar($r->titulo_grande, 80);
            }

            $texto = $r->texto_pequeno;
            if (empty($texto)) {
                $texto = strCortar(strip_tags($r->texto_grande), 120);
            }
            $imagem = '';
            if (!empty($r->imagem_pequena)) {
                $imagem = $r->imagem_pequena;
            } elseif (!empty($r->imagem_grande)) {
                $imagem = $r->imagem_grande;
            }

            $statusIndice = $Status->indice($r->status);
            $publicado = new Publicado(
                new Data($r->data_inicio),
                new Data($r->data_final),
                $statusIndice == Status::ATIVO
            );

            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $titulo,
                'texto'       => $texto,
                'imagem'      => !empty($imagem) ? arquivoPrivado($imagem) : '',
                'data_inicio' => $r->data_inicio,
                'url'         => $r->url,
                'publicado'   => $publicado->indice(),
                'status'      => $statusIndice
            ];
        }
        return $retorno;
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
        if ($this->local->valido()) {
            $where[] = ['local', $this->local->numero()];
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
        if(!empty($this->pesquisa)) {
            $where[] = ['titulo_grande', 'like', '%' . $this->pesquisa . '%'];
        }
        return $where;
    }

    private function validarDado()
    {
        //
    }

    private function validarCampoModulo(string $campo, ModuleInterface|OrderInterface|StatusInterface $modulo)
    {
        if ($modulo->vazio() || $modulo->valido()) {
            return $this;
        }
        mensagemErro('Campo inválido!', 'O campo ' . $campo . ' não é válido.');
    }
}
