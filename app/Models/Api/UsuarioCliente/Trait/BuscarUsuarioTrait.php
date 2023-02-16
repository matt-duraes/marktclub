<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use stdClass;
use System\Trait\Model\PaginaTrait;
use App\Classes\UsuarioCliente\Ordem;
use System\Trait\Model\QuantidadeTrait;

trait BuscarUsuarioTrait
{
    use WhereTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    /**
     * Buscar o usuário
     *
     * @param   array           $campo      Campo que deseja buscar do usuário
     * @param   bool            $paginacao  Se vai ter paginação
     * @return  stdClass|array              stdClass se tiver paginacao ou array quando não tiver paginacao
     */
    private function buscarUsuario(array $campo, bool $paginacao): stdClass|array
    {
        $request = $this->request;

        $ordem = new Ordem($request->chave('ordem', ''));
        $where = $this->pegarWhere();

        $query = $this->campo($campo)->where($where)->order($ordem);

        if ($paginacao) {
            $query->pagina($this->pegarPagina(), $this->pegarQuantidade());
        }

        $pagamento = $request->pagamento;
        if ($pagamento == 'sim') {
            $query
                ->tabela(TABELA_USUARIO_PAGAMENTO)
                ->join('id_usuario_cliente', 'id')
                ->where(['status', 1])
                ->group('id_usuario_cliente');
        }
        $query
            ->tabela(TABELA_EMPRESA_NOVO)
            ->join('id', 'empresa')
            ->campo(['nome_fantasia'], 'empresa');

        return $query->read();
    }
}
