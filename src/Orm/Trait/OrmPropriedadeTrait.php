<?php

namespace ORM\Trait;

use PDO;

trait OrmPropriedadeTrait
{
    private ?PDO $_db;

    protected string $_ormTipo = 'service';

    protected string $_tabela;
    private string $_tabelaAtual;

    protected array $_wherePadrao = [];

    private int $_ultimoId = 0;

    private array $_dado;

    private int $_condicaoNumero = 0;
    private array $_whereDado = [];
    private array $_havingDado = [];
    private array $_condicaoValue = [];

    private array $_order = [];

    private string $_limit = '';
    private int $_limitPagina = 1;
    private int $_limitQuantidade = 20;
    private bool $_paginacao = false;

    private string $_select = '';
    private string $_group = '';
    private array $_campo = [];
    private array $_join = [];

    private array $_condicao = [
        '>', '>=', '=', '<>', '<', '<=', '!=', 'like', 'notlike',
        'null', 'isnull', 'notnull', '!null', 'isnotnull', 'in',
        'notin', 'between', 'notbetween'
    ];
    private array $_condicaoNull = ['null', 'isnull', 'notnull', '!null', 'isnotnull'];

    private bool $_rollback = false;

    private array $_arquivoSalvar = [];
}
