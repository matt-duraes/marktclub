<?php

namespace App\Models\Api\Demanda;

use ORM\Entity;
use PhpParser\Node\Stmt\TryCatch;
use App\Classes\DemandaTarefa\Tipo;
use App\Classes\DemandaTarefa\Status;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class TarefaEntity extends Entity
{
    protected string $_tabela = TABELA_DEMANDA_TAREFA;
    protected array $_buscar = [
        'hora_producao_estimada', 'titulo', 'texto', 'status', 'tipo', 'id_usuario_equipe'
    ];

    protected array $_insert = [
        'id_demanda_dado'
    ];
    protected array $_salvar = [
        'id_admin_equipe', 'hora_producao_estimada', 'id_usuario_equipe', 'status', 'titulo', 'texto', 'tipo'
    ];
    protected string $_validarInsert = '
        titulo|Titulo|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        tipo|Tipo|valido
        hora_producao_estimada|Tempo de produção|int
    ';

    public Status $status;
    public int $id_demanda_dado;
    public int $id_usuario_equipe;

    public function __construct(
        private ?string $demanda = null,
        public ?string $titulo = null,
        public ?string $texto = null,
        public ?Tipo $tipo = null,
        public ?int $hora_producao_estimada = null,
        public null|string|EquipeEntity $equipe = null
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        if (!empty($this->id_usuario_equipe)) {
            $this->pegarUsuarioEquipe($this->id_usuario_equipe);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->id_demanda_dado = $this->pegarIdDemanda();
        $this->status = new Status('backlog');
        if (!empty($this->equipe)) {
            $this->pegarUsuarioEquipe($this->equipe);
            $this->id_usuario_equipe = $this->equipe->get('id');
        }
    }

    private function pegarIdDemanda()
    {
        try {
            $Demanda = new DemandaEntity();
            $Demanda->id($this->demanda);
            return $Demanda->get('id');
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi encontrado nenhuma demanda pelo id enviado.', status: 404);
        }
    }
    private function pegarUsuarioEquipe($id)
    {
        try {
            $Equipe = new EquipeEntity(validarToken: false);
            is_int($id) ? $Equipe->_id($id) : $Equipe->id($id);
            $this->equipe = $Equipe;
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi encontrado nenhum usuário pelo id enviado.', status: 404);
        }
    }
}
