<?php

namespace App\Models\Api\Saude\Convenio;

use ORM\ORM;
use Modules\EnderecoEstado;
use App\Classes\Geral\Status;
use App\Models\Api\Auth\Token\TokenHelper;

final class ListarModel extends PadraoModel
{
    protected string $ormTabela = TABELA_SAUDE_CONVENIO;

    public array $retorno = [];
    private array $where;
    private array $busca;

    public function __construct(
        public EnderecoEstado $EnderecoEstado,
        public ?string $enderecoCidade
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
        foreach($this->busca as $r) {
            $this->retorno[] = [
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'arquivo_imagem' => arquivoPrivado($r->arquivo_imagem),
                'url' => $r->url,
                'status' => $Status->indice($r->status)
            ];
        }
    }

    private function buscarLista()
    {
        $this->busca = $this
            ->campo(['uuid', 'titulo', 'arquivo_imagem', 'url', 'status'])
            ->where($this->where)
            ->read();
    }

    private function montarWhere(): void
    {
        $where = [];
        if($this->eClube()) {
            $where = $this->whereClube();
        }
        if($this->EnderecoEstado->valido()) {
            $where[] = ['endereco_estado', 'json', $this->EnderecoEstado];
        }
        if(empty($this->enderecoCidade) || $this->enderecoCidade == 'outra') {
            $where[] = ['endereco_cidade', 'null'];
        } elseif($this->enderecoCidade) {
            $where[] = [
                'OR',
                ['endereco_cidade', 'chave', $this->enderecoCidade],
                ['endereco_cidade', 'null']
            ];
        }
        $this->where = $where;
    }
}
