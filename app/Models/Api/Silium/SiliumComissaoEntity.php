<?php

namespace App\Models\Api\Silium;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\Entity;

class SiliumComissaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public int|float $comissao_usuario;
    public Data $data_compra;
    public string $moeda;
    public int $status;
    protected string $ormTabela = TABELA_SILIUM_COMISSAO;
    protected array $ormBuscar = [
        'comissao_usuario', 'data_compra', 'moeda', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario'       => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'comissao_usuario', 'data_compra', 'moeda', 'status'
    ];
    protected ?int $idEmpresa;
    protected ?int $idUsuario;

    /**
     * @param Request|null $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }
}
