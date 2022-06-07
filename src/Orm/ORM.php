<?php

namespace ORM;

use \PDO;
use Erro\Excecao;
use ORM\Join\JoinTrait;
use ORM\Buscar\ReadTrait;
use ORM\Group\GroupTrait;
use ORM\Limit\LimitTrait;
use ORM\Order\OrderTrait;
use ORM\Trait\TabelaTrait;
use ORM\Salvar\InsertTrait;
use ORM\Salvar\UpdateTrait;
use ORM\Trait\ValidarTrait;
use ORM\Condicao\WhereTrait;
use ORM\Deletar\DeleteTrait;
use ORM\Condicao\HavingTrait;
use ORM\Condicao\CondicaoTrait;
use ORM\Trait\OrmPropriedadeTrait;
use ORM\Trait\TraducaoPropriedadeTrait;

abstract class ORM
{
    use OrmPropriedadeTrait;
    use TraducaoPropriedadeTrait;
    use WhereTrait;
    use HavingTrait;
    use JoinTrait;
    use CondicaoTrait;
    use OrderTrait;
    use LimitTrait;
    use InsertTrait;
    use UpdateTrait;
    use DeleteTrait;
    use ValidarTrait;
    use ReadTrait;
    use GroupTrait;
    use TabelaTrait;

