<?php

namespace App\Models\Api\Saude;

use App\Classes\StatusGeral\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use ORM\Entity;

class DocumentoEntity extends Entity
{
    public string $id_contratacao;
    public TipoUsuario $tipo_usuario;
    public Status $status;
    protected string $ormTabela = TABELA_SAUDE_DOCUMENTO;
    protected array $ormBuscar = [
        'id_contratacao', 'tipo_usuario', 'status'
    ];
    protected array $ormSalvar = [
        'id_contratacao', 'tipo_usuario', 'status'
    ];

    public function __construct(string $id_contratacao, TipoUsuario $tipo_usuario)
    {
        parent::__construct();
        $this->id_contratacao = $id_contratacao;
        $this->tipo_usuario = $tipo_usuario;
        $this->status = new Status(Status::ATIVO);
    }
}
