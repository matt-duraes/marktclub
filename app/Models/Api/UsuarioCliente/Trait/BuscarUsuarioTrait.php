<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use App\Classes\UsuarioCliente\Ordem;
use Helpers\OrmHelper;
use stdClass;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

trait BuscarUsuarioTrait
{
    use WhereTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    /**
     * Buscar o usuário
     *
     * @param array $campo     Campo que deseja buscar do usuário
     * @param bool  $paginacao Se vai ter paginação
     *
     * @return stdClass|array stdClass se tiver paginacao ou array quando não tiver paginacao
     */
    private function buscarUsuario(array $campo, bool $paginacao, bool $download = false): stdClass|array
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
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'empresa')
            ->campo(['cod', 'nome_fantasia'], 'empresa');

        if ($download) {
            return $this->pegarSubempresa($query->read());
        }
        return $query->read();
    }

    private function pegarSubempresa(stdClass|array $usuarios): stdClass|array
    {
        $retorno = [];
        foreach ($usuarios as $usuario) {
            if (!empty($usuario->id_admin_subempresa)) {
                $subempresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
                    ->pegarPrimeiroRegistro(
                        ['id', $usuario->id_admin_subempresa],
                        ['cod', 'nome_fantasia'],
                        'object'
                    );

                if (!empty($subempresa)) {
                    $usuario->subempresa_cod = $subempresa->cod;
                    $usuario->subempresa_nome_fantasia = $subempresa->nome_fantasia;
                }
                unset($usuario->id_admin_subempresa);
            }
            $retorno[] = $usuario;
        }
        return $retorno;
    }
}
