<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Status;
use App\Models\Api\Demanda\Trait\EquipeTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class DemandaEntity extends Entity
{
    use EquipeTrait;
    use EmpresaTrait;

    protected string $_tabela = TABELA_DEMANDA_DADO;
    protected array $_buscar = [
        'id_admin_empresa', 'id_usuario_equipe', 'titulo', 'tipo', 'status', 'seguindo'
    ];
    protected array $_insert = [
        'id_admin_empresa', 'id_usuario_equipe', 'titulo', 'tipo', 'status'
    ];
    protected string $_validarSalvar = '
        titulo|Título|obrigatorio|vazio
        tipo|Tipo|vazio|valido
        status|Status|vazio|valido
    ';

    public array $dono = [];
    public array $equipe = [];
    public array $seguindo = [];
    public array $tarefa = [];
    public bool $estou_seguindo = false;
    public bool $sou_dono = false;
    public bool $sou_dev = false;

    public int $id_admin_empresa;
    public Status $status;
    protected int $id_usuario_equipe;

    /**
     * @param   null|string         $titulo     Título da demanda que deseja salvar
     * @param   null|string|array   $empresa    UUID da empresa dona da demanda e um array ao buscar
     * @param   null|Tipo           $tipo       Típo da demanda que deseja salvar
     */
    public function __construct(
        public ?string $titulo = null,
        public null|string|array $empresa = null,
        public ?Tipo $tipo = null
    ) {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR
    |--------------------------------------------------------------------------
    */
    protected function regraPosBuscar()
    {
        $this->dono = (array)$this->pegarUsuarioEquipe($this->id_usuario_equipe);
        $this->empresa = $this->pegarEmpresa($this->id_admin_empresa);
        $this->seguindo = $this->montarSeguidores();
        $this->tarefa = (new TarefaModel($this))->pegarListaTarefa();
        $this->equipe = $this->montarEquipe();
        $this->estou_seguindo = $this->verificarSeEstouSeguindo();
        $this->sou_dev = $this->verificarSeSouDev();
        $this->sou_dono = $this->verificarSeSouDono();
    }

    private function montarSeguidores(): array
    {
        $seguindo = $this->seguindo;
        $lista = [];
        foreach ($seguindo as $usuario) {
            $lista[$usuario] = $this->pegarUsuarioEquipe($usuario);
        }
        return array_values($lista);
    }

    private function montarEquipe()
    {
        $equipe = [];
        foreach ($this->tarefa as $r) {
            if (empty($r->dev->id)) {
                continue;
            }
            $equipe[$r->dev->id] = $r->dev;
        }
        return array_values($equipe);
    }

    private function verificarSeEstouSeguindo(): bool
    {
        $Equipe = TOKEN['usuario'];
        if (!($Equipe instanceof EquipeEntity)) {
            return false;
        }

        $id = $Equipe->get('id');
        if ($this->id_usuario_equipe == $id) {
            return true;
        }

        $uuid = $Equipe->id;
        foreach ($this->equipe as $r) {
            if ($uuid == $r->id) {
                return true;
            }
        }

        foreach ($this->seguindo as $r) {
            if ($uuid == $r->id) {
                return true;
            }
        }
        return false;
    }

    private function verificarSeSouDev()
    {
        $Equipe = TOKEN['usuario'];
        if (!($Equipe instanceof EquipeEntity)) {
            return false;
        }

        $uuid = $Equipe->id;
        foreach ($this->equipe as $r) {
            if ($uuid == $r->id) {
                return true;
            }
        }
        return false;
    }
    private function verificarSeSouDono()
    {
        $Equipe = TOKEN['usuario'];
        if (!($Equipe instanceof EquipeEntity)) {
            return false;
        }
        return $this->id_usuario_equipe == $Equipe->get('id');
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->status = new Status('nova');
        $this->id_usuario_equipe = TOKEN['usuario']->get('id');
        $this->pegarIdEmpresa();
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
