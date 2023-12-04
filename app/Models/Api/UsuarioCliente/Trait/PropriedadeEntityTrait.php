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
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\Situacao;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\UsuarioCliente\TipoPagamento;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

trait PropriedadeEntityTrait
{
    public EmpresaEntity $Empresa;
    public string $matricula;
    public string $siape;
    public string $contratoSiape;
    public Nome $nome;
    public Cpf $cpf;
    public string $rg;
    public Email $email;
    public Email $email_trabalho;
    public Email $email_pessoal;
    public Email $email_funcional;
    public Telefone $telefone_pessoal;
    public Telefone $telefone_trabalho;
    public Senha $senha;
    public Data $data_nascimento;
    public Data $trabalho_data_inicio;
    public Data $data_termo;
    public Botao $termo;
    public TrabalhoEmpresa $trabalho_empresa;
    public TrabalhoCargo $trabalho_cargo;
    public TipoPagamento $tipo_pagamento;
    public Genero $genero;
    public EstadoCivil $estado_civil;
    public int $id_admin_empresa;
    public int $id_admin_subempresa;
    public Botao $primeiro_acesso;
    public Botao $mudar_senha;
    public Botao $lead;
    public Botao $mensagem;
    public Situacao $situacao;
    public Status $status;
    public string $imagem;
    public string $imagem_google;
    public array $pagamento;
    public string $grupo;
    public Origem $origem;
    public TipoUsuario $tipo;
    public int $codigo_plano;
    public string $contrato_siape;
    public string $federacao;
    public EnderecoCep $endereco_cep;
    public EnderecoEstado $endereco_estado;
    public string $endereco_cidade;
    public string $endereco_logradouro;
    public string $endereco_numero;
    public string $endereco_complemento;
    public string $endereco_bairro;
    public string $responsavel_cargo;
    public string $subempresa = '';
    protected string $cod;
    private array $campoObrigatorio = [];
    private int $idEmpresa;
}
