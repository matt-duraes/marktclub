<?php

namespace App\Models\Api\PublicacaoNoticia;

use ORM\ORM;
use stdClass;
use Http\Request;
use Modules\Data;
use Order\OrderInterface;
use Status\StatusInterface;
use Modules\ModuleInterface;
use System\Trait\Model\OrdemTrait;
use App\Classes\StatusGeral\Status;
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

    protected string $_tabela = TABELA_PUBLICACAO_NOTICIA;

    private Status $status;
    private Data $dataPublicacaoDe;
    private Data $dataPublicacaoAte;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo_grande', 'titulo_pequeno', 'texto_grande', 'texto_pequeno',
                'data_publicacao_inicio', 'imagem_grande', 'imagem_pequena', 'url', 'status'
            ])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montardado($dado->lista);
        return $dado;
    }

    private function montardado($lista)
    {
        if (!$lista) {
            return [];
        }

        $Status = new Status;
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
            } else if (!empty($r->imagem_pequena)) {
                $imagem = $r->imagem_grande;
            }

            $retorno[] = [
                'id' => $r->uuid,
                'titulo' => $titulo,
                'texto' => $texto,
                'imagem' => $imagem,
                'data_publicacao_inicio' => $r->data_publicacao_inicio,
                'url' => $r->url,
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $where = $this->_wherePadrao;
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        if ($this->dataPublicacaoDe->valido() && $this->dataPublicacaoAte->valido()) {
            $where[] = [
                'data_publicacao_inicio',
                'between',
                [$this->dataPublicacaoDe->date(), $this->dataPublicacaoAte->date()]
            ];
        } else if ($this->dataPublicacaoDe->valido()) {
            $where[] = ['data_publicacao_inicio', '>=', $this->dataPublicacaoDe->date()];
        } else if ($this->dataPublicacaoAte->valido()) {
            $where[] = ['data_publicacao_inicio', '<=', $this->dataPublicacaoAte->date()];
        }
        return $where;
    }
    private function validarRequest()
    {
        $this->status = new Status($this->request->status);
        $this->dataPublicacaoDe = new Data($this->request->data_publicacao_de);
        $this->dataPublicacaoAte = new Data($this->request->data_publicacao_ate);
        $ordem = new Ordem($this->request->ordem);

        $this
            ->validarCampoModulo('ordem', $ordem)
            ->validarCampoModulo('status', $this->status)
            ->validarCampoModulo('data de publicação inicial', $this->dataPublicacaoDe)
            ->validarCampoModulo('data de publicação final', $this->dataPublicacaoAte);
    }
    private function validarCampoModulo(string $campo, ModuleInterface|OrderInterface|StatusInterface $modulo)
    {
        if ($modulo->vazio() || $modulo->valido()) {
            return $this;
        }
        mensagemErro('Campo inválido!', 'O campo ' . $campo . ' não é válido.');
    }
}
