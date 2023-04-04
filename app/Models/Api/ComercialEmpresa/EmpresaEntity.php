<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\Entity;
use Modules\Cpf;
use Modules\Cnpj;
use Modules\Botao;
use Modules\Email;
use Modules\Dinheiro;
use Modules\Telefone;
use Modules\EnderecoEstado;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\TipoPagamento;

final class EmpresaEntity extends Entity
{
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;
    protected array $ormBuscar = [
        'imagem' => 'imagem_arquivo',
        'finalidade_principal' => 'finalidade_empresa',
        'titulo', 'cnpj', 'razao_social', 'nome_fantasia', 'slug', 'responsavel_nome', 'responsavel_cpf',
        'responsavel_email', 'responsavel_telefone', 'finalidade_secundaria', 'tipo_pagamento',
        'valor_pago', 'produto_clube', 'produto_ios', 'produto_android', 'produto_site',
        'renda_media', 'valor_pib', 'estado_principal', 'site', 'status'
    ];
    protected array $ormSalvar = [
        'finalidade_empresa' => '->finalidade_principal',
        'titulo', 'cnpj', 'razao_social', 'nome_fantasia', 'slug', 'responsavel_nome', 'responsavel_cpf',
        'responsavel_email', 'responsavel_telefone', 'finalidade_secundaria', 'tipo_pagamento',
        'valor_pago', 'produto_clube', 'produto_ios', 'produto_android', 'produto_site',
        'renda_media', 'valor_pib', 'estado_principal', 'site', 'status'
    ];

    protected string $ormValidarSalvar = '
        titulo|Título|vazio
        razao_social|Razão Social|vazio
        cnpj|CNPJ|vazio|valido
        responsavel_nome|Nome do responsável|vazio|valido
        responsavel_cpf|CPF do responsável|vazio|valido
        responsavel_telefone|Telefone do responsável|valido
        responsavel_email|E-mail do responsável|valido
        estado_principal|Estado principal|valido
        tipo_pagamento|Tipo de pagamento|valido
        valor_pago|Valor pago|valido
        renda_media|Renda média|valido
        valor_pib|Valor do PIB|valido
        status|Status|vazio|valido
    ';

    protected array $ormRetornoPadrao = ['id', 'nome_fantasia', 'imagem', 'slug', 'status'];

    public string $titulo;
    public Cnpj $cnpj;
    public string $razao_social;
    public string $nome_fantasia;
    public string $imagem;
    public string $slug;
    public Status $status;
    public string $responsavel_nome;
    public Cpf $responsavel_cpf;
    public Email $responsavel_email;
    public Telefone $responsavel_telefone;
    public Botao $produto_clube;
    public Botao $produto_ios;
    public Botao $produto_android;
    public Botao $produto_site;
    public string $site;
    public TipoPagamento $tipo_pagamento;
    public Dinheiro $valor_pago;
    public Dinheiro $renda_media;
    public Dinheiro $valor_pib;
    public EnderecoEstado $estado_principal;


    protected function regraPosBuscar()
    {
        if (empty($this->imagem)) {
            $this->imagem = arquivoPublico('empresa', 'padrao.png');
        }
    }


    protected function getId()
    {
        return $this->prop('id');
    }
}
