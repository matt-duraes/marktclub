<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\Analytics\Trait\WhereTrait;

final class UsuarioMaisAcessoModel extends ORM
{
    use ValidarEmpresaTrait;
    use WhereTrait;
    protected string $_tabela = TABELA_ANALYTICS_USUARIO;

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
            ->campo(['quantidade', 'usuario_nome', 'id_usuario_cliente'])
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
            if (!array_key_exists($r->id_usuario_cliente, $dado)) {
                $dado[$r->id_usuario_cliente] = object([
                    'usuario_nome' => $r->usuario_nome,
                    'quantidade' => 0,
                ]);
            }
            $dado[$r->id_usuario_cliente]->quantidade += $r->quantidade;
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
                'usuario' => $r->usuario_nome,
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
