<?php

namespace App\Models\Api\ComercialEmpresa;

use App\Classes\ComercialEmpresa\CanalPreferencia;
use App\Classes\ComercialEmpresa\FormatoReuniao;
use App\Classes\ComercialEmpresa\Origem;
use ORM\Entity;
use Modules\Cpf;
use Modules\Cnpj;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Inteiro;
use Modules\Dinheiro;
use Modules\Telefone;
use Helpers\OrmHelper;
use Modules\EnderecoEstado;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\TipoSite;
use App\Classes\ComercialEmpresa\EmailDisparo;
use App\Classes\ComercialEmpresa\ContratoPrazo;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Classes\ComercialEmpresa\CadastroUsuario;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\ContratoRenovacao;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use App\Models\Api\ComercialFatura\UltimaFaturaModel;
use App\Classes\ComercialEmpresa\FinalidadeSecundaria;
use App\Models\Api\ComercialPagamento\PagamentoEntity;
use App\Models\Api\ComercialEmpresa\Trait\ValidarEmpresaAtivaTrait;

final class EmpresaEntity extends Entity
{
    use ValidarEmpresaAtivaTrait;

    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;
    protected array $ormBuscar = [
        'finalidade_principal' => 'finalidade_empresa',
        'titulo', 'finalidade_secundaria', 'nome_fantasia', 'razao_social', 'imagem_arquivo', 'slug',
        'site', 'responsavel_nome', 'responsavel_cargo', 'responsavel_email', 'responsavel_telefone', 'responsavel_cpf',
        'id_usuario_equipe', 'tipo_pagamento', 'renda_media', 'produto_clube',
        'produto_ios', 'produto_android', 'produto_site', 'produto_webview', 'produto_api', 'cnpj',
        'estado_principal', 'status', 'data_eleicao', 'email_dia', 'whatsapp_dia', 'rede_social_dia',
        'contrato_prazo', 'contrato_renovacao', 'tipo_site', 'cadastro_usuario', 'comunicacao_email',
        'comunicacao_whatsapp', 'comunicacao_rede_social', 'email_disparo', 'prospeccao_status',
        'observacao_ti', 'observacao_comunicacao', 'observacao_financeiro', 'restricao_lista', 'contrato_data',
        'contrato_dia_pagamento', 'cobrar_aposentado', 'contrato_valor', 'contrato_valor_minimo',
        'contrato_usuario_minimo', 'contrato_dia_fechamento', 'parceiro_proprio', 'contratou_concorrente', 'qual_concorrente',
        'origem', 'base_usuarios', 'canal_preferencia', 'data_apresentacao', 'formato_reuniao', 'previsao_retorno', 'motivo_standby',
        'motivo_standby', 'motivo_perdido', 'devolutiva', 'nivel_decisao', 'etapa_negociacao'
    ];
    protected array $ormSalvar = [
        'finalidade_empresa' => '->finalidade_principal',
        'titulo', 'finalidade_secundaria', 'nome_fantasia', 'razao_social', 'imagem_arquivo', 'slug',
        'site', 'responsavel_nome', 'responsavel_cargo', 'responsavel_email', 'responsavel_telefone', 'responsavel_cpf',
        'id_usuario_equipe', 'tipo_pagamento', 'renda_media', 'produto_clube',
        'produto_ios', 'produto_android', 'produto_site', 'produto_webview', 'produto_api', 'cnpj',
        'estado_principal', 'status', 'data_eleicao', 'email_dia', 'whatsapp_dia', 'rede_social_dia',
        'contrato_prazo', 'contrato_renovacao', 'tipo_site', 'cadastro_usuario', 'comunicacao_email',
        'comunicacao_whatsapp', 'comunicacao_rede_social', 'email_disparo', 'prospeccao_status',
        'observacao_ti', 'observacao_comunicacao', 'observacao_financeiro', 'restricao_lista', 'contrato_data',
        'contrato_dia_pagamento', 'cobrar_aposentado', 'contrato_valor', 'contrato_valor_minimo',
        'contrato_usuario_minimo', 'contrato_dia_fechamento', 'parceiro_proprio', 'contratou_concorrente', 'qual_concorrente',
        'origem', 'base_usuarios', 'canal_preferencia', 'data_apresentacao', 'formato_reuniao', 'previsao_retorno', 'motivo_standby',
        'motivo_standby', 'motivo_perdido', 'devolutiva', 'nivel_decisao', 'etapa_negociacao'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|vazio
        finalidade_principal|Finalidade principal|vazio|valido
        finalidade_secundaria|Finalidade principal|vazio|valido
        id_usuario_equipe|Responsável pelo contrato|vazio|int>0
        responsavel_nome|Nome do responsável|vazio|valido
        responsavel_telefone|Telefone do responsável|vazio|valido
        responsavel_email|E-mail do responsável|vazio|valido
        status|Status|vazio|valido
    ';
    protected array $ormRetornoPadrao = ['id', 'nome_fantasia', 'imagem', 'slug', 'status'];
    protected int $id_usuario_equipe;
    public string $titulo;
    public Cnpj $cnpj;
    public string $razao_social;
    public string $nome_fantasia;
    public string $imagem;
    public string $slug;
    public ProspeccaoStatus $prospeccao_status;
    public Status $status;
    public Nome $responsavel_nome;
    public Cpf $responsavel_cpf;
    public Email $responsavel_email;
    public Telefone $responsavel_telefone;
    public Botao $produto_clube;
    public Botao $produto_ios;
    public Botao $produto_android;
    public Botao $produto_site;
    public Botao $produto_webview;
    public Botao $produto_api;
    public Botao $cobrar_aposentado;
    public string $site;
    public TipoPagamento $tipo_pagamento;
    public UltimaFaturaModel $valor_pago;
    public Dinheiro $contrato_valor;
    public Dinheiro $contrato_valor_minimo;
    public Inteiro $contrato_usuario_minimo;
    public Inteiro $contrato_dia_pagamento;
    public Inteiro $contrato_dia_fechamento;
    public Dinheiro $renda_media;
    public Dinheiro $valor_pib;
    public EnderecoEstado $estado_principal;
    public string $equipe;
    public FinalidadePrincipal $finalidade_principal;
    public FinalidadeSecundaria $finalidade_secundaria;
    public Data $data_eleicao;
    public array $email_dia;
    public array $whatsapp_dia;
    public array $rede_social_dia;
    public Data $contrato_data;
    public ContratoPrazo $contrato_prazo;
    public ContratoRenovacao $contrato_renovacao;
    public TipoSite $tipo_site;
    public CadastroUsuario $cadastro_usuario;
    public Botao $comunicacao_email;
    public Botao $comunicacao_whatsapp;
    public Botao $comunicacao_rede_social;
    public EmailDisparo $email_disparo;
    public string $observacao_ti;
    public string $observacao_comunicacao;
    public Botao $parceiro_proprio;
    public Botao $contratou_concorrente;
    public string $qual_concorrente;
    public Origem $origem;
    public int $base_usuarios;
    public CanalPreferencia $canal_preferencia;
    public string $data_apresentacao;
    public FormatoReuniao $formato_reuniao;
    public string $observacao_financeiro;
    public array $restricao_lista;
    public string $previsao_retorno;
    public string $motivo_standby;
    public string $motivo_perdido;
    public Data $devolutiva;
    public int $nivel_decisao;
    public int $etapa_negociacao;
    private bool $atualizarValor = false;

    protected function regraInsert()
    {
        $this->prospeccao_status = new ProspeccaoStatus(ProspeccaoStatus::PESQUISA);
        $this->status = new Status(Status::PROSPECCAO);
        $this->validarSeJaExisteCnpj();
    }

    protected function regraUpdate()
    {
        $this->validarSeJaExisteCnpj($this->prop('id'));
    }

    protected function regraSalvar()
    {
        if ($this->status->indice() == Status::ATIVO) {
            $this->validarEmpresaAtiva();
        }
        if (
            $this->propriedadeExiste('contrato_valor') &&
            $this->prop('contrato_valor') != $this->contrato_valor->decimal()
        ) {
            $this->atualizarValor = true;
        }
        $this->setarUsuarioEquipe();
    }

    protected function regraPosSalvar()
    {
        if ($this->atualizarValor) {
            new PagamentoEntity(Empresa: $this, valor: $this->contrato_valor);
        }
    }

    private function setarUsuarioEquipe()
    {
        if (empty($this->equipe)) {
            $this->id_usuario_equipe = array_key_exists('usuario', TOKEN) ? TOKEN['usuario']->id : null;
            return;
        }
        $this->id_usuario_equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($this->equipe);
    }

    protected function regraPosBuscar()
    {
        if (empty($this->imagem)) {
            $this->imagem = arquivoPublico('empresa', 'padrao.png');
        }

        $this->valor_pago = new UltimaFaturaModel(Empresa: $this);

        $this->equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarUuidPeloId($this->id_usuario_equipe);
    }

    protected function getId()
    {
        return $this->prop('id');
    }

    private function validarSeJaExisteCnpj(?int $id = null)
    {
        if (!$this->propriedadeExiste('cnpj') || !$this->cnpj->valido()) {
            return;
        }

        $where = [['cnpj', $this->cnpj->numero()]];
        if (!empty($id)) {
            $where[] = ['id', '!=', $id];
        }

        if ($this->existe($where)) {
            mensagemErro('Campo duplicado!', 'O CNPJ informado já está em uso por outro cliente');
        }
    }
}
