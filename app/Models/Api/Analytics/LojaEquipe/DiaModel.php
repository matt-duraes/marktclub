<?php

namespace App\Models\Api\Analytics\LojaEquipe;

use ORM\ORM;
use Modules\Data;
use Helpers\OrmHelper;

final class DiaModel extends ORM
{
    protected string $ormTabela = TABELA_ANALYTICS_LOJA_EQUIPE;
    public array $retorno = [];
    private array $dado = [];

    public function __construct(
        protected Data $de,
        protected Data $ate,
        private string $equipe
    ) {
        parent::__construct();
        $this->buscarDado();
        $this->montarDado();
    }

    private function retornoPadrao()
    {
        return (object)[
            'prospeccao' => [
                'numero'      => 0,
                'relacao'     => 0
            ],
            'problema' => [
                'numero'      => 0,
                'relacao'     => 0
            ],
            'cancelado' => [
                'numero'      => 0,
                'relacao'     => 0
            ],
            'sem_interesse' => [
                'numero'      => 0,
                'relacao'     => 0
            ],
            'concluido' => [
                'numero'      => 0,
                'relacao'     => 0
            ],
        ];
    }

    private function buscarDado()
    {
        $this->dado = $this->where($this->pegarWhere())->order('data_acesso', 'ASC')->read();
    }

    private function montarDado()
    {
        $retorno = [];
        foreach ($this->dado as $r) {
            if (!array_key_exists($r->data_acesso, $retorno)) {
                $retorno[$r->data_acesso] = $this->retornoPadrao();
            }
            // $retorno = []
        }
    }

    private function pegarWhere()
    {
        $where = [['data_acesso', 'between', [$this->de->banco(), $this->ate->banco()]]];
        $equipe = '';
        if (!empty($this->equipe)) {
            $equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($this->equipe);
        }
        if (!empty($equipe)) {
            $where[] = ['id_usuario_equipe', $equipe];
        }
        return $where;
    }
}
