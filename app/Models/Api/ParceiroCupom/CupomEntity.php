<?php

namespace App\Models\Api\ParceiroCupom;

use App\Classes\ParceiroCupom\Status;
use App\Classes\ParceiroCupom\Tipo;
use Modules\Data;
use ORM\Entity;

class CupomEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_CUPOM;
    protected array $ormBuscar = [
        'titulo', 'tipo', 'texto', 'data_validade', 'cupom', 'link', 'imagem',
        'status', 'data_criacao'
    ];
    protected array $ormSalvar = [
        'status'
    ];
    protected string $ormValidarSalvar = '
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo;
    public Tipo $tipo;
    public string $texto;
    public Data $data_validade;
    public string $cupom;
    public string $link;
    public string $imagem;
    public Status $status;
}
