<?php

namespace App\Models\Api\Saude\Convenio;

use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use Modules\EnderecoEstado;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class ListarModel extends PadraoModel
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_SAUDE_CONVENIO;

    public array|stdClass $retorno = [];
    private array $where;
    private array|stdClass $busca;

    public function __construct(
        public EnderecoEstado $EnderecoEstado,
        public ?string $enderecoCidade,
        public Pagina $pagina,
        public Quantidade $quantidade,
    )
    {
        parent::__construct();
        $this->validarEstado();
        $this->montarWhere();
        $this->buscarLista();
        $this->montarRetorno();
    }

    private function montarRetorno()
    {
        $Status = new Status();
        $retorno = [];
        $busca = $this->busca;
        foreach($busca->lista as $r) {
            $retorno[] = $this->painel ? [
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'data_criacao' => $r->data_criacao,
                'status' => $Status->indice($r->status)
            ] : [
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'imagem' => arquivoPrivado($r->arquivo_imagem),
                'tipo_loja' => 'plano-saude',
                'url' => $r->url
            ];
        }
        $busca->lista = $retorno;
        $this->retorno = $busca;
    }

    private function buscarLista()
    {
        $this->busca = $this
            ->campo(['uuid', 'titulo', 'arquivo_imagem', 'url', 'data_criacao', 'status'])
            ->where($this->where, obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();
    }

    private function montarWhere(): void
    {
        $where = [];
        if($this->clube) {
            $where = $this->whereClube();
        }
        if($this->EnderecoEstado->valido()) {
            $where[] = ['endereco_estado', 'json', $this->EnderecoEstado];
        }
        if(!empty($this->enderecoCidade) && $this->enderecoCidade !== 'outra') {
            $where[] = [
                'OR',
                ['endereco_cidade', 'chave', $this->enderecoCidade],
                ['endereco_cidade', 'null']
            ];
        }
        $this->where = $where;
    }
}
