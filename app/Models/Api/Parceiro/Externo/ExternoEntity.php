<?php

namespace App\Models\Api\Parceiro\Externo;

use ORM\Entity;
use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use Modules\EnderecoCep;
use Modules\EnderecoEstado;
use System\Classes\Contato\Tipo;
use ApiModel\Contato\ContatoEntity;
use App\Classes\ParceiroLoja\Status;
use ApiModel\Endereco\EnderecoEntity;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;
use System\Classes\PainelHistorico\Acao;
use ApiModel\PainelHistorico\HistoricoEntity;

final class ExternoEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $ormInsert = [
        'id_admin_empresa', 'id_dono_empresa', 'id_dono_equipe', 'categoria_principal',
        'tipo_loja', 'titulo_interno', 'url', 'status'
    ];
    protected array $ormBuscar = [
        'titulo_interno', 'id_dono_equipe', 'categoria_principal', 'data_criacao', 'status'
    ];
    public string $dono;
    public string $titulo_interno;
    protected array $id_admin_empresa;
    protected int $id_dono_empresa;
    protected int $id_dono_equipe;
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
    public string $mensagem;
    public string $url;
    protected TipoLoja $tipo_loja;
    public Status $status;

    protected function regraPosBuscar()
    {
        $this->dono = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarUuidPeloId($this->id_dono_equipe);
    }

    protected function regraInsert()
    {
        $idEmpresa = TOKEN['empresa']->id;
        $idEquipe = TOKEN['usuario']->id;
        $this->id_admin_empresa = [$idEmpresa];
        $this->id_dono_empresa = $idEmpresa;
        $this->id_dono_equipe = $idEquipe;
        $this->status = new Status(Status::PROSPECCAO);
        $this->tipo_loja = new TipoLoja(TipoLoja::LOJA);

        $this->validarCampoDuplicado('titulo_interno', 'O parceiro já existe no sistema.');
        $this->url = strSlug($this->titulo_interno);
    }

    protected function regraPosInsert()
    {
        $this->adicionarContato('Telefone', new Tipo(Tipo::TELEFONE), $this->telefone);
        $this->adicionarContato('E-mail', new Tipo(Tipo::EMAIL), $this->email);
        $this->adicionarEndereco();
        $this->adicionarHistorico();
    }

    private function adicionarContato($titulo, $tipo, $valor)
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
        } catch (\Throwable) {
        }
    }

    private function adicionarEndereco()
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
        } catch (\Throwable) {
        }
    }

    private function adicionarHistorico()
    {
        $Historico = new HistoricoEntity();
        $Historico->set(lista: [
            'relacionado' => [$this->id],
            'mensagem'    => $this->mensagem,
            'app'         => ['parceiro_loja'],
            'acao'        => new Acao(Acao::SALVAR)
        ]);
        $Historico->salvar();
    }
}
