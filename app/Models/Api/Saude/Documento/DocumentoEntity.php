<?php

namespace App\Models\Api\Saude\Documento;

use App\Classes\Geral\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use ORM\Entity;

class DocumentoEntity extends Entity
{
    public Status $status;
    protected string $ormTabela = TABELA_SAUDE_DOCUMENTO;
    protected array $ormInsert = [
        'status' => 1
    ];
    protected array $ormBuscar = [
        'id_contratacao', 'tipo_usuario', 'status'
    ];
    protected array $ormSalvar = [
        'id_contratacao', 'tipo_usuario', 'status'
    ];

    public function __construct(
        public string $id_contratacao,
        public TipoUsuario $tipo_usuario
    ) {
        parent::__construct();
    }
}
