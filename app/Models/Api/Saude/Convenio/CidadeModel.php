<?php

namespace App\Models\Api\Saude\Convenio;

use stdClass;
use Helpers\ListaHelper;
use Modules\EnderecoEstado;

final class CidadeModel extends PadraoModel
{
    protected string $ormTabela = TABELA_SAUDE_CONVENIO;

    public array|stdClass $retorno = [];
    private array $where;
    private array $busca;
    private bool $outra = false;

    public function __construct(
        public EnderecoEstado $EnderecoEstado
    )
    {
        parent::__construct();
        $this->validarEstado();
        $this->montarWhere();
        $this->buscarLista();
        $this->montarRetorno();
        $this->ordenarRetorno('Escolha uma cidade');
        $this->verificarSeOutra();
    }

    private function verificarSeOutra()
    {
        if(!$this->retorno || !$this->outra) {
            return;
        }
        $this->retorno['outra'] = 'Outras Cidades';
    }

    private function montarRetorno()
    {
        foreach($this->busca as $r) {
            if(is_null($r->endereco_cidade)) {
                $this->outra = true;
                continue;
            }
            $valor = jsonDecode($r->endereco_cidade, true);
            if(!is_array($valor)) {
                continue;
            }
            $this->retorno += $valor;
        }
    }

    private function buscarLista()
    {
        $this->busca = $this
            ->campo(['endereco_cidade'])
            ->where($this->where)
            ->read();
    }

    private function montarWhere(): void
    {
        $where = $this->whereClube();
        $where[] = ['endereco_estado', 'json', $this->EnderecoEstado];
        $this->where = $where;
    }
}
