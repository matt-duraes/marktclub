<?php

namespace App\Models\Api\IndicacaoParceiro;

use App\Classes\IndicacaoParceiro\Status;
use Erro\Erro;
use Erro\Excecao;
use Http\Request;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class IndicacaoParceiroEntity extends Entity
{
    protected string $ormTabela = TABELA_MENSAGEM_INDICACAO_NOVO;

    protected array $ormSalvar = [
        'id_admin_empresa',  'id_usuario_cliente', 'parceiro', 'telefone', 'email', 'mensagem'
    ];

    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public string $mensagem;
    public int $idUsuario;
    public int $idEmpresa;
    public Status $status;

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
        $this->tipo = 2;
        $this->id_admin_empresa = $this->idEmpresa;
        $this->id_usuario_cliente = $this->idUsuario;
        $this->status = new Status(Status::CRIADA);
        unset($this->url);
    }

}
