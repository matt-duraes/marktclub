<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use App\Models\Api\Analytics\Trait\WhereTrait;

final class AcessoDiaModel extends ORM
{
    use WhereTrait;
    protected string $_tabela = TABELA_ANALYTICS_ACESSO_DIA;

    public function __construct(
        protected Data $de,
        protected Data $ate,
    ) {
        parent::__construct();
    }

    public function listarDado()
    {
        $where = $this->pegarWherePadrao();
        $de = $this->de->data();
        $ate = $this->ate->data();

        $dado = [
            $de => [
                'total' => 0,
                'unico' => 0
            ]
        ];

        for ($i = 0; $i < 367; $i++) {
            $data = dataAdicionar($de, $i, 'dia', 'd/m/Y');
            $dado[$data] = [
                'data' => $data,
                'total' => 0,
                'unico' => 0,
            ];
            if ($data == $ate) {
                break;
            }
        }

        $lista = $this
            ->campo(['quantidade_total', 'quantidade_unico', 'data_acesso'])
            ->where($where)
            ->read();

        foreach ($lista as $r) {
            $data = dataBr($r->data_acesso);
            $dado[$data]['unico'] = $r->quantidade_unico;
            $dado[$data]['total'] = $r->quantidade_total;
        }

        return array_values($dado);
    }
}
