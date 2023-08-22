<?php

namespace App\Models\Api\SolicitacaoChequeBonus;

use ORM\Entity;
use Modules\Cpf;
use Modules\Data;
use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;
use App\Classes\Solicitacao\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\UsuarioCliente\GrauParentesco;

final class ChequeBonusEntity extends Entity
{
    protected string $ormTabela = TABELA_SOLICITACAO_CHEQUE_BONUS;
    public string $automovel;
    public TipoUsuario $tipo_usuario;
    public Nome $nome;
    public Email $email_pessoal;
    public Telefone $telefone_celular;
    public EstadoCivil $estado_civil;
    public string $rg;
    public Data $data_nascimento;
    public EnderecoCep $endereco_cep;
    public string $endereco_logradouro;
    public string $endereco_numero;
    public string $endereco_complemento;
    public string $endereco_cidade;
    public EnderecoEstado $endereco_estado;
    public Nome $dependente_nome;
    public Email $dependente_email_pessoal;
    public string $dependente_rg;
    public Cpf $dependente_documento;
    public GrauParentesco $dependente_grau_parentesco;
    public Data $dependente_data_nascimento;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
    }
}
