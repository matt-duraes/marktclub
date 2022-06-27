<?php

namespace ORM\Buscar;

use Erro\Erro;
use Erro\Excecao;

trait BuscarTrait
{
    /**
     * @param String|Int    $id         ID ou UUID do usuário
     * @param Bool      $erro       Caso não encontre o resulta, retorna erro 404
     */
    public function id(string $id, bool $erro = true)
    {
        $this->ormVerificarSeEntityExiste();

        if (empty($id) && $erro) {
            mensagemStatus(404);
        } else if (empty($id)) {
            return [];
        }

        $quantidade = mb_strlen($id, 'UTF-8');
        if (!in_array($quantidade, [32, 36])) {
            return mensagemStatus(404, localhost: 'Você deve enviar um COD ou UUID para fazer a busca.');
        } else if (array_key_exists('uuid', $this->_campoBanco)) {
            $where = ['uuid', $id];
        } else if (array_key_exists('cod', $this->_campoBanco)) {
            $where = ['cod', $id];
        }
        return $this->buscar($where, $erro);
    }
    public function _id(int $id, bool $erro = true)
    {
        if (empty($id) && $erro) {
            mensagemStatus(404);
        } else if (empty($id)) {
            return [];
        }

        return $this->buscar(['id', $id], $erro);
    }

    /**
     * @param Array     $where      Where para a busca
     * @param Bool      $erro       Caso não encontre o resulta, retorna erro 404
     */
    public function buscar(array $where, $erro = true)
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
        if (!$busca && $erro) {
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
