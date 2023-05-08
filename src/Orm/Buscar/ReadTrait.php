<?php

namespace ORM\Buscar;

use PDO;
use stdClass;
use Erro\Excecao;
use PDOStatement;
use Order\OrderInterface;

trait ReadTrait
{
    protected function paginacaoZero()
    {
        return (object)[
            'lista' => [],
            'registro' => (object) [
                'inicio' => 0,
                'final' => 0,
                'atual' => 0,
                'total' => 0
            ],
            'pagina' => (object) [
                'total' => 0,
                'atual' => 0,
                'paginacao' => []
            ],
        ];
    }

    /**
     * Conta a quantidade de registro
     *
     * @param   array $where    Where para a busca
     * @return  int             Quantidade de registros encontrados
     * @throws  Excecao         Exceção caso ocorra um erro de PDO
     */
    protected function contar(array $where = []): int
    {
        if ($where) {
            $this->where($where);
        }

        $whereDado = $this->ormConverterCondicaoParaString($this->ormWhereDado);
        $where = !empty($whereDado) ? ' WHERE ' . $whereDado : '';

        $query = $this->ormExecute('SELECT COUNT(*) FROM `' . $this->ormTabelaAtual . '`' . $where, $this->ormCondicaoValue);
        if (!$query instanceof PDOStatement) {
            mensagemErro(
                titulo: 'Erro na contagem!',
                mensagem: 'Ocorreu um erro ao contar os registros.',
                localhost: is_string($query) ? $query : 'Ocorreu um erro ao contar os registros.'
            );
        }
        $this->ormResetarOrm();
        return $query->fetchColumn();
    }

    /**
     * Faz uma busca e monta um select da busca
     *
     * @param   string                              $indice     Indice que deve ser usado no retorno
     * @param   string                              $valor      Valor que deve ser usado no retorno
     * @param   array                               $where      Array com uma busca caso queira filtrar
     * @param   null|string|array|OrderInterface    $order      Ordem caso não queira usar a ordem padrão que é $valor ASC
     * @return  array                                           Array com a lista com o formato $indice => $valor
     */
    public function pegarSelect(
        string $indice,
        string $valor,
        array $where = [],
        null|string|array|OrderInterface $order = null,
        ?string $titulo = null
    ): array {
        $order = $order == null ? [[$valor, 'ASC']] : $order;
        $query = $this->campo([$indice, $valor])->order($order);
        if (!empty($where)) {
            $query->where($where);
        }
        $dado = $query->read();
        return montarSelect($dado, indice: $indice, valor: $valor, titulo: $titulo);
    }

    /**
     * Verifica se um registro existe
     *
     * @param array $where  Where com a condição para a busca
     * @return bool
     */
    public function existe(array $where): bool
    {
        $this->ormResetarOrm();
        $this->campo(['id'])->where($where)->limit(0, 1);
        $query = $this->ormExecute($this->ormMontarQueryString(), $this->ormCondicaoValue);
        if (!$query instanceof PDOStatement) {
            throw new Excecao(titulo: 'Erro na busca!', mensagem: is_string($query) && SISTEMA != 'PRODUCAO' ? $query : 'Ocorreu um erro ao verificar se a busca existe.');
        }
        $existe = $query->fetch() ? true : false;
        $this->ormResetarOrm();
        return $existe;
    }

    /**
     * Pega o primeiro registro da busca
     *
     * @param string        $campo          Campo que deseja pegar na requisição, caso não passe o indice, pegar o indice 0
     * @param mixed         $padrao         Padrão caso não exista o campo
     * @param string        $retorno        Tipo de retorno podendo ser object ou array
     * @return mixed
     */
    protected function primeiro(string $campo = '', $padrao = null, string $retorno = 'object')
    {
        return $this->read(0, $campo, $padrao, $retorno);
    }

