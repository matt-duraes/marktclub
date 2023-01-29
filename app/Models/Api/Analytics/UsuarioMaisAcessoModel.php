<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use App\Models\Api\Analytics\Trait\WhereTrait;

final class UsuarioMaisAcessoModel extends ORM
{
    use WhereTrait;
    protected string $_tabela = TABELA_ANALYTICS_USUARIO;

    public function __construct(
        protected Data $de,
        protected Data $ate,
    ) {
        parent::__construct();
    }

    public function listarDado(): array
    {
        $lista = $this
            ->campo(['quantidade', 'usuario_nome'])
            ->where($this->pegarWherePadrao())
            ->order('quantidade', 'DESC')
            ->limit(0, 20)
            ->read();

        return $this->montarDado($lista);
    }

    private function montarDado($lista)
    {
        $dado = [];
        $total = 0;
        foreach ($lista as $r) {
            $total += $r->quantidade;
        }
        foreach ($lista as $r) {
            $dado[] = [
                'usuario' => $r->usuario_nome,
                'total' => $r->quantidade,
                'porcentagem' => porcentagem($r->quantidade, $total)
            ];
        }
        return $dado;
    }
}
