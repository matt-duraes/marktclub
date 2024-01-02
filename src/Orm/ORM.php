<?php

namespace ORM;

use PDO;
use Erro\Excecao;
use ORM\Join\JoinTrait;
use ORM\Buscar\ReadTrait;
use ORM\Group\GroupTrait;
use ORM\Limit\LimitTrait;
use ORM\Order\OrderTrait;
use ORM\Trait\SetGetTrait;
use ORM\Trait\TabelaTrait;
use ORM\Salvar\InsertTrait;
use ORM\Salvar\UpdateTrait;
use ORM\Trait\ValidarTrait;
use Status\StatusInterface;
use Modules\ModuleInterface;
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
    use SetGetTrait;

    /**
     * @param array $option Option aceitos pelo PDO
     * @param array $conn   Option para a conexao podendo ser:
     *                      host, banco, usuario e senha.
     *                      Caso não informa, será usado o ENV
     */
    public function __construct(array $option = [], array $conn = [])
    {
        $this->ormTabelaAtual = $this->ormTabela;

        if (empty($option)) {
            $option[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
        }

        $banco = $conn['banco'] ?? env('DB_BANCO', '');
        $usuario = $conn['usuario'] ?? env('DB_USUARIO', '');
        $senha = $conn['senha'] ?? env('DB_SENHA', '');
        $porta = $conn['porta'] ?? env('DB_PORTA', '');
        $porta = !empty($porta) && preg_match('/^[0-9]+$/', $porta) ? ';port=' . $porta : '';

        $escrita = $conn['escrita'] ?? env('DB_ESCRITA', '');
        $escrita = $this->pegarIpSeDominio($escrita);

        $leitura = $conn['leitura'] ?? env('DB_LEITURA', '');
        $leitura = $this->pegarIpSeDominio($leitura);

        $this->ormDBEscrita = new PDO(
            'mysql:host=' . $escrita . ';dbname=' . $banco . $porta,
            $usuario,
            $senha,
            $option
        );
        if(!empty($leitura)) {
            $this->ormLeitura = true;
            $this->ormDBLeitura = new PDO(
                'mysql:host=' . $leitura . ';dbname=' . $banco . $porta,
                $usuario,
                $senha,
                $option
            );
        }
    }

    private function pegarIpSeDominio($host)
    {
        return filter_var($host, FILTER_VALIDATE_DOMAIN) ? gethostbyname($host) : $host;
    }

    private function pegarReplace(string $tabela = null): array
    {
        if (!file_exists(ROOT . '/database/replace.php')) {
            return [];
        }
        $replace = require ROOT . '/database/replace.php';
        $tabela = !empty($tabela) ? $tabela : $this->ormTabelaAtual;
        if (!array_key_exists($tabela, $replace)) {
            return [];
        }
        return $replace[$tabela];
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
        $this->ormRollback = true;
        return $this;
    }

    public function limpar()
    {
        $this->ormDB->rollBack();
    }

    /**
     * @param array $dado Array com os dados que deseja salvar no formado: ['campo_tabela' => 'valor']
     */
    protected function dado(array $dado, ?array $replace = null)
    {
        if (empty($dado)) {
            throw new Excecao(
                titulo: 'Campo dado incorreto!',
                mensagem: 'Você precisa enviar um array no método dado.'
            );
        }
        $dado = $this->replaceClasseDado($dado);

        $replace = is_array($replace) ? array_flip($replace) : array_flip($this->pegarReplace());
        if (is_array($replace) && $replace) {
            foreach ($dado as $ind => $val) {
                if (!array_key_exists($ind, $replace)) {
                    continue;
                }
                $dado[$replace[$ind]] = $val;
                unset($dado[$ind]);
            }
        }

        $this->ormDado = $dado;
        return $this;
    }

    private function replaceClasseDado($dado)
    {
        $retorno = [];
        foreach ($dado as $ind => $val) {
            if ($val instanceof ModuleInterface) {
                $val = $val->banco();
            } elseif ($val instanceof StatusInterface) {
                $val = $val->numero();
            } elseif ($val instanceof Entity && method_exists($val, 'getId')) {
                $val = $val->get('id');
            }
            $retorno[$ind] = $val;
        }
        return $retorno;
    }

    protected function debug()
    {
        if (SISTEMA != 'producao') {
            echo '<pre>';
            print_r([
                'query'  => $this->ormMontarQueryString(),
                'mysql'  => $this->ormMontarQueryReal(),
                'select' => $this->ormSelect,
                'where'  => $this->ormWhereDado,
                'having' => $this->ormHavingDado,
                'value'  => $this->ormCondicaoValue,
                'limit'  => $this->ormLimit,
                'order'  => $this->ormOrder,
                'group'  => $this->ormGroup,
                'campo'  => implode(', ', $this->ormCampo),
                'join'   => $this->ormJoin,
            ]);
            exit();
        }
    }

    private function ormExecute(string $query, array $dado = [])
    {
        $this->ormDB = $this->ormDBEscrita;
        if($this->ormQueryLeitura($query)) {
            $this->ormDB = $this->ormDBLeitura;
        }
        $sql = $this->ormDB->prepare($query);
        $this->ormDB->beginTransaction();

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
            $this->ormDB->rollBack();
            return $this->ormTraduzirErro($erroTexto);
        }

        $this->ormUltimoId = $this->ormDB->lastInsertId();
        if (true !== $this->ormRollback) {
            $this->ormDB->commit();
        }
        return $sql;
    }

    private function ormResetarOrm()
    {
        $this->ormTabelaAtual = $this->ormTabela;
        $this->ormUltimoId = 0;

        $this->ormCondicaoNumero = 0;
        $this->ormWhereDado = [];
        $this->ormHavingDado = [];
        $this->ormCondicaoValue = [];

        $this->ormOrder = [];

        $this->ormLimit = '';
        $this->ormLimitPagina = 1;
        $this->ormLimitQuantidade = 20;
        $this->ormPaginacao = false;

        $this->ormSelect = '';
        $this->ormGroup = '';
        $this->ormCampo = [];
        $this->ormJoin = [];
    }

    private function ormMontarQueryReal()
    {
        $query = $this->ormMontarQueryString();
        if (!$this->ormCondicaoValue) {
            return $query;
        }
        $de = [];
        $por = [];
        if ($this->ormCondicaoValue) {
            foreach ($this->ormCondicaoValue as $ind => $val) {
                $de[] = ':' . $ind;
                $por[] = "'" . $val . "'";
            }
        }
        return str_replace($de, $por, $query);
    }

    private function ormUuid(): string
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
        $DB = $this->ormLeitura ? $this->ormDBLeitura : $this->ormDBEscrita;

        $query = $DB->query("SHOW FULL COLUMNS FROM `{$this->ormTabela}`");
        $query->setFetchMode(PDO::FETCH_OBJ);
        $lista = $query->fetchAll();

        $array = [];
        foreach ($lista as $r) {
            if (mb_detect_encoding($r->Comment, 'UTF-8, ISO-8859-1')) {
                $comment = mb_convert_encoding($r->Comment, 'UTF-8', 'ISO-8859-1');
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
                'campo'       => $r->Field,
                'tipo'        => $tipo,
                'tamanho'     => (int) $tamanho,
                'obrigatorio' => ($r->Null == 'YES') ? false : true,
                'padrao'      => $r->Default,
                'download'    => strstr($comentario, '{download}') ? true : false,
                'titulo'      => $titulo,
                'validar'     => $validar,
                'slug'        => $slug
            ];
        }
        return $array;
    }

    protected function ormPegarTodosOsDadoPeloId($id)
    {
        if (empty($id) || !preg_match('/^[0-9]+$/', $id)) {
            return [];
        }
        $query = $this->ormDB->prepare('SELECT * FROM `' . $this->ormTabela . '` WHERE `id` = ' . $id);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $dado = $query->fetchAll();
        $dado = $dado[0] ?? [];

        $replace = $this->pegarReplace();
        if ($replace) {
            foreach ($replace as $ind => $val) {
                if (array_key_exists($ind, $dado)) {
                    $dado[$val] = $dado[$ind];
                    unset($dado[$ind]);
                }
            }
        }
        return $dado;
    }

    protected function ormDestruirPDO()
    {
        $this->ormDB = null;
        $this->ormDBEscrita = null;
        $this->ormDBLeitura = null;
    }

    protected function ormSalvarArquivo()
    {
        if ($this->ormArquivoSalvar) {
            foreach ($this->ormArquivoSalvar as $arquivo) {
                $arquivo->salvar();
            }
        }
        $this->ormArquivoSalvar = [];
    }

    private function ormQueryLeitura($query)
    {
        $query = mb_strtoupper($query, 'UTF-8');
        return $this->ormLeitura && !preg_match('/^(INSERT|DELETE|UPDATE)/', $query);
    }
}
