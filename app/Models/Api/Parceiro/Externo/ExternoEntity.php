<?php

namespace App\Models\Api\Parceiro\Externo;

use ApiModel\Contato\ContatoEntity;
use ApiModel\Endereco\EnderecoEntity;
use ApiModel\PainelHistorico\HistoricoEntity;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use Helpers\OrmHelper;
use Modules\Email;
use Modules\EnderecoCep;
use Modules\EnderecoEstado;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;
use System\Classes\Contato\Tipo;
use System\Classes\PainelHistorico\Acao;
use Throwable;

final class ExternoEntity extends Entity
{
    public string $dono;
    public array $captador = [];
    public array $contato;
    public string $titulo_interno;
    public string $dono_equipe;
    public Categoria $categoria_principal;
    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public EnderecoCep $endereco_cep;
    public string $endereco_logradouro;
    public string $endereco_numero;
    public string $endereco_complemento;
    public string $endereco_bairro;
    public string $endereco_cidade;
    public EnderecoEstado $endereco_estado;
    public array $estado_parceiro;
    public string $mensagem;
    public string $url;
    public Status $status;
    public Indicador $tipo_indicador;
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $ormBuscar = [
        'titulo_interno', 'id_dono_equipe', 'categoria_principal', 'data_criacao',
        'status', 'tipo_indicador', 'id_usuario_equipe', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'endereco_estado' => '->estado_parceiro',
        'id_admin_empresa', 'id_dono_subempresa', 'id_dono_empresa', 'id_dono_equipe',
        'categoria_principal', 'tipo_loja', 'titulo_interno', 'url', 'status',
        'tipo_indicador'
    ];
    protected string $ormValidarSalvar = '
        titulo_interno|Nome da parceria|vazio|obrigatorio
        categoria_principal|Categoria|vazio|obrigatorio|valido
        tipo_indicador|Indicador|vazio|obrigatorio|valido
        nome|Nome|vazio|obrigatorio|valido
        telefone|Telefone|vazio|obrigatorio|valido
        email|E-mail|vazio|obrigatorio|valido
        endereco_cep|CEP|vazio|obrigatorio|valido
        endereco_logradouro|Logradouro|vazio|obrigatorio
        endereco_bairro|Bairro|vazio|obrigatorio
        endereco_cidade|Cidade|vazio|obrigatorio
        endereco_estado|Estado|vazio|obrigatorio|valido
        mensagem|Mensagem|vazio|obrigatorio
    ';
    protected array $id_admin_empresa;
    protected int $id_dono_empresa;
    protected int $id_dono_subempresa;
    protected int $id_usuario_equipe;
    protected int $id_dono_equipe;
    protected TipoLoja $tipo_loja;

    protected function regraPosBuscar(): void
    {
        $OrmHelperEquipe = new OrmHelper(TABELA_USUARIO_EQUIPE);
        $OrmHelperContato = new OrmHelper(TABELA_SISTEMA_CONTATO);

        $this->dono = $OrmHelperEquipe->pegarUuidPeloId($this->id_dono_equipe);
        $contatos = $OrmHelperContato->listar(['nome', 'cpf', 'tipo', 'valor'], ['id_vinculo', $this->id]);

        if (!empty($this->id_usuario_equipe)) {
            $captador = $OrmHelperEquipe->pegarUltimoRegistro([
                'uuid', $this->id_usuario_equipe
            ], ['nome_real'], 'object');
            $this->captador = [
                'id'   => $this->id_usuario_equipe,
                'nome' => $captador->nome_real
            ];
        }

        $this->contato = [];
        foreach ($contatos as $contato) {
            if ((new Tipo($contato->tipo))->indice() === Tipo::EMAIL) {
                $this->contato['email'] = $contato->valor;
            }
            if ((new Tipo($contato->tipo))->indice() === Tipo::TELEFONE) {
                $this->contato['telefone'] = $contato->valor;
            }
            $this->contato['nome'] = $contato->nome;
        }
    }

    protected function regraInsert(): void
    {
        $idEmpresa = TOKEN['empresa']->id;
        $idEquipe = TOKEN['usuario']->id;
        $idSubempresa = TOKEN['usuario']->id_admin_subempresa;
        if (!empty($idSubempresa)) {
            $this->id_dono_subempresa = $idSubempresa;
        }
        $this->estado_parceiro = [$this->endereco_estado->uf()];
        $this->id_admin_empresa = [$idEmpresa];
        $this->id_dono_empresa = $idEmpresa;
        $this->id_dono_equipe = $idEquipe;
        $this->status = new Status(Status::PROSPECCAO);
        $this->tipo_loja = new TipoLoja(TipoLoja::LOJA);

        $this->validarCampoDuplicado('titulo_interno', 'O parceiro já existe no sistema.');
        $this->url = strSlug($this->titulo_interno);
    }

    protected function regraPosInsert(): void
    {
        $this->adicionarContato('Telefone', new Tipo(Tipo::TELEFONE), $this->telefone);
        $this->adicionarContato('E-mail', new Tipo(Tipo::EMAIL), $this->email);
        $this->adicionarEndereco();
        $this->adicionarHistorico();
    }

    private function adicionarContato($titulo, $tipo, $valor): void
    {
        if (empty($valor)) {
            return;
        }
        try {
            $valor = $valor instanceof Telefone ? '+55 ' . $valor->telefone() : $valor->email();
            $Contato = new ContatoEntity();
            $Contato->set(lista: [
                'local_principal'  => 'parceiro_loja',
                'local_secundario' => 'painel',
                'vinculo'          => $this->id,
                'titulo'           => $titulo,
                'nome'             => $this->nome,
                'tipo'             => $tipo,
                'valor'            => $valor,
                'principal'        => 'nao'
            ]);
            $Contato->salvar();
        } catch (Throwable) {
        }
    }

    private function adicionarEndereco(): void
    {
        try {
            $Endereco = new EnderecoEntity();
            $Endereco->set(lista: [
                'vinculo'          => $this->id,
                'local_principal'  => 'parceiro_loja',
                'local_secundario' => 'painel',
                'pais'             => 'BR',
                'titulo'           => 'Endereço',
                'cep'              => $this->endereco_cep,
                'logradouro'       => $this->endereco_logradouro,
                'numero'           => $this->endereco_numero,
                'complemento'      => $this->endereco_complemento,
                'bairro'           => $this->endereco_bairro,
                'cidade'           => $this->endereco_cidade,
                'estado'           => $this->endereco_estado,
                'latitude'         => '-15.787284',
                'longitude'        => '-47.913265',
                'principal'        => 'nao'
            ]);
            $Endereco->salvar();
        } catch (Throwable) {
        }
    }

    private function adicionarHistorico(): void
    {
        $Historico = new HistoricoEntity();
        $Historico->set(lista: [
            'relacionado' => [$this->id],
            'mensagem'    => $this->mensagem,
            'app'         => ['parceiro_externo', 'parceiro_loja'],
            'acao'        => new Acao(Acao::SALVAR)
        ]);
        $Historico->salvar();
    }
}