    /**
     * @param Array         $option         Option aceitos pelo PDO
     * @param Array         $conn           Option para a conexao podendo ser:
     *                                          host, banco, usuario e senha.
     *                                          Caso não informa, será usado o ENV
     */
    public function __construct(array $option = [], array $conn = [])
    {
        $this->_tabelaAtual = $this->_tabela;

        if (empty($option)) {
            $option[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
        }

        $host = $conn['host'] ?? env('DB_HOST', '');
        $banco = $conn['banco'] ?? env('DB_BANCO', '');
        $usuario = $conn['usuario'] ?? env('DB_USUARIO', '');
        $senha = $conn['senha'] ?? env('DB_SENHA', '');

        $this->_db = new PDO(
            'mysql:host=' . $host . ';dbname=' . $banco,
            $usuario,
            $senha,
            $option
        );
    }

    private function ormCriarValorUnico($campo, $valor, $tamanho, $numero = 0)
    {
        $valorTemp = $valor;
        $tamanhoTemp = $tamanho;
        if ($numero > 0) {
            $tamanhoTemp = $tamanhoTemp - (strlen($numero) + 1);
            $valorTemp = substr($valorTemp, 0, $tamanhoTemp) . '-' . $numero;
        }

        if ($this->existe([$campo, $valorTemp])) {
            $numero += 1;
            return $this->ormCriarValorUnico($campo, $valor, $tamanho, $numero);
        }
        return $valorTemp;
    }

    private function ormLimparArraySet(array $lista): array
    {
        $retorno = [];
        foreach ($lista as $ind => $val) {
            $retorno[] = is_int($ind) ? $val : $ind;
        }
        return $retorno;
    }

    public function teste()
    {
        $this->_rollback = true;
        return $this;
    }

    public function limpar()
    {
        $this->_db->rollBack();
    }

    /**
     * @param Array     $dado       Array com os dados que deseja salvar no formado: ['campo_tabela' => 'valor']
     */
    protected function dado(array $dado)
    {
        if (empty($dado)) {
            throw new Excecao(titulo: 'Campo dado incorreto!', mensagem: 'Você precisa enviar um array no método dado.');
        }
        $this->_dado = $dado;
        return $this;
    }

    protected function debug()
    {
        if (SISTEMA != 'producao') {
            echo '<pre>';
            print_r([
                'query' => $this->ormMontarQueryString(),
                'mysql' => $this->ormMontarQueryReal(),
                'select' => $this->_select,
                'where' => $this->_whereDado,
                'having' => $this->_havingDado,
                'value' => $this->_condicaoValue,
                'limit' => $this->_limit,
                'order' => $this->_order,
                'group' => $this->_group,
                'campo' => implode(', ', $this->_campo),
                'join' => $this->_join,
            ]);
            exit();
        }
    }

    private function ormExecute(string $query, array $dado = [])
    {
        $sql = $this->_db->prepare($query);
        $this->_db->beginTransaction();

        if ($dado) {
            foreach ($dado as $ind => $valor) {
                $null = null;
                if (!is_numeric($dado[$ind]) && empty($dado[$ind])) {
                    $sql->bindValue(":{$ind}", $null, PDO::PARAM_NULL);
                } elseif (filter_var($valor, FILTER_VALIDATE_INT)) {
                    $sql->bindValue(":{$ind}", $valor, PDO::PARAM_STR);
                } elseif (filter_var($valor, FILTER_VALIDATE_BOOLEAN)) {
                    $sql->bindValue(":{$ind}", $valor, PDO::PARAM_BOOL);
                } elseif (is_string($valor)) {
                    $sql->bindValue(":{$ind}", $valor, PDO::PARAM_STR);
                } else {
                    $sql->bindValue(":{$ind}", $valor);
                }
            }
        }

        try {
            $run = $sql->execute();
        } catch (\Throwable $e) {
            $run = false;
            $erroFatal = $e->getMessage();
        }

        if (!$run) {
            $erroTexto = $sql->errorInfo()[2] ?? $erroFatal ?? false;
            if (SISTEMA == 'producao') {
                $erroTexto = $sql->errorInfo()[2] ?? false;
            }
            if (false === $erroTexto) {
                $erroTexto = 'Ocorreu um erro ao executar ação, recarregue o navegador e tente novamente.';
            }
            $this->_db->rollBack();
            return $this->ormTraduzirErro($erroTexto);
        }

        $this->_ultimoId = $this->_db->lastInsertId();
        if (true !== $this->_rollback) {
            $this->_db->commit();
        }
        return $sql;
    }

    private function ormResetarOrm()
    {
        $this->_tabelaAtual = $this->_tabela;
        $this->_ultimoId = 0;

        $this->_condicaoNumero = 0;
        $this->_whereDado = [];
        $this->_havingDado = [];
        $this->_condicaoValue = [];

        $this->_order = [];

        $this->_limit = '';
        $this->_limitPagina = 1;
        $this->_limitQuantidade = 20;
        $this->_paginacao = false;

        $this->_select = '';
        $this->_group = '';
        $this->_campo = [];
        $this->_join = [];
    }

    private function ormMontarQueryReal()
    {
        $query = $this->ormMontarQueryString();
        if (!$this->_condicaoValue) {
            return $query;
        }
        $de = [];
        $por = [];
        if ($this->_condicaoValue) {
            foreach ($this->_condicaoValue as $ind => $val) {
                $de[] = ':' . $ind;
                $por[] = "'" . $val . "'";
            }
        }
        return str_replace($de, $por, $query);
    }

    private function ormUuid(): String
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    protected function ormPegarColunaBanco(): array
    {
        $query = $this->_db->query("SHOW FULL COLUMNS FROM `{$this->_tabela}`");
        $query->setFetchMode(PDO::FETCH_OBJ);
        $lista = $query->fetchAll();

        $array = [];
        foreach ($lista as $r) {
            if (mb_detect_encoding($r->Comment, 'UTF-8, ISO-8859-1')) {
                $comment = utf8_encode($r->Comment);
            } else {
                $comment = $r->Comment;
            }
            $comentario = mb_strtolower($comment, 'UTF-8');

            preg_match('/\[(.*?)\]/', $comment, $titulo);
            $titulo = $titulo[1] ?? '';
            preg_match('/\|(.*?)\|/', $comment, $slug);
            $slug = $slug[1] ?? '';
            preg_match('/\((.*?)\)/', $comment, $validar);
            $validar = array_key_exists(1, $validar) ? explode(',', str_replace(' ', '', $validar[1])) : [];
            $tipo = $r->Type;
            $tamanho = false;

            if (!empty($r->Type) && strstr($r->Type, '(')) {
                $explode = explode('(', $r->Type);
                $tipo = $explode[0];
                $tamanho = preg_replace('/[^0-9]/', '', $explode[1]);
            }

            $array[$r->Field] = (object) [
                'campo' => $r->Field,
                'tipo' => $tipo,
                'tamanho' => (int) $tamanho,
                'obrigatorio' => ($r->Null == 'YES') ? false : true,
                'padrao' => $r->Default,
                'download' => strstr($comentario, '{download}') ? true : false,
                'titulo' => $titulo,
                'validar' => $validar,
                'slug' => $slug
            ];
        }
        return $array;
    }

    protected function ormPegarTodosOsDadoPeloId($id)
    {
        if (empty($id) || !preg_match('/^[0-9]+$/', $id)) {
            return [];
        }
        $query = $this->_db->prepare('SELECT * FROM `' . $this->_tabela . '` WHERE `id` = ' . $id);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $dado = $query->fetchAll();
        return $dado[0] ?? [];
    }

    protected function ormDestruirPDO()
    {
        $this->_db = null;
    }

    protected function ormSalvarArquivo()
    {
        if ($this->_arquivoSalvar) {
            foreach ($this->_arquivoSalvar as $arquivo) {
                $arquivo->salvar();
            }
        }
        $this->_arquivoSalvar = [];
    }
}
