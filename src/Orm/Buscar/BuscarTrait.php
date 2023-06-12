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
    public function uuid(
        string $id,
        bool $erro = true,
        ?string $mensagem = null,
        ?string $titulo = null
    ) {
        $this->ormVerificarSeEntityExiste();

        if (empty($id) && !empty($mensagem)) {
            $titulo = !empty($titulo) ? $titulo : 'Não encontrado!';
            mensagemErro($titulo, $mensagem);
        } elseif (empty($id) && $erro) {
            mensagemStatus(404);
        } elseif (empty($id)) {
            return;
        }

        $eId = is_string($id) &&
            (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $id) ||
                preg_match('/^[a-f0-9]{32}$/i', $id));
        $quantidade = mb_strlen($id, 'UTF-8');

        if (!$eId || !in_array($quantidade, [32, 36])) {
            mensagemStatus(404, localhost: 'Você deve enviar um COD ou UUID para fazer a busca.');
        } elseif (!array_key_exists('uuid', $this->ormCampoBanco) && !array_key_exists('cod', $this->ormCampoBanco)) {
            mensagemStatus(404, localhost: 'A tabela informada não contem um ID.');
        } elseif (array_key_exists('uuid', $this->ormCampoBanco)) {
            $where = ['uuid', $id];
        } elseif (array_key_exists('cod', $this->ormCampoBanco)) {
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
    public function id(
        int $id,
        bool $erro = true,
        ?string $mensagem = null,
        ?string $titulo = null
    ) {
        $id = preg_match('/^[1-9]{1}[0-9]{0,}$/', $id) ? $id : '';

        if (empty($id) && !empty($mensagem)) {
            $titulo = !empty($titulo) ? $titulo : 'Não encontrado!';
            mensagemErro($titulo, $mensagem);
        } elseif (empty($id) && $erro) {
            mensagemStatus(404);
        } elseif (empty($id)) {
            return;
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
        } elseif (empty($idSlug) && $erro) {
            mensagemStatus(404);
        } elseif (empty($idSlug)) {
            return;
        }

        $eId = is_string($idSlug) &&
            (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $idSlug) ||
                preg_match('/^[a-f0-9]{32}$/i', $idSlug));

        $where = [$campo, $idSlug];
        if ($eId && array_key_exists('cod', $this->ormCampoBanco)) {
            $where = ['cod', $idSlug];
        } elseif ($eId && array_key_exists('uuid', $this->ormCampoBanco)) {
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
        if (!empty($this->ormWherePadrao)) {
            $where = [$where, [$this->ormWherePadrao]];
        }

        $this->ormVerificarSeEntityExiste();
        if (method_exists($this, 'regraBuscar')) {
            $this->regraBuscar();
        }

        $campo = $this->ormPegarCampoBusca();
        $campoTabela = $this->ormCampoBanco;
        if ($campo != '*' && !in_array('id', $campo)) {
            $campo[] = 'id';
        }
        if ($campo != '*' && array_key_exists('uuid', $campoTabela) && !in_array('uuid', $campo)) {
            $campo[] = 'uuid';
        }
        if (
            $campo != '*' &&
            !array_key_exists('uuid', $campoTabela) &&
            array_key_exists('cod', $campoTabela) &&
            !in_array('uuid', $campo)
        ) {
            $campo[] = 'cod';
        }

        $busca = $this->where($where)->order('id', 'DESC')->campo($campo);
        if (!empty($this->ormRelacionado)) {
            foreach ($this->ormRelacionado as $r) {
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
        } elseif (empty($busca) && $erro) {
            throw new Excecao(status: 404);
        } elseif (!$busca) {
            return;
        }

        $this->entityExiste = true;

        $nova = empty($this->ormEntityId);
        $this->ormEntityId = $busca['id'];
        if ($nova) {
            $this->ormPegarListaParaSet();
            $this->ormPegarListaDeAliasEReal();
        }
        $this->ormEntityRetorno = $busca;
        $this->ormSetarDadoDaEntity($busca, 'buscar');
        if (method_exists($this, 'regraPosBuscar')) {
            $this->regraPosBuscar();
        }
    }

    private function ormPegarCampoBusca(): string | array
    {
        $lista = $this->ormBuscar ?? [];
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

    /**
     * Recria um entidade usando um ID ou UUID e cancela o salvar
     *
     * @param   int|string      $id     ID ou uuid para recriar a entidade
     */
    protected function recriarEntity(int|string $id)
    {
        if (is_int($id)) {
            $this->id($id);
        } else {
            $this->uuid($id);
        }
        $this->cancelarSalvar();
    }
}
