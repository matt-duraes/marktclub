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
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
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
                'data_inicio', 'imagem_grande', 'imagem_pequena', 'url', 'status'
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
            } elseif (!empty($r->imagem_pequena)) {
                $imagem = $r->imagem_grande;
            }

            $retorno[] = [
                'id'                     => $r->uuid,
                'titulo'                 => $titulo,
                'texto'                  => $texto,
                'imagem'                 => $imagem,
                'data_publicacao_inicio' => $r->data_publicacao_inicio,
                'url'                    => $r->url,
                'status'                 => $Status->indice($r->status)
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
        if ($this->data_inicio_de->eDate() && $this->data_inicio_ate->eDate()) {
            $where[] = [
                'data_inicio',
                'between',
                [$this->data_inicio_de->date(), $this->data_inicio_ate->date()]
            ];
        } elseif ($this->data_inicio_de->eDate()) {
            $where[] = ['data_inicio', '>=', $this->data_inicio_de->date()];
        } elseif ($this->data_inicio_ate->valido()) {
            $where[] = ['data_inicio', '<=', $this->data_inicio_ate->date()];
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
