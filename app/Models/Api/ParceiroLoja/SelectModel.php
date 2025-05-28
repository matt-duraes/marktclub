<?php

namespace App\Models\Api\ParceiroLoja;

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use Erro\Excecao;
use ORM\ORM;

class SelectModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    /**
     * @param string|null       $titulo
     * @param TipoLoja          $tipoLoja
     * @param array|string|null $status
     */
    public function __construct(
        private readonly ?string $titulo = null,
        private readonly TipoLoja $tipoLoja = new TipoLoja(),
        private readonly array|string|null $status = null
    ) {
        parent::__construct();
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function listarDados(): array
    {
        $parceiros = $this
            ->campo(['uuid', 'titulo', 'titulo_interno'])
            ->where($this->pegarWhere())
            ->order('titulo', 'ASC')
            ->read();
        return $this->montarDado($parceiros);
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = [];
        if ($this->tipoLoja->valido()) {
            $where[] = ['tipo_loja', $this->tipoLoja->numero()];
        }
        if (!empty($this->status) && is_array($this->status)) {
            $status = [];
            foreach ($this->status as $item) {
                $Status = new Status($item);
                if ($Status->valido()) {
                    $status[] = $Status->numero();
                }
            }
            $where[] = ['status', 'in', $status];
        } elseif (!empty($this->status) && is_string($this->status)) {
            $Status = new Status($this->status);
            if ($Status->valido()) {
                $where[] = ['status', $Status->numero()];
            }
        } else {
            $where[] = ['status', 'in', [4, 5]];
        }
        return $where;
    }

    /**
     * @param array $parceiros
     *
     * @return array
     */
    private function montarDado(array $parceiros): array
    {
        $retorno = [];
        if (!empty($this->titulo)) {
            $retorno[''] = $this->titulo;
        }
        foreach ($parceiros as $parceiro) {
            $retorno[$parceiro->uuid] = !empty($parceiro->titulo_interno) ? $parceiro->titulo_interno : $parceiro->titulo;
        }
        return $retorno;
    }
}