    /**
     * Executa a busca no banco
     *
     * @param   null|int      $indice         Indice que quer pegar da requisição
     * @param   string        $campo          Campo que deseja pegar na requisição, caso não passe o indice, pegar o indice 0
     * @param   mixed         $padrao         Padrão caso não exista o campo
     * @param   string        $retorno        Tipo de retorno podendo ser object ou array
     * @return  mixed
     */
    protected function read(?int $indice = null, string $campo = '', $padrao = null, string $retorno = 'object')
    {
        if (!empty($campo) && is_null($indice)) {
            $indice = 0;
        }
        if (is_numeric($indice)) {
            $this->limit($indice, 1);
        }

        $busca = $this->ormExecute($this->ormMontarQueryString(), $this->ormCondicaoValue);
        if (!$busca instanceof PDOStatement) {
            throw new Excecao(titulo: 'Erro na busca!', mensagem: is_string($busca) && SISTEMA != 'PRODUCAO' ? $busca : 'Ocorreu um erro na sua busca.');
        }

        $tipoRetorno = $retorno == 'array' ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ;
        $busca->setFetchMode($tipoRetorno);
        $dado = $busca->fetchAll();

        if ($this->ormPaginacao && !$indice) {
            $dado = $this->ormRetornarPaginacao($dado);
        }

        $this->ormResetarOrm();

        if (is_int($indice) && $indice >= 0 && empty($campo)) {
            return $dado[$indice] ?? [];
        } elseif (is_int($indice) && $indice >= 0 && !empty($campo)) {
            $padrao = is_null($padrao) ? '' : $padrao;
            return $dado[$indice]->$campo ?? $padrao;
        } else {
            return $dado;
        }
    }

    /**
     * Buscar no banco usando uma string para a busca
     *
     * @param string    $query      Query para a busca
     * @param array     $valor      Valores para a query informada
     * @param string    $retorno    Tipo de retorno podendo ser object ou array
     * @return stdClass|array
     */
    protected function readTexto(string $query, array $valor = [], string $retorno = 'object'): stdClass | array
    {
        if (stristr($query, 'WHERE') && (!strstr($query, '?') && !strstr($query, ':'))) {
            throw new Excecao(titulo: 'Where incorreta', mensagem: 'Você precisa enviar o where com "?" nos valores.');
        } elseif (stristr($query, 'WHERE') && empty($valor)) {
            throw new Excecao(titulo: 'Where incorreta', mensagem: 'Você precisa enviar os valores do where como array no parâmetro "$valor".');
        }
        $query = $this->ormQueryTextoMontarString(str_replace('{{TABELA}}', '`' . $this->ormTabela . '`', $query));
        $dado = $this->ormExecute($query, $valor);
        if (!$dado instanceof PDOStatement) {
            throw new Excecao(titulo: 'Erro na busca!', mensagem: is_string($dado) && SISTEMA != 'PRODUCAO' ? $dado : 'Ocorreu um erro na sua busca.');
        }

        $tipoRetorno = $retorno == 'array' ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ;

        $dado->setFetchMode($tipoRetorno);
        return $dado->fetchAll();
    }

    private function ormQueryTextoMontarString($query)
    {
        if (!strstr($query, '?')) {
            return $query;
        }
        $lista = explode('?', $query);
        $quantidade = count($lista);
        $queryNova = '';
        for ($i = 0; $i < $quantidade; $i++) {
            if ($i < $quantidade - 1) {
                $queryNova .= $lista[$i] . ':' . $i;
                continue;
            }
            $queryNova .= $lista[$i];
        }
        return $queryNova;
    }

