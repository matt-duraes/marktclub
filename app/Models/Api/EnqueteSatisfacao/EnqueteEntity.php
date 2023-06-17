<?php

namespace App\Models\Api\EnqueteSatisfacao;

use App\Models\Api\UsuarioCliente\ClienteEntity;
use Http\Request;
use ORM\Entity;
use Helpers\ValidarHelper;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Suporte;
use App\Classes\EnqueteSatisfacao\Atendimento;

class EnqueteEntity extends Entity
{
    protected string $idEmpresa;
    protected string $idUsuario;
    protected Navegar $navegar;
    protected Procura $procura;
    protected Suporte $suporte;
    protected Atendimento $atendimento;
    protected array  $sistemas;
    protected string $comentario;
    protected Status $status;
    protected string $ormTabela = TABELA_ENQUETE;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario_equipe' => '->idUsuario',
        'status'     => 1
    ];
    protected array $ormBuscar = [
        'navegar', 'procura', 'suporte', 'comentario',
        'atendimento', 'sistemas', 'status', 'data_criacao'
    ];
    protected array $ormSalvar = [
        'navegar', 'procura', 'suporte', 'comentario',
        'atendimento', 'sistemas', 'status'
    ];
    protected string $ormValidarSalvar = '
        navegar|Navegar|obrigatorio|vazio
        procura|Procura|vazio|valido
        suporte|Suporte|vazio|valido
        atendimento|Atendimento|valido
    ';


    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
    }

    /**
     * @return void
     */
    public function regraInsert(): void
    {
        $this->validarRequest();
        //TODO - inserir aqui usuário
        $this->setarUsuarioSeExistir('5595203c-f7b1-4211-9981-bf09eb236b35');

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
        if (!$this->navegar->indice()) {
            mensagemErro('Dado inválido!', 'O campo navegar não é um valor válido.');
        } elseif (!$this->procura->indice()) {
            mensagemErro('Dado inválido!', 'O campo procura não é um valor válido.');
        } elseif (!$this->suporte->indice()) {
            mensagemErro('Dado inválido!', 'O campo suporte não é um valor válido.');
        } elseif (!$this->atendimento->indice()) {
            mensagemErro('Dado inválido!', 'O campo atendimento não é um valor válido.');
        } elseif (!$this->sistemas) {
            mensagemErro('Dado inválido!', 'O campo sistema não é um valor válido.');
        }
    }
}
