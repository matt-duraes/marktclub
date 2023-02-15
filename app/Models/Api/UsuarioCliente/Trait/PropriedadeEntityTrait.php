<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use Modules\Cpf;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Senha;
use Modules\Genero;
use Modules\Telefone;
use Modules\EstadoCivil;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\Situacao;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\UsuarioCliente\TipoPagamento;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;

trait PropriedadeEntityTrait
{
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
        'trabalho_cargo', 'tipo_pagamento', 'trabalho_data_inicio', 'mensagem', 'grupo', 'tipo'
    ];
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
        'tipo_pagamento', 'trabalho_data_inicio', 'grupo'
    ];
    protected array $_insert = [
        'empresa' => '->idEmpresa',
        'cod', 'tipo'
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

    protected string $cod;
    public string $matricula;
    public string $siape;
    public string $contratoSiape;
    public Nome $nome;
    public Cpf $cpf;
    public Email $email;
    public Email $email_trabalho;
    public Email $email_pessoal;
    public Email $email_funcional;
    public Telefone $telefone_pessoal;
    public Telefone $telefone_trabalho;
    public Senha $senha;
    public Data $data_nascimento;
    public Data $trabalho_data_inicio;
    public TrabalhoEmpresa $trabalho_empresa;
    public TrabalhoCargo $trabalho_cargo;
    public TipoPagamento $tipo_pagamento;
    public Genero $genero;
    public EstadoCivil $estado_civil;
    public int $id_admin_empresa;
    public Botao $primeiro_acesso;
    public Botao $mudar_senha;
    public Botao $lead;
    public Botao $mensagem;
    public Situacao $situacao;
    public Status $status;
    public string $imagem;
    public array $pagamento;
    public string $grupo;
    public Origem $origem;
    public TipoUsuario $tipo;

    public string $contrato_siape;
    private array $campoObrigatorio;
    private int $idEmpresa;
}
