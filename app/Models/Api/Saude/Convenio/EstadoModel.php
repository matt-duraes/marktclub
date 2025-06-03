<?php

namespace App\Models\Api\Saude\Convenio;

use Helpers\ListaHelper;

final class EstadoModel extends PadraoModel
{
    protected string $ormTabela = TABELA_SAUDE_CONVENIO;

    public array $retorno = [];
    private array $where;
    private array $busca;

    public function __construct()
    {
        parent::__construct();
        $this->montarWhere();
        $this->buscarLista();
        $this->montarRetorno();
        $this->ordenarRetorno('Escolha um estado');
    }

    private function montarRetorno()
    {
        $estado = [];
        foreach($this->busca as $r) {
            $estado += jsonDecode($r->endereco_estado, true);
        }
        $lista = (new ListaHelper())->estado()->r();
        foreach($lista as $ind => $val) {
            if(!in_array($ind, $estado)) {
                continue;
            }
            $this->retorno[$ind] = $val;
        }
        if(!$this->retorno) {
            return;
        }
        $this->retorno = ['' => 'Escolha um estado'] + $this->retorno;
    }

    private function buscarLista()
    {
        $this->busca = $this
            ->campo(['endereco_estado'])
            ->where($this->where)
            ->read();
    }

    private function montarWhere(): void
    {
        $this->where = $this->whereClube();
    }
}
