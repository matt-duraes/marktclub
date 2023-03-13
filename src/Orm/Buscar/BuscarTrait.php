<?php

namespace ORM\Buscar;

use Erro\Excecao;

trait BuscarTrait
{
    /**
     * Buscar um registro pelo UUID
     *
     * @param   string|int      $id         ID ou UUID do usuário
     * @param   bool            $erro       Caso não encontre o resulta, retorna erro 404
     * @param   null|string     $mensagem   Mensagem em caso de erro
     * @param   null|string     $titulo     Título em caso de erro
     * @throws  Erro\Excecao
     */
    public function id(
        string $id,
        bool $erro = true,
        ?string $mensagem = null,
        ?string $titulo = null
    ) {
        $this->ormVerificarSeEntityExiste();

        if (empty($id) && !empty($mensagem)) {
            $titulo = !empty($titulo) ? $titulo : 'Não encontrado!';
            mensagemErro($titulo, $mensagem);
        } else if (empty($id) && $erro) {
            mensagemStatus(404);
        } else if (empty($id)) {
            return [];
        }

        $eId = is_string($id) &&
            (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $id) ||
                preg_match('/^[a-f0-9]{32}$/i', $id));
        $quantidade = mb_strlen($id, 'UTF-8');

        if (!$eId || !in_array($quantidade, [32, 36])) {
            mensagemStatus(404, localhost: 'Você deve enviar um COD ou UUID para fazer a busca.');
        } else if (!array_key_exists('uuid', $this->_campoBanco) && !array_key_exists('cod', $this->_campoBanco)) {
            mensagemStatus(404, localhost: 'A tabela informada não contem um ID.');
        } else if (array_key_exists('uuid', $this->_campoBanco)) {
            $where = ['uuid', $id];
        } else if (array_key_exists('cod', $this->_campoBanco)) {
            $where = ['cod', $id];
        }
        return $this->buscar($where, $erro, $mensagem, $titulo);
    }

    /**
     * Buscar um registro pelo ID
     *
     * @param   string|int      $id         ID ou UUID do usuário
     * @param   bool            $erro       Caso não encontre o resulta, retorna erro 404
     * @param   null|string     $mensagem   Mensagem em caso de erro
     * @param   null|string     $titulo     Título em caso de erro
     * @throws  Erro\Excecao
     */
    public function _id(
        int $id,
        bool $erro = true,
        ?string $mensagem = null,
        ?string $titulo = null
    ) {
        $id = preg_match('/^[1-9]{1}[0-9]{0,}$/', $id) ? $id : '';

        if (empty($id) && !empty($mensagem)) {
            $titulo = !empty($titulo) ? $titulo : 'Não encontrado!';
            mensagemErro($titulo, $mensagem);
        } else if (empty($id) && $erro) {
            mensagemStatus(404);
        } else if (empty($id)) {
            return [];
        }

        return $this->buscar(['id', $id], $erro, $mensagem, $titulo);
    }

    /**
     * Buscar um registro pelo UUID ou um slug
     *
     * @param   int|string      $idSlug     UUID ou slug para a busca
     * @param   string          $campo      Campo que será usado para a busca no caso do slug
     * @param   bool            $erro       Caso não encontre o resulta, retorna erro 404
     * @param   null|string     $mensagem   Mensagem em caso de erro
     * @param   null|string     $titulo     Título em caso de erro
     * @throws  Erro\Excecao
     */
    public function idSlug(
        int|string $idSlug,
        string $campo = 'url',
        bool $erro = true,
        ?string $mensagem = null,
        ?string $titulo = null
    ) {
        if (empty($idSlug) && !empty($mensagem)) {
            $titulo = !empty($titulo) ? $titulo : 'Não encontrado!';
            mensagemErro($titulo, $mensagem);
        } else if (empty($idSlug) && $erro) {
            mensagemStatus(404);
        } else if (empty($idSlug)) {
            return [];
        }

        $eId = is_string($idSlug) &&
            (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $idSlug) ||
                preg_match('/^[a-f0-9]{32}$/i', $idSlug));
        $quantidade = mb_strlen($idSlug, 'UTF-8');

        $where = [$campo, $idSlug];
        if ($eId && $quantidade == 32) {
            $where = ['cod', $idSlug];
        } else if ($eId && $quantidade == 36) {
            $where = ['uuid', $idSlug];
        }

        return $this->buscar($where, $erro, $mensagem, $titulo);
    }

    /**
     * Faz uma busca pelo where passado
     *
     * @param array     $where      Where para a busca
     * @param bool      $erro       Caso não encontre o resulta, retorna erro 404
     * @param   null|string     $mensagem   Mensagem em caso de erro
     * @param   null|string     $titulo     Título em caso de erro
     * @throws  Erro\Excecao
     */
    public function buscar(array $where, $erro = true, ?string $mensagem = null, ?string $titulo = null)
    {
        if (!empty($this->_wherePadrao)) {
            $where = [$where, [$this->_wherePadrao]];
        }
        $this->ormVerificarSeEntityExiste();
        if (method_exists($this, 'regraBuscar')) {
            $this->regraBuscar();
        }

        $campo = $this->ormPegarCampoBusca();
        $campoTabela = $this->_campoBanco;
        if ($campo != '*' && !in_array('id', $campo)) {
            $campo[] = 'id';
        }
        if ($campo != '*' && array_key_exists('uuid', $campoTabela) && !in_array('uuid', $campo)) {
            $campo[] = 'uuid';
        }
        if ($campo != '*' && !array_key_exists('uuid', $campoTabela) && array_key_exists('cod', $campoTabela) && !in_array('uuid', $campo)) {
            $campo[] = 'cod';
        }

        $busca = $this->where($where)->order('id', 'DESC')->campo($campo);
        if (!empty($this->_relacionado)) {
            foreach ($this->_relacionado as $r) {
                $r = (object)$r;
                $this
                    ->tabela($r->tabela)->campo($r->campo, $r->alias)
                    ->join($r->campo_atual, $r->campo_original, $r->condicao, $r->tabela_original, $r->tipo)
                    ->where($r->where, false);
            }
        }

        $busca = $busca->read(indice: 0, retorno: 'array');
        if (empty($busca) && !empty($mensagem)) {
            $titulo = !empty($titulo) ? $titulo : 'Não encontrado!';
            throw new Excecao(titulo: $titulo, mensagem: $mensagem, status: 404);
        } else if (empty($busca) && $erro) {
            throw new Excecao(status: 404);
        } elseif (!$busca) {
            return;
        }

        $this->entityExiste = true;

        $nova = empty($this->_entityId);
        $this->_entityId = $busca['id'];
        if ($nova) {
            $this->ormPegarListaParaSet();
            $this->ormPegarListaDeAliasEReal();
        }
        $this->_entityRetorno = $busca;
        $this->ormSetarDadoDaEntity($busca, 'buscar');
        if (method_exists($this, 'regraPosBuscar')) {
            $this->regraPosBuscar();
        }
    }

    private function ormPegarCampoBusca(): string | array
    {
        $lista = $this->_buscar ?? [];
        if (empty($lista)) {
            return '*';
        }

        $campo = [];
        foreach ($lista as $valor) {
            if (!is_array($valor)) {
                $campo[] = substr($valor, 0, 1) == '!' ? substr($valor, 1) : $valor;
                continue;
            }
            foreach ($valor as $subValor) {
                $campo[] = substr($subValor, 0, 1) == '!' ? substr($subValor, 1) : $subValor;
            }
        }
        return array_unique($campo);
    }
}
