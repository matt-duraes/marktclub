<?php

namespace Database;

use PDO;
use Throwable;

final class DataBase
{
    /**
     * @var string
     */
    public string $tabela;

    /**
     * @var string
     */
    public string $diretorio;

    /**
     * @var PDO
     */
    private PDO $db;

    /**
     * @var string
     */
    private string $banco;

    /**
     * @var array
     */
    private array $dado = [];

    /**
     * @var array
     */
    private array $lista = [];

    /**
     * @var array
     */
    private array $estrutura = [];

    /**
     * @var array
     */
    private array $propriedade = [];

    /**
     * @var array
     */
    private array $relacionado = [];

    /**
     * @var int
     */
    private int $contador = 0;

    /**
     * @var array
     */
    public array $replace = [];

    public function __construct()
    {
        if (!eLocalhost() || env('DB_STATUS', '') != 'localhost') {
            exit();
        }
        $this->banco = env('DB_BANCO');
        $this->db = new PDO(
            'mysql:host=' . env('DB_HOST') . ';dbname=' . $this->banco,
            env('DB_USUARIO'),
            env('DB_SENHA'),
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ]
        );
    }

    /**
     */
    public function sistemaDeletar(): void
    {
        $this->executarCreate('deletar');
    }

    /**
     * @param $acao
     */
    private function executarCreate($acao): void
    {
        if ($acao == 'deletar') {
            $query = "SET foreign_key_checks = 0; DROP TABLE IF EXISTS `{$this->tabela}`; SET foreign_key_checks = 1;";
        } elseif ($acao == 'criar') {
            $dado = implode(', ', array_merge($this->estrutura, $this->propriedade));
            $query = 'CREATE TABLE `' . $this->tabela . '` ('
                . $dado . ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
        } elseif ($acao == 'relacionar' && $this->relacionado) {
            $query = $this->montarRelacionado();
        } else {
            return;
        }

        $sql = $this->db->prepare($query);
        try {
            $sql->execute();
        } catch (Throwable $th) {
            echo '<pre>';
            echo $query;
            print_r($th);
            exit();
        }

        if ($acao == 'deletar') {
            return;
        }
        $sql = $this->db->prepare("SELECT * FROM `{$this->tabela}` LIMIT 0,1");
        $sql->execute();
        $erro = $sql->errorInfo()[2] ?? '';

        if (!empty($erro) && $acao == 'criar') {
            echo 'ERRO AO CRIAR TABELA {$this->tabela}.<br><br>' .
                str_replace(
                    ["CREATE TABLE `{$this->tabela}` (", ') ENGINE', ','],
                    ["CREATE TABLE `{$this->tabela}` (<br>", '<br>) ENGINE', ',<br>'],
                    $query
                ) . '<br>';
            exit();
        } elseif (!empty($erro)) {
            echo "ERRO AO RELACIONAR TABELA {$this->tabela}.<br><br>" . str_replace(';', ';<br>', $query) . '<br>';
            exit();
        }

        if (file_exists(__DIR__ . '/../../database/' . $this->diretorio . '/dado.php') && $acao == 'criar') {
            $lista = require __DIR__ . '/../../database/' . $this->diretorio . '/dado.php';
            foreach ($lista as $linha) {
                $this->insert($linha);
            }
        }
    }

    /**
     * @return string
     */
    private function montarRelacionado(): string
    {
        $query = '';
        foreach ($this->relacionado as $r) {
            $tabela = $r[0];
            $indice = $r[1];
            $nome = $this->tabela . '_' . $indice;
            $campo = $r[2];
            $delete = $r[3];
            $update = $r[4];

            $query .= "
                ALTER TABLE `{$this->tabela}` ADD CONSTRAINT `{$nome}` FOREIGN KEY (`{$indice}`)
                REFERENCES `{$tabela}`(`{$campo}`) ON DELETE {$delete} ON UPDATE {$update};
            ";
        }
        return $query;
    }

    /**
     * @param $dado
     */
    public function insert($dado): void
    {
        $campos_ini = [];

        $queryTabela = $this->db->query("SHOW FULL COLUMNS FROM `{$this->tabela}`");
        $queryTabela->setFetchMode(PDO::FETCH_OBJ);
        foreach ($queryTabela->fetchAll() as $rCampo) {
            if ($rCampo->Field == 'uuid' && !array_key_exists('uuid', $dado)) {
                $dado['uuid'] = uuid();
            } elseif ($rCampo->Field == 'data_criacao' && !array_key_exists('data_criacao', $dado)) {
                $dado['data_criacao'] = date('Y-m-d H:i:s');
            } elseif ($rCampo->Field == 'data_atualizacao' && !array_key_exists('data_atualizacao', $dado)) {
                $dado['data_atualizacao'] = date('Y-m-d H:i:s');
            }
        }

        $contador = false;
        if (is_array($dado) && $dado) {
            foreach ($dado as $ind => $val) {
                if ($val == '++' && !$contador) {
                    $this->contador++;
                    $contador = true;
                }
                if ($val == '++') {
                    $dado[$ind] = $this->contador;
                }
                $campos_ini[] = $ind;
            }
        }

        $campos = '`' . implode('`, `', $campos_ini) . '`';
        $valores = ':' . implode(', :', $campos_ini);

        $query = "INSERT INTO `{$this->tabela}` ({$campos}) VALUES ({$valores})";
        $sql = $this->db->prepare($query);

        if (is_array($dado) && $dado) {
            foreach ($dado as $ind => $valor) {
                if (is_array($valor) || is_object($valor)) {
                    $valor = json_encode($valor);
                }
                $null = null;
                if (!is_numeric($dado[$ind]) && empty($dado[$ind])) {
                    $sql->bindValue(":{$ind}", $null, PDO::PARAM_NULL);
                } elseif (filter_var($valor, FILTER_VALIDATE_INT)) {
                    $sql->bindValue(":{$ind}", $valor, PDO::PARAM_INT);
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
        } catch (Throwable $th) {
            echo '<pre>';
            echo $query;
            print_r($th);
            exit();
        }

        if (!$run) {
            echo '<pre>';
            print_r($sql->errorInfo());
            exit();
        }
    }

    /**
     */
    public function sistemaRelacionar(): void
    {
        $this->executarCreate('relacionar');
    }

    /**
     * Faz o replace do campo do banco para o nome futuro
     *
     * @param string $nome Nome futuro do campo
     */
    public function replace(string $nome)
    {
        $this->replace[$this->dado['campo']] = $nome;
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | RESETA
    |--------------------------------------------------------------------------
     */

    /**
     */
    public function sistemaCriar(): void
    {
        $this->resetar();

        if (!$this->lista) {
            trigger_error('A LISTA DE CAMPOS ESTÁ VAZIA.');
            exit();
        }

        foreach ($this->lista as $r) {
            $campo = $r['campo'];
            $tipo = $r['tipo'];
            $null = true === $r['null'] ? 'DEFAULT NULL' : 'NOT NULL';
            $tamanho = $r['tamanho'];
            $primario = true === $r['primario'] ? 'AUTO_INCREMENT' : '';
            $zero = true === $r['zero'] ? 'UNSIGNED ZEROFILL' : '';
            $unico = $r['unico'] ?? false;
            $slug = $r['slug'] ?? false;
            if ($slug) {
                $null = 'NOT NULL';
                $unico = true;
                $tipo = $this->lista[$slug]['tipo'] ?? $tipo;
                $tamanho = $this->lista[$slug]['tamanho'] ?? 191;
            }

            $tamanho_lista = [
                'BIGINT'  => 11,
                'CHAR'    => 36,
                'VARCHAR' => 250,
                'DECIMAL' => '10,2',
            ];

            if ($primario) {
                $this->propriedade[] = 'PRIMARY KEY (`id`)';
            }
            if ($unico) {
                $this->propriedade[] = 'UNIQUE KEY `' . $campo . '` (`' . $campo . '`)';
            }
            if ($unico && $tamanho > 191) {
                $tamanho = 191;
            }

            if (empty($tamanho) && $tipo == 'INT') {
                $tamanho = '(1)';
            } elseif (!empty($tamanho) && $tipo == 'INT') {
                if ($tamanho <= 2) {
                    $tipo = 'TINYINT';
                } elseif ($tamanho <= 4) {
                    $tipo = 'SMALLINT';
                } elseif ($tamanho <= 7) {
                    $tipo = 'MEDIUMINT';
                } elseif ($tamanho <= 9) {
                    $tipo = 'INT';
                } else {
                    $tipo = 'BIGINT';
                }
                $tamanho = '(' . $tamanho . ')';
            } elseif (empty($tamanho) && isset($tamanho_lista[$tipo])) {
                $tamanho = '(' . $tamanho_lista[$tipo] . ')';
            } elseif (!empty($tamanho)) {
                $tamanho = '(' . $tamanho . ')';
            }

            $padrao = isset($r['padrao']) && !empty($r['padrao']) ? "DEFAULT '" . $r['padrao'] . "'" : '';

            $titulo = !empty($r['titulo']) ? $r['titulo']
                : str_replace(['_', '-'], ' ', mb_strtoupper($r['campo'], 'UTF-8'));

            $comentario = '[' . $titulo . ']';
            if ($r['download']) {
                $comentario .= '{download}';
            }
            if (!empty($r['validar'])) {
                $comentario .= '(' . $r['validar'] . ')';
            }
            if ($slug) {
                $comentario .= '|' . $slug . '|';
            }
            $this->estrutura[] =
                "`{$campo}` {$tipo}{$tamanho} {$zero} {$padrao} {$null} {$primario} COMMENT '{$comentario}'";
        }

        $this->executarCreate('criar');
    }

    /*
    |--------------------------------------------------------------------------
    | TIPOS PADRÕES
    |--------------------------------------------------------------------------
    */

    //doc

    /**
     */
    private function resetar(): void
    {
        if ($this->dado && empty($this->dado['campo'])) {
            trigger_error('NOME PARA O CAMPO DA TABELA É OBRIGATÓRIO.');
            exit();
        } elseif ($this->dado) {
            $this->lista[$this->dado['campo']] = $this->dado;
        }

        $this->dado = [
            'campo'    => '',
            'titulo'   => '',
            'tipo'     => 'varchar',
            'tamanho'  => '',
            'download' => '',
            'null'     => false,
            'unico'    => false,
            'auto'     => false,
            'primario' => false,
            'zero'     => false,
            'validar'  => false,
        ];
    }

    //doc

    /**
     * Cria um campo padrão de id sendo int(9) auto-incremento e único
     *
     * @return self
     */
    public function id(): self
    {
        $this->setarTipo('id', 'INT');
        $this->dado['auto'] = true;
        $this->dado['tamanho'] = 9;
        $this->dado['primario'] = true;
        return $this;
    }

    //doc

    /**
     * @param $campo
     * @param $tipo
     */
    private function setarTipo($campo, $tipo): void
    {
        $this->resetar();
        $this->dado['campo'] = $campo;
        $this->dado['tipo'] = $tipo;
    }

    //doc

    /**
     * Cria um campo padrão de uuid sendo char(36) único
     *
     * @return DataBase
     */
    public function uuid(): DataBase
    {
        $this->setarTipo('uuid', 'CHAR');
        $this->dado['tamanho'] = 36;
        $this->dado['unico'] = true;
        return $this;
    }

    public function cod(): DataBase
    {
        $this->setarTipo('cod', 'CHAR');
        $this->dado['tamanho'] = 36;
        $this->dado['unico'] = true;
        $this->replace('uuid');
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de nome sendo varchar(80)
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function nome(string $nome): DataBase
    {
        $this->setarTipo($nome, 'VARCHAR');
        $this->dado['tamanho'] = 80;
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de data_criacao sendo datetime
     *
     * @param  string   $nome Nome do campo com padrão de data_criacao
     * @return DataBase
     */
    public function dataCriacao(string $nome = 'data_criacao'): DataBase
    {
        $this->setarTipo($nome, 'DATETIME');
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de data_atualizacao sendo datatime
     *
     * @param  string   $nome Nome do campo com padrão de data_atualizacao
     * @return DataBase
     */
    public function dataAtualizacao(string $nome = 'data_atualizacao'): DataBase
    {
        $this->setarTipo($nome, 'DATETIME');
        $this->dado['null'] = true;
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de e-mail sendo varchar(255) único
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function email(string $nome): DataBase
    {
        $this->setarTipo($nome, 'VARCHAR');
        $this->dado['tamanho'] = 255;
        $this->dado['validar'] = 'email';
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de telefone sendo bigint(11)
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function telefone(string $nome): DataBase
    {
        $this->setarTipo($nome, 'BIGINT');
        $this->dado['tamanho'] = 11;
        $this->dado['validar'] = 'telefone';
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de dinheiro sendo decimal(10,2)
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function dinheiro(string $nome): DataBase
    {
        $this->setarTipo($nome, 'DECIMAL');
        $this->dado['tamanho'] = '20,2';
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de CPF sendo bigint(1)
     *
     * @param  string   $nome Nome do campo com padrão de documento_cpf
     * @return DataBase
     */
    public function cpf(string $nome = 'documento_cpf'): DataBase
    {
        $this->setarTipo($nome, 'BIGINT');
        $this->dado['tamanho'] = 11;
        $this->dado['zero'] = true;
        $this->dado['validar'] = 'cpf';
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de CNPJ sendo bigint(14)
     *
     * @param  string   $nome Nome do campo com padrão de documento_cnpj
     * @return DataBase
     */
    public function cnpj(string $nome = 'documento_cnpj'): DataBase
    {
        $this->setarTipo($nome, 'BIGINT');
        $this->dado['tamanho'] = 14;
        $this->dado['zero'] = true;
        $this->dado['validar'] = 'cnpj';
        return $this;
    }

    //doc

    /**
     * Cria um campo padrão de CEP sendo int(8) e adicionando zero a esquerda
     *
     * @param  string   $nome Nome do campo com padrão de endereco_cep
     * @return DataBase
     */
    public function cep(string $nome = 'endereco_cep'): DataBase
    {
        $this->setarTipo($nome, 'INT');
        $this->dado['tamanho'] = 8;
        $this->dado['zero'] = true;
        return $this;
    }

    /**
     * Cria um campo padrão de imagem sendo char(36)
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function imagem(string $nome): DataBase
    {
        $this->setarTipo($nome, 'VARCHAR');
        $this->dado['tamanho'] = 41;
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SETANDO OS TIPOS ACEITOS
    |--------------------------------------------------------------------------
     */

    /**
     * Cria um campo padrão de status sendo tinyint(1)
     *
     * @param  string   $campo Nome do campo com padrão de status
     * @return DataBase
     */
    public function status(string $campo = 'status'): DataBase
    {
        $this->setarTipo($campo, 'TINYINT');
        $this->dado['tamanho'] = 1;
        $this->dado['null'] = true;
        return $this;
    }

    //doc

    /**
     * Campo para criação de slug para URL e afins
     *
     * @param  string   $nome
     * @param  string   $base Qual campo ser��� usado como base, por exemplo, se existe um campo titulo, ele pegara o
     *                        titulo e criar o slug
     * @return DataBase
     */
    public function slug(string $nome, string $base): DataBase
    {
        $this->setarTipo($nome, 'VARCHAR');
        $this->dado['slug'] = $base;
        return $this;
    }

    //doc

    /**
     * Campo do tipo varchar
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function varchar(string $nome): DataBase
    {
        $this->setarTipo($nome, 'VARCHAR');
        return $this;
    }

    //doc

    /**
     * Campo do tipo char
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function char(string $nome): DataBase
    {
        $this->setarTipo($nome, 'CHAR');
        return $this;
    }

    //doc

    /**
     * Campo do tipo datatime
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function datetime(string $nome): DataBase
    {
        $this->setarTipo($nome, 'DATETIME');
        return $this;
    }

    //doc

    /**
     * Campo do tipo date
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function date(string $nome): DataBase
    {
        $this->setarTipo($nome, 'DATE');
        return $this;
    }

    //doc

    /**
     * Campo do tipo time
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function time(string $nome): DataBase
    {
        $this->setarTipo($nome, 'TIME');
        return $this;
    }

    //doc

    /**
     * Campo do tipo tinyint
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function tinyint(string $nome): DataBase
    {
        $this->setarTipo($nome, 'TINYINT');
        return $this;
    }

    //doc

    /**
     * Campo do tipo int
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function int(string $nome): DataBase
    {
        $this->setarTipo($nome, 'INT');
        return $this;
    }

    //doc

    /**
     * Campo do tipo bigint
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function bigint(string $nome): DataBase
    {
        $this->setarTipo($nome, 'BIGINT');
        return $this;
    }

    //doc

    /**
     * Campo do tipo decimal
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function decimal(string $nome): DataBase
    {
        $this->setarTipo($nome, 'DECIMAL');
        return $this;
    }

    //doc

    /**
     * Campo do tipo float
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function float(string $nome): DataBase
    {
        $this->setarTipo($nome, 'FLOAT');
        return $this;
    }

    //doc

    /**
     * Campo do tipo text
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function text(string $nome): DataBase
    {
        $this->setarTipo($nome, 'TEXT');
        return $this;
    }

    //doc

    /**
     * Campo do tipo json
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function json(string $nome): DataBase
    {
        $this->setarTipo($nome, 'JSON');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | DEMAIS PROPRIEDADES
    |--------------------------------------------------------------------------
    */

    //doc

    /**
     * Campo do tipo lonttext
     *
     * @param  string   $nome Nome do campo
     * @return DataBase
     */
    public function longtext(string $nome): DataBase
    {
        $this->setarTipo($nome, 'LONGTEXT');
        return $this;
    }

    // doc

    /**
     * Se o campo vai ter um valor null
     *
     * @return DataBase
     */
    public function null(): DataBase
    {
        $this->dado['null'] = true;
        return $this;
    }

    // doc

    /**
     * Se o campo vai poder ter apenas valores únicos
     *
     * @return DataBase
     */
    public function unico(): DataBase
    {
        $this->dado['unico'] = true;
        return $this;
    }

    // doc

    /**
     * Se vai ser um campo auto incremente
     *
     * @return DataBase
     */
    public function auto(): DataBase
    {
        $this->dado['auto'] = true;
        return $this;
    }

    // doc

    /**
     * Se vai ser um campo primario
     *
     * @return DataBase
     */
    public function primario(): DataBase
    {
        $this->dado['primario'] = true;
        return $this;
    }

    // doc

    /**
     * Se o valor vai ter zero a esquerda para completar o tamanho do campo
     *
     * @return DataBase
     */
    public function zero(): DataBase
    {
        $this->dado['zero'] = true;
        return $this;
    }

    // doc

    /**
     * Coloca um valor padrão no campo
     *
     * @param  mixed    $valor Valor padrão para o campo
     * @return DataBase
     */
    public function padrao(mixed $valor): DataBase
    {
        $this->dado['padrao'] = $valor;
        return $this;
    }

    /**
     * Se o campo vai precisar ser validado antes de salvar
     *
     * @param  string   $validar Se o campo precisa ser validado podendo ser: cpf, cnpj, telefone, email, url,
     *                           positivo, negativo
     * @return DataBase
     */
    public function validar(string $validar): DataBase
    {
        $this->dado['validar'] = $validar;
        return $this;
    }

    /**
     * Um título para o campo, caso não sejá informado, pegará o nome do campo
     *
     * @param  string   $titulo Título para o campo
     * @return DataBase
     */
    public function titulo(string $titulo): DataBase
    {
        $this->dado['titulo'] = $titulo;
        return $this;
    }

    /**
     * Se o campo vai ter algum relacionamento
     *
     * @param  string      $tabela Nome da tabela que será relacionada
     * @param  string      $campo  Nome do campo que será relacionado
     * @param  null|string $delete Ação que será tomada quando o usuário foi deletado podendo ser: CASCADE, RESTRICT,
     *                             SET NULL, NO ACTION
     * @param  null|string $update Ação que será tomada quando o usuário foi deletado podendo ser: CASCADE, RESTRICT,
     *                             SET NULL, NO ACTION
     * @return self
     */
    public function relacionado(string $tabela, string $campo, ?string $delete = null, ?string $update = null): self
    {
        $this->relacionado[] = [
            $tabela,
            $this->dado['campo'],
            $campo,
            $this->setarAcaoRelacionamento($delete),
            $this->setarAcaoRelacionamento($update)
        ];
        $this->tamanho(9);
        return $this;
    }

    /**
     * @param  string|null $tipo
     * @return string
     */
    private function setarAcaoRelacionamento(?string $tipo): string
    {
        $tipo = is_string($tipo) ? mb_strtoupper($tipo, 'UTF-8') : null;
        if ($tipo == null || $tipo == 'CASCADE') {
            return 'CASCADE';
        } elseif ($tipo == 'RESTRICT') {
            return 'RESTRICT';
        } elseif ($tipo == 'SET NULL') {
            return 'SET NULL';
        } elseif ($tipo == 'NO ACTION') {
            return 'NO ACTION';
        }
        return $tipo;
    }

    /**
     * Seta um tamanho para os campos que precisam de um tamanho específico
     *
     * @param  int      $tamanho Tamanho do campo
     * @return DataBase
     */
    public function tamanho(int $tamanho): DataBase
    {
        $this->dado['tamanho'] = $tamanho;
        return $this;
    }
}
