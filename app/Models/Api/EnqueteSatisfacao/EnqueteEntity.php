<?php

namespace App\Models\Api\EnqueteSatisfacao;

use App\Classes\EnqueteSatisfacao\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Http\Request;
use ORM\Entity;
use Helpers\ValidarHelper;

class EnqueteEntity extends Entity
{
    protected string $ormTabela = TABELA_ENQUETE;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario_equipe' => '->idUsuario',
        'status'     => 1
    ];
    protected array $ormBuscar = [
        'id_usuario_equipe', 'status', 'data_criacao'
    ];
    protected array $ormSalvar = [
        'navegar', 'procura', 'suporte', 'comentario', 'atendimento', 'sistemas', 'tipo', 'status'
    ];

    protected string $idEmpresa;
    protected string $idUsuario;
    protected string $navegar;
    protected string $procura;
    protected string $suporte;
    protected string $atendimento;
    protected array  $sistemas;
    protected string $comentario;
    protected Status $status;


    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        //TODO - inserir aqui usuário
        $this->setarUsuarioSeExistir('5595203c-f7b1-4211-9981-bf09eb236b35');
    }

    /**
     * @return void
     */
    public function regraInsert(): void
    {
        $this->validarRequest();

    }
    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function setarUsuarioSeExistir(?string $usuario)
    {
        if (empty($usuario)) {
            return;
        }

        $cliente = new ClienteEntity(validarToken: false);
        $cliente->uuid($usuario, mensagem: 'Usuario buscado não foi encontrado.');
        $this->idUsuario = $cliente->get('id');
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $ValidarHelper = new ValidarHelper();

        $ValidarHelper
            ->valor($this->navegar, 'Operadora', 'A Operadora deve ser uma escolha válida.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->procura, 'Tipo', 'O Tipo de solicitação deve ser uma escolha válida.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->suporte, 'suporte', 'O suporte deve ser um número válido.')
            ->obrigatorio()
            ->vazio()
            ->vazio()
            ->valor($this->atendimento, 'atendimento', 'O atendimento deve ser um número válido.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->sistemas, 'sistemas', 'O sistemas deve ser um número válido.')
            ->obrigatorio()
            ->vazio();
    }
}
