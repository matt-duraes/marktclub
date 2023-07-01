<?php

namespace App\Models\Api\IndicacaoParceiro;

use Erro\Erro;
use ORM\Entity;
use Erro\Excecao;
use Http\Request;
use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use Helpers\ValidarHelper;
use App\Classes\IndicacaoParceiro\Tipo;
use App\Classes\IndicacaoParceiro\Status;

class IndicacaoParceiroEntity extends Entity
{
    protected string $ormTabela = TABELA_MENSAGEM_INDICACAO_NOVO;

    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente',
        'parceiro', 'telefone', 'email', 'mensagem','tipo',
        'data_atualizacao', 'data_criacao', 'status',
    ];
    protected array $ormSalvar = [
        'id_admin_empresa',  'id_usuario_cliente', 'parceiro', 'telefone', 'email', 'mensagem', 'tipo'
    ];

    public int $idUsuario;
    public int $idEmpresa;
    public string $parceiro;
    public string $mensagem;
    public Telefone $telefone;
    public Status $status;
    public Email $email;
    public Tipo $tipo;

    public function __construct(
        private readonly ?Request $request = null
    ) {

        parent::__construct();
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        $this->idUsuario =  1;

    }

    /**
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $this->validarRequest();
        $this->id_admin_empresa = $this->idEmpresa;
        $this->id_usuario_cliente = $this->idUsuario;
        $this->status = new Status(Status::CRIADA);
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $validarHelper = new ValidarHelper();

        $this->tipo = new Tipo($this->tipo ?? '');
        $this->parceiro = $this->parceiro ?? '';
        $this->telefone = new Telefone($this->telefone ?? '');
        $this->email = new Email($this->email ?? '');

        $validarHelper
            ->valor($this->parceiro, 'Parceiro', 'Digite o nome do parceiro que deseja indicar.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->telefone, 'Telefone', 'Digite o telefone do parceiro que deseja indicar.')
            ->obrigatorio()
            ->valido()
            ->valor($this->email, 'Email', 'Digite o email do parceiro que deseja indicar.')
            ->obrigatorio()
            ->valido();

    }


}
