<?php

namespace ORM\Trait;

use PDO;

trait OrmPropriedadeTrait
{
    private ?PDO $ormDB;
    protected string $ormTipo = 'service';
    protected string $ormTabela;
    private string $ormTabelaAtual;
    protected array $ormWherePadrao = [];
    private array $campoReplace = [];
    private int $ormUltimoId = 0;
    private array $ormDado;
    private int $ormCondicaoNumero = 0;
    private array $ormWhereDado = [];
    private array $ormHavingDado = [];
    private array $ormCondicaoValue = [];
    private array $ormOrder = [];
    private string $ormLimit = '';
    private int $ormLimitPagina = 1;
    private int $ormLimitQuantidade = 20;
    private bool $ormPaginacao = false;
    private string $ormSelect = '';
    private string $ormGroup = '';
    private array $ormCampo = [];
    private array $ormJoin = [];
    private array $ormCondicao = [
        '>', '>=', '=', '<>', '<', '<=', '!=', 'like', 'notlike',
        'null', 'isnull', 'notnull', '!null', 'isnotnull', 'in',
        'notin', 'between', 'notbetween', 'json'
    ];
    private array $ormCondicaoNull = ['null', 'isnull', 'notnull', '!null', 'isnotnull'];
    private bool $ormRollback = false;
    private array $ormArquivoSalvar = [];
}
