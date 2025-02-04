<?php

namespace App\Models\Api\AlbumFoto;

use App\Classes\Geral\Status;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class FotoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_ALBUM_FOTO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param string|null $album
     * @param string|null $titulo
     * @param Data        $dataInicio
     * @param Data        $dataFinal
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly ?string $album = null,
        private readonly ?string $titulo = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A data de final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $fotos = $this
            ->campo([
                'uuid', 'titulo', 'imagem', 'status', 'data_criacao',
                'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('ordem', 'ASC')
            ->read();

        if (empty($fotos)) {
            return $this->paginacaoZero();
        }

        $fotos->lista = $this->montarRetorno($fotos->lista);
        return $fotos;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->album)) {
            $ormHelper = new OrmHelper(TABELA_ALBUM_DADO);
            $where[] = ['id_album_dado', '=', $ormHelper->pegarIdPeloUuid($this->album)];
        }

        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'like', '%' . $this->titulo . '%'];
        }

        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataInicio->date(), $this->dataFinal->date()]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', $this->dataFinal->date()];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $fotos
     *
     * @return array
     */
    private function montarRetorno(array $fotos): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($fotos as $foto) {
            $retorno[] = [
                'id'               => $foto->uuid,
                'titulo'           => $foto->titulo,
                'imagem'           => arquivoPrivado($foto->imagem),
                'status'           => $Status->indice($foto->status),
                'data_criacao'     => $foto->data_criacao,
                'data_atualizacao' => $foto->data_atualizacao
            ];
        }
        return $retorno;
    }
}
