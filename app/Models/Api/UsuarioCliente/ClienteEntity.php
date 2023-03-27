<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\Entity;
use Http\Request;
use App\Models\Api\Painel\ConfiguracaoEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioCliente\Trait\CampoUnicoTrait;
use App\Models\Api\UsuarioCliente\Trait\EntityBuscarTrait;
use App\Models\Api\UsuarioCliente\Trait\EntityInsertTrait;
use App\Models\Api\UsuarioCliente\Trait\EntitySalvarTrait;
use App\Models\Api\UsuarioCliente\Trait\EntityUpdateTrait;
use App\Models\Api\UsuarioCliente\Trait\PropriedadeEntityTrait;

final class ClienteEntity extends Entity
{
    use PropriedadeEntityTrait;
    use ValidarEmpresaTrait;
    use CampoUnicoTrait;
    use EntityBuscarTrait;
    use EntityInsertTrait;
    use EntitySalvarTrait;
    use EntityUpdateTrait;

    protected array $_salvar = [
        'documento' => '->cpf',
        'sexo' => '->genero',
        'telefone_celular' => '->telefone_pessoal',
        'telefone_fixo' => '->telefone_trabalho',
        'aniversario' => '->data_nascimento',
        'uf' => '->endereco_estado',
        'cidade' => '->endereco_cidade',
        'salt' => '->senha',
        'trabalho_orgao' => '->trabalho_empresa',
        'siape', 'nome', 'email_trabalho', 'email_pessoal', 'email_funcional', 'estado_civil', 'mensagem',
        'status', 'matricula', 'primeiro_acesso', 'mudar_senha', 'endereco_cep', 'endereco_logradouro',
        'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'situacao', 'trabalho_cargo',
        'tipo_pagamento', 'trabalho_data_inicio', 'grupo', 'federacao'
    ];
    protected array $_insert = [
        'empresa' => '->idEmpresa',
        'cod', 'tipo'
    ];
    protected array $_buscar = [
        'cpf' => 'documento',
        'rg' => 'documento_rg',
        'email' => ['email_trabalho', 'email_pessoal'],
        'telefone_pessoal' => 'telefone_celular',
        'telefone_trabalho' => 'telefone_fixo',
        'genero' => 'sexo',
        'data_nascimento' => 'aniversario',
        'id_admin_empresa' => 'empresa',
        'endereco_estado' => 'uf',
        'endereco_cidade' => 'cidade',
        'trabalho_empresa' => 'trabalho_orgao',
        'senha' => 'salt',
        'origem' => 'lead_origem',
        'lead' => 'usuario_lead',
        'nome', 'siape', 'email_trabalho', 'email_pessoal', 'email_funcional', 'status', 'estado_civil',
        'matricula', 'primeiro_acesso', 'mudar_senha', 'data_criacao', 'data_atualizacao', 'endereco_cep',
        'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'situacao',
        'trabalho_cargo', 'tipo_pagamento', 'trabalho_data_inicio', 'mensagem', 'grupo', 'tipo', 'federacao'
    ];
    protected string $_validarSalvar = '
        documento|CPF|cpf
        genero|Gênero|valido
        data_nascimento|Data Nascimento|dataDate
        email_trabalho|E-mail de trabalho|email
        email_pessoal|E-mail pessoal|email
        email_funcional|E-mail funcional|email
        telefone_pessoal|Telefone pessoal|telefone
        telefone_trabalho|Telefone de trabalho|telefone
        trabalho_empresa|Empresa que trabalha|valido
        trabalho_cargo|Cargo na empresa|valido
        tipo_pagamento|Tipo de pagamento|valido
        senha|Senha|senha
        status|Status|valido
    ';
    protected array $_retornoPadrao = ['id', 'nome', 'cpf'];

    protected string $_tabela = TABELA_USUARIO_NOVO;

    /**
     * @param   null|Request    $request        Request para salvar um novo usuário
     * @param   bool            $validarToken   Se vai validar o token e a empresa
     */
    public function __construct(
        private ?Request $request = null,
        private bool $validarToken = true
    ) {
        parent::__construct();
        if (!$validarToken) {
            return;
        }

        $this->validarEmpresa('empresa');
        $this->pegarCampoObrigatorio();
    }
    private function pegarCampoObrigatorio()
    {
        try {
            $Config = new ConfiguracaoEntity();
            $this->campoObrigatorio = $Config->campo_obrigatorio['usuario_cliente'] ?? [];
        } catch (\Throwable) {
            $this->campoObrigatorio = ["cpf", "email", "status"];
        }
    }

    public function getId()
    {
        return $this->prop('id');
    }

    public function getCpf()
    {
        return $this->prop('documento');
    }

    public function setEmpresa($valor)
    {
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->id($valor, mensagem: 'Empresa enviada não foi encontrada.');
        $this->setarIdEmpresaManual($this->Empresa->get('id'));
    }
}
