<?php

namespace App\Models\Api\Parceiro\Turismo;

use ORM\Entity;
use Modules\Data;
use Modules\ArquivoPrivado;
use App\Classes\Geral\Status;

final class TurismoEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_TURISMO;
    protected array $ormSalvar = [
        'titulo', 'texto', 'imagem', 'data_inicio', 'data_final', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'imagem', 'data_inicio', 'data_final', 'status'
    ];
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        imagem|Imagem|obrigatorio|vazio
        data_inicio|Data de início|obrigatorio|vazio|valido
        data_final|Data final|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo;
    public string $texto;
    public ArquivoPrivado $imagem;
    public Data $data_inicio;
    public Data $data_final;
    public Status $status;
}