    /**
     * Cria um select para a busca
     *
     * @param   string        $select         Select que deseja passar
     * @return  self
     */
    protected function select(string $select = '')
    {
        $select = trim($select);
        if (!empty($select) && stristr($select, 'FROM')) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'Você não pode passar um FROM no select.');
        } elseif (!empty($select) && !preg_match('/^SELECT/', $select)) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'Você deve começar o select com "SELECT".');
        }
        $this->ormSelect =
            !empty($select) ?
            str_replace(
                'SELECT',
                'SELECT {{CAMPO}}',
                $select . ' FROM `' . $this->ormTabela . '`'
            ) :
            "SELECT {{CAMPO}} FROM `{$this->ormTabela}`";
        return $this;
    }
    /**
     * Cria um select em forma de texto, cuidado ao usá-lo
     *
     * @param   string  $select Select que deseja usar
     * @return  self
     */
    protected function selectTexto(string $select)
    {
        $select = trim($select);
        if (!empty($select) && !preg_match('/^SELECT/', $select)) {
            throw new Excecao(titulo: 'Campo incorreto!', mensagem: 'Você deve começar o select com "SELECT".');
        }
        $this->ormSelect = $select;
        return $this;
    }

    /**
     * Campos permitidos na busca
     *
     * @param   string|array    $campo      Lista com os campos que devem ser buscados podendo ser uma lista simples ["campo_1", "campo_2"] ou um array composto onde o primeiro indice é o campo e o segundo é a alias [["campo_1", "nome_campo_1"], ["campo_2", "campo_nome_2"]]
     * @param   null|string     $as         Alias padrão para o as, por exemplo, $as = usuario: campo1 vira usuario_campo1, campo2 vira usuario_campo2, etc
     * @param   null|array      $replace    Array para trocar os valores do campo, caso não seja passado, pega a propriedade _replace, passar [] para não validar
     * @return  self
     */
    protected function campo(array $campo, ?string $as = null, ?array $replace = null): self
    {
        if (!is_array($campo)) {
            throw new Excecao(
                titulo: 'Campo incorreto!',
                mensagem: 'Lista de campos da busca com formato inválido.'
            );
        }

        $as = !empty($as) && str_contains($as, '!') ? substr($as, 1) : $as;

        $replace = is_array($replace) ? array_flip($replace) : array_flip($this->ormReplace);
        $lista = [];
        foreach ($campo as $val) {
            if (is_string($val)) {
                if ($replace && array_key_exists($val, $replace)) {
                    $val = $replace[$val];
                }
                $lista[] = $this->setarStringCampo($val, $as);
                continue;
            } elseif (is_array($val) && count($val) == 2) {
                $lista[] = $this->setarStringCampo($val[0], $val[1]);
                continue;
            }
            throw new Excecao(
                titulo: 'Campo incorreto!',
                mensagem: 'Lista de campos da busca com formato inválido.'
            );
        }
        $this->ormCampo[] = implode(', ', $lista);
        return $this;
    }
    /**
     * Faz um count de um campo da tabela
     *
     * @param  string       $campo  Campo da tabela que deseja fazer o count
     * @param  null|string  $as     Alias para campo, começar com ! para ser exatamente esse nome ou vazio
     *                              para ser o próprio campo
     */
    public function count(string $campo, string $as = null): self
    {
        $as = !empty($as) ? $this->setarValorAlias($campo, $as) : implode('_', explode('.', $campo));
        $campo = $this->setarStringCampo($campo, '');
        $this->ormCampo[] = 'count(' . $campo . ')' . $as;
        return $this;
    }
    private function setarStringCampo($campo, $as)
    {
        $as = $this->setarValorAlias($campo, $as);
        if (!str_contains($campo, '.')) {
            return '`' . $this->ormTabelaAtual . '`.`' . $campo . '`' . $as;
        }
        $explode = explode('.', $campo);
        $campo = $explode[0];
        unset($explode[0]);
        return 'JSON_EXTRACT(`' . $this->ormTabelaAtual . '`.`'
            . $campo . '`, \'$.' . implode('.', $explode) . '\')' . $as;
    }
    private function setarValorAlias(string $campo, ?string $as)
    {
        if (empty($as)) {
            return '';
        } elseif (str_contains($as, '!')) {
            return ' AS ' . substr($as, 1);
        }
        $campo = implode('_', explode('.', $campo));
        return ' AS ' . $as . '_' . $campo;
    }

    /**
     * Campos em texto simples, muito cuidado ao usá-lo
     *
     * @param   string   $campo  Campos no formato: campo_1, campo_2
     * @return  self
     */
    protected function campoTexto(string $campo)
    {
        $this->ormCampo[] = $campo;
        return $this;
    }

    private function ormMontarQueryString(bool $paginacao = false): string
    {
        $select = !empty($this->ormSelect) ? $this->ormSelect : "SELECT {{CAMPO}} FROM `{$this->ormTabela}`";
        $campo = !empty($this->ormCampo) ? implode(', ', $this->ormCampo) : '*';
        $whereDado = $this->ormConverterCondicaoParaString($this->ormWhereDado);
        $where = !empty($whereDado) ? 'WHERE ' . $whereDado : '';
        $order = !empty($this->ormOrder) ? 'ORDER BY ' . implode(', ', $this->ormOrder) : '';
        $group = !empty($this->ormGroup) ? 'GROUP BY ' . $this->ormGroup : '';
        $limit = !empty($this->ormLimit) ? 'LIMIT ' . $this->ormLimit : '';
        $havingDado = $this->ormConverterCondicaoParaString($this->ormHavingDado);
        $having = !empty($havingDado) ? 'HAVING ' . $havingDado : '';
        $join = !empty($this->ormJoin) ? implode(' ', $this->ormJoin) : '';

        if ($paginacao) {
            $campo = 'count(*)';
            $limit = '';
            $order = '';
            $group = '';
            $having = '';
        }

        $query = str_replace(
            ['{{CAMPO}}'],
            [$campo],
            $select . ' ' . $join . ' ' . $where . ' ' . $having . ' ' . $group . ' ' . $order . ' ' . $limit
        );
        return $query;
    }

    private function ormRetornarPaginacao(array $lista)
    {
        $busca = $this->ormExecute($this->ormMontarQueryString(true), $this->ormCondicaoValue);
        if (!$busca instanceof PDOStatement) {
            throw new Excecao(titulo: 'Erro na busca!', mensagem: is_string($busca) && SISTEMA != 'PRODUCAO' ? $busca : 'Ocorreu um erro na sua busca.');
        }
        $total = $busca->fetchColumn();

        $paginaTotal = $total == 0 ? 0 : ceil($total / $this->ormLimitQuantidade);
        $paginaAtual = $total == 0 ? 0 : $this->ormLimitPagina;
        $paginaQuantidade = $total == 0 ? 0 : $this->ormLimitQuantidade;

        $paginacao = [];
        if ($paginaTotal <= 7) {
            for ($i = 1; $i <= $paginaTotal; ++$i) {
                $paginacao[] = $i;
            }
        } else {
            if ($paginaAtual + 3 > $paginaTotal) {
                for ($i = 0; $i < 7; ++$i) {
                    $paginacao[] = $paginaTotal - $i;
                }
                $paginacao = array_reverse($paginacao, false);
            } else {
                $comeco = $paginaAtual - 3 < 1 ? 1 : $paginaAtual - 3;
                for ($i = 0; $i < 7; ++$i) {
                    $paginacao[] = $comeco + $i;
                }
            }
        }

        $registroAtual = count((array) $lista);
        $registroInicio = (($paginaAtual - 1) * $paginaQuantidade) + 1;

        return (object)[
            'lista' => $lista,
            'registro' => (object) [
                'inicio' => $registroAtual == 0 ? 0 : $registroInicio,
                'final' => $registroAtual == 0 ? 0 : $registroInicio + $registroAtual - 1,
                'atual' => $registroAtual == 0 ? 0 : $registroAtual,
                'total' => (int) $total,
            ],
            'pagina' => (object) [
                'total' => $paginaTotal,
                'atual' => $paginaAtual,
                'paginacao' => $paginacao,
            ]
        ];
    }
}
