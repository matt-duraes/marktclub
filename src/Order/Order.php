<?php

namespace Order;

use stdClass;

abstract class Order implements OrderInterface
{
    public const MAIS_NOVO = 'mais-novo';
    public const MAIS_VELHO = 'mais-velho';
    public const RANDOMICO = 'randomico';
    public const STATUS = 'status';

    private string $tabela;
    private array $lista;
    protected ?string $valor;
    private string $padrao = '';

    // doc
    /**
     * Pega ou seta o valor manualmente da ordem
     *
     * @param  string $valor Valor da ordem
     * @return self
     */
    public function valor(?string $valor = null): self|string|null
    {
        if (is_null($valor)) {
            return $this->valor;
        }
        $this->valor = $valor;
        return $this;
    }

    // doc
    /**
     * Qual tabela pertence a ordem
     *
     * @param  string $tabela Nome da tabela que deseja usar
     * @return self
     */
    public function tabela(string $tabela): self
    {
        $this->tabela = $tabela;
        return $this;
    }

    // doc
    /**
     * Pega um array com a lista de valores válidos no formato indice => nome
     *
     * @param  null|string $titulo Título para ficar no primeiro valor do array tendo o indice vazio: "" => $titulo
     * @return array
     */
    public function select(?string $titulo = null): array
    {
        $retorno = [];
        if (!empty($titulo)) {
            $retorno[''] = $titulo;
        }
        foreach ($this->lista as $r) {
            $retorno[$r['indice']] = $r['nome'];
        }
        return $retorno;
    }

    // doc
    /**
     * Cria uma ordem passando os campos para a tabela atual
     *
     * @param  string      $indice  Indice publico que deseja usar para a ordenação. Ex: nome-mais-novo
     * @param  string      $nome    Nome do campo para ser mostrado para o usuário normal. Ex: Nome mais novo
     * @param  string      $campo   Campo do banco que deseja usar para ordernar. Ex: Nome
     * @param  string      $direcao Qual direção será usada podendo ser ASC ou DESC
     * @param  null|string $tabela  Tabela que vai usar, se null, pega a tabela atual
     * @return self
     */
    public function campo(string $indice, string $nome, string $campo, string $direcao, ?string $tabela = null): self
    {
        if (!in_array($direcao, ['ASC', 'DESC'])) {
            mensagemErro('Campo inválido!', 'Você deve passar um valor válido para a direção da ordem.');
        }

        $tabela = !empty($tabela) ? $tabela : $this->tabela;
        $this->lista[$indice] = [
            'order'  => '`' . $tabela . '`.`' . $campo . '` ' . $direcao,
            'indice' => $indice,
            'nome'   => $nome,
            'campo'  => $campo,
            'icone'  => $direcao == 'ASC' ? '>' : '<'
        ];

        return $this;
    }

    // doc
    /**
     * Cria uma ordem passando a string ORDER em forma de texto
     *
     * @param  string $indice Indice publico que deseja usar para a ordenação. Ex: nome-mais-novo
     * @param  string $nome   Nome do campo para ser mostrado para o usuário normal. Ex: Nome mais novo
     * @param  string $campo  Campo do banco que deseja usar para ordernar. Ex: Nome
     * @param  string $ordem  A ordem que deseja usar. Ex: `tabela`.`nome` ASC
     * @param  string $icone  Icone que deseja usar podendo ser < ou >
     * @return self
     */
    public function campoTexto(string $indice, string $nome, string $campo, string $ordem, ?string $icone): self
    {
        $this->lista[$indice] = [
            'order'  => $ordem,
            'indice' => $indice,
            'nome'   => $nome,
            'campo'  => $campo,
            'icone'  => in_array($icone, ['<', '>']) ? $icone : ''
        ];
        return $this;
    }

    // doc
    /**
     * Buscar randomicamente
     *
     * @return self
     */
    public function rand()
    {
        $this->lista['randomico'] = [
            'order'  => 'RAND()',
            'indice' => 'randomico',
            'nome'   => 'Randômico',
            'campo'  => '',
            'icone'  => ''
        ];
        return $this;
    }

    // doc
    /**
     * Buscar pelo registro mais novo
     *
     * @return self
     */
    public function maisNovo(): self
    {
        $this->campo('mais-novo', 'Mais novos', 'id', 'DESC');
        return $this;
    }

    // doc
    /**
     * Buscar pelo registro mais antigo
     *
     * @return self
     */
    public function maisVelho(): self
    {
        $this->campo('mais-velho', 'Mais velhos', 'id', 'ASC');
        return $this;
    }

    // doc
    /**
     * Buscar pelo Status ascendente
     *
     * @return self
     */
    public function status(): self
    {
        $this->campo('status', 'Por status', 'status', 'ASC');
        return $this;
    }

    // doc
    /**
     * Buscar por um indice ascendente
     *
     * @param  string $indice Indice publico que deseja usar para a ordenação. Ex: nome-mais-novo
     * @param  string $nome   Nome do campo para ser mostrado para o usuário normal. Ex: Nome mais novo
     * @param  string $campo  Campo do banco que deseja usar para ordernar. Ex: Nome
     * @return self
     */
    public function asc(string $indice, string $nome, string $campo): self
    {
        $this->campo($indice, $nome, $campo, 'ASC');
        return $this;
    }

    // doc
    /**
     * Buscar por um indice descendente
     *
     * @param  string $indice Indice publico que deseja usar para a ordenação. Ex: nome-mais-novo
     * @param  string $nome   Nome do campo para ser mostrado para o usuário normal. Ex: Nome mais novo
     * @param  string $campo  Campo do banco que deseja usar para ordernar. Ex: Nome
     * @return self
     */
    public function desc($indice, $nome, $campo): self
    {
        $this->campo($indice, $nome, $campo, 'DESC');
        return $this;
    }

    // doc
    /**
     * Pega a ordem atual selecionada
     *
     * @return string Retorna a ordem selecionada ou id DESC como padrão
     */
    public function ordem(): string
    {
        $padrao = '`' . $this->tabela . '`.`id` DESC';
        $valido = !$this->vazio() && $this->valido();
        if (!$valido && empty($this->padrao)) {
            return $padrao;
        } elseif (!$valido) {
            return $this->lista[$this->padrao]['order'] ?? $padrao;
        }
        return $this->lista[$this->valor]['order'] ?? $this->lista[$this->padrao]['order'] ?? $padrao;
    }

    /**
     * Seta qual será o indice padrão
     *
     * @param string $indice Qual o indice padrão para quando não passar nada
     */
    public function padrao(string $indice): self
    {
        $this->padrao = $indice;
        return $this;
    }

    public function listaParaPainel(): stdClass
    {
        $lista = $this->lista;

        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = (object)[
                'hash'   => base64Encode($r['indice'], true),
                'titulo' => $r['nome'],
                'icone'  => $r['icone'],
                'campo'  => $r['campo'],
                'indice' => $r['indice']
            ];
        }

        return (object)$retorno;
    }

    // doc
    /**
     * Verifica se a valor da ordem está vazio
     *
     * @return bool
     */
    public function vazio(): bool
    {
        return empty($this->valor);
    }

    // doc
    /**
     * Verifica se a valor da ordem é válido
     *
     * @return bool
     */
    public function valido(): bool
    {
        return !empty($this->valor) && array_key_exists($this->valor, $this->lista);
    }
}
