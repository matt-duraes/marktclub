<?php

namespace App\Models\Api\Votacao\Dado;

use ORM\Entity;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DadoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_VOTACAO_DADO;
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio|valido
        voto_unico|Voto único|valido
        indentificar_usuario|Identificar usuário|valido
        data_inicio|Data de início da publicação|obrigatorio|vazio|valido
        data_final|Data final da publicação|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmrpesa'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'tipo', 'voto_unico', 'indentificar_usuario', 'data_inicio', 'data_final', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'tipo', 'voto_unico', 'indentificar_usuario', 'data_inicio', 'data_final', 'status'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
