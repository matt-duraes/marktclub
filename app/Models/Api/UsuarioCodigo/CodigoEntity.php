<?php

namespace App\Models\Api\UsuarioCodigo;

use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\Entity;

class CodigoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $empresa;
    public string $subempresa;
    public string $codigo;
    public Status $status;
    protected string $ormTabela = TABELA_USUARIO_CLUBE_CODIGO;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_admin_subempresa', 'codigo', 'data_criacao',
        'data_atualizacao', 'status'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa'    => '->idEmpresa',
        'id_admin_subempresa' => '->idSubempresa',
        'codigo', 'status'
    ];
    protected int $id_admin_empresa;
    protected int $id_admin_subempresa;
    private OrmHelper $ormHelper;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarEmpresa();
        $this->pegarSubempresa();
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        if (!empty($this->empresa)) {
            $this->setarEmpresa();
        }
        if (!empty($this->subempresa)) {
            $this->setarSubempresa();
        }
        $this->validarCampoDuplicado('codigo', $this->codigo);
    }

    /**
     * @throws Excecao
     */
    private function setarEmpresa(): void
    {
        $idEmpresa = $this->ormHelper->pegarIdPeloUuid($this->empresa);

        if (empty($idEmpresa)) {
            mensagemErro(
                'Campo inválido!',
                'Não foi possível vincular a empresa selecionada.',
                localhost: 'Não achou há empresa no banco'
            );
        }
        $this->idEmpresa = $idEmpresa;
    }

    /**
     * @throws Excecao
     */
    private function setarSubempresa(): void
    {
        $idSubempresa = $this->ormHelper->pegarIdPeloUuid($this->subempresa);

        if (empty($idSubempresa)) {
            mensagemErro(
                'Campo inválido!',
                'Não foi possível vincular a subempresa selecionada.',
                localhost: 'Não achou há subempresa no banco'
            );
        }
        $this->idSubempresa = $idSubempresa;
    }

    private function pegarEmpresa(): void
    {
        $uuidEmpresa = $this->ormHelper->pegarUuidPeloId($this->id_admin_empresa);
        $this->empresa = $uuidEmpresa;
    }

    private function pegarSubempresa(): void
    {
        $uuidSubempresa = $this->ormHelper->pegarUuidPeloId($this->id_admin_subempresa);

        if (empty($uuidSubempresa)) {
            return;
        }
        $this->subempresa = $uuidSubempresa;
    }
}
