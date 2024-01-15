<?php

namespace App\Models\Api\UsuarioCliente;

use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Models\Api\Painel\ConfiguracaoEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\Trait\CampoUnicoTrait;
use App\Models\Api\UsuarioCliente\Trait\EntityBuscarTrait;
use App\Models\Api\UsuarioCliente\Trait\EntityInsertTrait;
use App\Models\Api\UsuarioCliente\Trait\EntitySalvarTrait;
use App\Models\Api\UsuarioCliente\Trait\EntityUpdateTrait;
use App\Models\Api\UsuarioCliente\Trait\PropriedadeEntityTrait;
use Erro\Erro;
use Erro\Excecao;
use Http\Request;
use ORM\Entity;

final class ClienteEntity extends Entity
{
    use PropriedadeEntityTrait;
    use ValidarEmpresaTrait;
    use CampoUnicoTrait;
    use EntityBuscarTrait;
    use EntityInsertTrait;
    use EntitySalvarTrait;
    use EntityUpdateTrait;

    protected array $ormSalvar = [
        'telefone_celular' => '->telefone_pessoal',
        'telefone_fixo'    => '->telefone_trabalho',
        'salt'             => '->senha',
        'imagem'           => '->imagem_google',
        'trabalho_orgao'   => '->trabalho_empresa',
        'id_admin_subempresa', 'cpf', 'genero', 'data_nascimento', 'endereco_estado', 'endereco_cidade',
        'siape', 'nome', 'email_trabalho', 'email_pessoal', 'email_funcional', 'estado_civil', 'mensagem',
        'status', 'matricula', 'primeiro_acesso', 'mudar_senha', 'endereco_cep', 'endereco_logradouro',
        'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'situacao', 'trabalho_cargo',
        'tipo_pagamento', 'trabalho_data_inicio', 'grupo', 'federacao', 'data_termo'
    ];
    protected array $ormInsert = [
        'empresa' => '->idEmpresa',
        'cod', 'tipo', 'codigo_plano'
    ];
    protected array $ormBuscar = [
        'cpf'               => 'documento',
        'rg'                => 'documento_rg',
        'email'             => ['email_trabalho', 'email_pessoal'],
        'telefone_pessoal'  => 'telefone_celular',
        'telefone_trabalho' => 'telefone_fixo',
        'genero'            => 'sexo',
        'data_nascimento'   => 'aniversario',
        'id_admin_empresa'  => 'empresa',
        'endereco_estado'   => 'uf',
        'endereco_cidade'   => 'cidade',
        'trabalho_empresa'  => 'trabalho_orgao',
        'senha'             => 'salt',
        'origem'            => 'lead_origem',
        'lead'              => 'usuario_lead',
        'imagem_google'     => 'imagem',
        'id_admin_subempresa',
        'nome', 'siape', 'email_trabalho', 'email_pessoal', 'email_funcional', 'status', 'estado_civil',
        'matricula', 'primeiro_acesso', 'mudar_senha', 'data_criacao', 'data_atualizacao', 'endereco_cep',
        'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'situacao',
        'trabalho_cargo', 'tipo_pagamento', 'trabalho_data_inicio', 'mensagem', 'grupo', 'tipo', 'federacao',
        'data_termo'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|valido
        cpf|CPF|valido
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
    protected array $ormRetornoPadrao = ['id', 'nome', 'cpf'];
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    /**
     * @param null|Request $request      Request para salvar um novo usuário
     * @param bool         $validarToken Se vai validar o token e a empresa
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?Request $request = null,
        private readonly bool $validarToken = true
    ) {
        parent::__construct();
        if (!$validarToken) {
            return;
        }

        $this->validarEmpresa('empresa');
        $this->validarSubempresa();
        $this->pegarCampoObrigatorio();
    }

    private function pegarCampoObrigatorio(): void
    {
        $Config = new ConfiguracaoEntity();
        $configuracoes = $Config->pegarConfiguracoes();
        $this->campoObrigatorio = empty($configuracoes->campo_obrigatorio->usuario_cliente)
            ? ['cpf', 'email', 'status']
            : $configuracoes->campo_obrigatorio->usuario_cliente;
    }

    /**
     * @return mixed
     * @throws Erro|Excecao
     */
    public function getId(): mixed
    {
        return $this->prop('id');
    }

    /**
     * @return mixed
     * @throws Erro|Excecao
     */
    public function getCpf(): mixed
    {
        return $this->prop('documento');
    }

    /**
     * @param $valor
     *
     * @throws Excecao
     */
    public function setEmpresa($valor): void
    {
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->uuid($valor, mensagem: 'Empresa enviada não foi encontrada.');
        $this->setarIdEmpresaManual($this->Empresa->get('id'));
    }
}
