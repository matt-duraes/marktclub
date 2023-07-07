<?php

namespace App\Models\Api\Automovel\Versao;

use Helpers\UploadHelper;
use Http\Request;
use ORM\Entity;

final class VersaoEntity extends Entity
{
    public string $tipo;

    protected string $ormTabela = TABELA_CARRO_MODELO;

    protected array $ormBuscar = [
        'uuid', 'titulo', 'vinculo', 'detalhe', 'cor', 'valor', 'valor_off', 'tipo', 'status'
    ];
    protected array $ormSalvar = [
        'titulo', 'detalhe', 'vinculo', 'cor', 'valor', 'valor_off', 'tipo', 'status'
    ];
    protected string $ormValidarSalvar = '
        detalhe|Detalhe|obrigatorio|vazio
        titulo|Titulo|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio
        valor|Valor|obrigatorio|vazio
    ';

    public string $uuid;

    public string $titulo;
    public string $vinculo;

    public function __construct(
        private ?Request $request = null,
    ) {
        parent::__construct();
    }
}
