<?php

namespace App\Models\Api\Siliium;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Http\Request;
use Modules\Data;
use ORM\Entity;

class SiliumComissaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SILIUM_COMISSAO;
    protected array $ormBuscar = [
        'comissao_usuario', 'data_compra', 'moeda', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => 'idEmpresa',
        'id_usuario'       => 'idUsuario'
    ];
    protected array $ormSalvar = [
        'comissao_usuario', 'data_compra', 'moeda', 'status'
    ];
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
    protected int|float $comissao_usuario;
    protected Data $data_compra;
    protected string $moeda;
    //protected Status $status;

    /**
     * @param  Request|null  $request
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }
}
