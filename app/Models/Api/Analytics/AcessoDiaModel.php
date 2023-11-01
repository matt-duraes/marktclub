<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use App\Models\Api\Analytics\Trait\WhereTrait;

final class AcessoDiaModel extends ORM
{
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_ACESSO_DIA;

    public function __construct(
        protected Data $de,
        protected Data $ate,
        private array|string|null $Empresa = null
    ) {
        parent::__construct();
    }

    public function listarDado()
    {
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
                'data'  => $data,
                'total' => 0,
                'unico' => 0,
            ];
            if ($data == $ate) {
                break;
            }
        }

        $lista = $this
            ->campo(['quantidade_total', 'quantidade_unico', 'data_acesso'])
            ->where($this->pegarWherePadrao())
            ->read();

        $somas_por_data = [];

        foreach ($lista as $objeto) {
            $data_acesso = $objeto->data_acesso;
            if (!isset($somas_por_data[$data_acesso])) {
                $somas_por_data[$data_acesso] = [
                    'data_acesso'      => $data_acesso,
                    'quantidade_total' => $objeto->quantidade_total,
                    'quantidade_unico' => $objeto->quantidade_unico
                ];
            } else {
                $somas_por_data[$data_acesso]['quantidade_total'] += $objeto->quantidade_total;
                $somas_por_data[$data_acesso]['quantidade_unico'] += $objeto->quantidade_unico;
            }
        }

        $lista = array_values($somas_por_data);
        foreach ($lista as $r) {
            $data = dataBr($r['data_acesso']);
            $dado[$data]['data'] = $data;
            $dado[$data]['unico'] = $r['quantidade_unico'];
            $dado[$data]['total'] = $r['quantidade_total'];
        }

        return array_values($dado);
    }
}
