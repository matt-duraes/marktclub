<?php

namespace App\Models\Api\Saude\Convenio;

use ORM\ORM;
use Where\Where;
use App\Classes\Geral\Status;
use App\Models\Api\Auth\Token\TokenHelper;

final class ListarModel extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_CONVENIO;

    public array $retorno = [];
    private array $where;
    private array $busca;

    public function __construct(
        public ?string $enderecoEstado,
        public ?string $enderecoCidade
    )
    {
        parent::__construct();
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
        $Token = new TokenHelper();
        $where = [];
        if($Token->eClube()) {
            $where = [
                ['id_admin_empresa', 'json', $Token->pegarEmpresa(erro: true)],
                ['status', 1]
            ];
        }
        if($this->enderecoEstado) {
            $where[] = ['endereco_estado', 'json', $this->enderecoEstado];
        }
        if($this->enderecoCidade) {
            $where[] = [
                'OR',
                ['endereco_cidade', 'json', $this->enderecoCidade],
                ['endereco_cidade', 'null']
            ];
        }
        $this->where = $where;
    }
}
