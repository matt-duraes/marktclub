<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use stdClass;
use App\Classes\UsuarioCliente\Ordem;

trait BuscarUsuarioTrait
{
    use WhereTrait;

    private function buscarUsuario(array $campo, bool $paginacao): stdClass|array
    {
        $request = $this->request;

        $ordem = new Ordem($request->chave('ordem', ''));
        $where = $this->pegarWhere();

        $query = $this->campo($campo)->where($where)->order($ordem);

        if ($paginacao) {
            $pagina = $this->pegarPagina();
            $query->pagina($pagina, 50);
        }

        $pagamento = $request->pagamento;
        if ($pagamento == 'sim') {
            $query
                ->tabela(TABELA_USUARIO_PAGAMENTO)->join('id_usuario_cliente', 'id')
                ->where(['status', 1])->group('id_usuario_cliente');
        }

        return $query->read();
    }
}
