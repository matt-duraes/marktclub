<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class PaginaMaisAcessadaModel extends ORM
{
    use ValidarEmpresaTrait;
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_PAGINA;

    public function __construct(
        protected Data $de,
        protected Data $ate,
        private ?EmpresaEntity $Empresa = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function listarDado(): array
    {
        $lista = $this
            ->campo(['quantidade', 'url'])
            ->where($this->pegarWherePadrao())
            ->order('quantidade', 'DESC')
            ->read();

        return $this->montarDado($lista);
    }

    private function montarDado($lista)
    {
        $dado = [];
        $total = 0;
        foreach ($lista as $r) {
            $total += $r->quantidade;
            if (!array_key_exists($r->url, $dado)) {
                $dado[$r->url] = object([
                    'url' => $r->url,
                    'quantidade' => 0,
                ]);
            }
            $dado[$r->url]->quantidade += $r->quantidade;
        }

        usort($dado, function ($a, $b) {
            $a = $a->quantidade;
            $b = $b->quantidade;
            if ($a == $b) {
                return 0;
            }
            return $a < $b ? 1 : -1;
        });

        $retorno = [];
        $i = 1;
        foreach ($dado as $r) {
            $retorno[] = [
                'pagina' => empty($r->url) ? '/' : $r->url,
                'total' => $r->quantidade,
                'porcentagem' => porcentagem($r->quantidade, $total)
            ];
            if ($i >= 20) {
                break;
            }
            $i++;
        }
        return $retorno;
    }
}
