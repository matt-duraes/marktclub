<?php

namespace App\Models\Api\Automovel\Modelo;

use Helpers\UploadHelper;
use Http\Request;
use ORM\Entity;

final class ModeloEntity extends Entity
{
    public string $tipo;
    public UploadHelper|string $imagem;

    protected string $ormTabela = TABELA_CARRO;

    protected array $ormBuscar = [
        'uuid', 'montadora', 'titulo', 'imagem', 'url', 'tipo'
    ];
    protected array $ormSalvar = [
        'montadora', 'titulo', 'imagem', 'url', 'tipo'
    ];
    protected string $ormValidarSalvar = '
        montadora|Montadora|obrigatorio|vazio
        imagem|Imagem|obrigatorio|vazio
        url|Url|obrigatorio|vazio
        titulo|Titulo|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio
    ';

    public function __construct(
        private ?Request $request = null,
    ) {
        parent::__construct();
    }
}
