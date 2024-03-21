<?php

namespace App\Models\Api\Solicitacao\Link;

use ORM\ORM;

final class LimiteModel extends ORM
{
    protected string $ormTabela = TABELA_SOLICITACAO_LINK;
    public int $saldo = 0;
    public int $usado = 0;
    public array $lista = [];

    public function __construct(
        private HashModel $Hash
    ) {
        parent::__construct();
        $this->pegarSaldo();
        $this->pegarUsado();
    }

    private function pegarSaldo()
    {
        $this->saldo = $this->contar([
            ['status', 1],
            ['id_admin_empresa', 'null'],
            ['id_usuario_cliente', 'null'],
            ['id_parceiro_loja', $this->Hash->parceiro->id],
            ['data_vencimento', '>=', hoje()]
        ]);
    }

    private function pegarUsado()
    {
        $dado = $this
            ->campo(['uuid', 'data_emissao', 'link'])
            ->where([
                ['status', 2],
                ['id_usuario_cliente', $this->Hash->usuario],
                ['id_parceiro_loja', $this->Hash->parceiro->id],
                ['data_emissao', 'between', [
                    dataPrimeiroDiaMes(hoje()) . ' 00:00:00', dataUltimoDiaMes(hoje()) . ' 23:59:59'
                ]],
                ['data_vencimento', '>=', hoje()]
            ])
            ->read();

        $this->usado = count($dado);
        $this->lista = $this->montar($dado);
    }

    private function montar($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'   => $r->uuid,
                'data' => dataHoraBr($r->data_emissao),
                'link' => $r->link
            ];
        }
        return $retorno;
    }
}
