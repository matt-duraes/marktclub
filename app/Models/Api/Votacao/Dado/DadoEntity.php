<?php

namespace App\Models\Api\Votacao\Dado;

use App\Classes\Geral\Publicado;
use App\Classes\Votacao\Dado\Status;
use App\Classes\Votacao\Dado\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Votacao\Trait\MensagemTrait;
use Erro\Excecao;
use Modules\Botao;
use Modules\DataHora;
use ORM\Entity;

final class DadoEntity extends Entity
{
    use ValidarEmpresaTrait;
    use MensagemTrait;

    public string $titulo;
    public string $texto;
    public Tipo $tipo;
    public Botao $voto_unico;
    public Botao $identificar_usuario;
    public Botao $bloqueado;
    public string $status_votacao;
    public DataHora $data_inicio;
    public DataHora $data_final;
    public Publicado $publicado;
    public Status $status;
    public bool $estaBloqueado = false;
    protected string $ormTabela = TABELA_VOTACAO_DADO;
    protected array $ormBuscar = [
        'titulo', 'texto', 'tipo', 'voto_unico', 'identificar_usuario', 'data_inicio',
        'data_final', 'bloqueado', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'tipo', 'voto_unico', 'identificar_usuario', 'data_inicio',
        'data_final', 'bloqueado', 'status'
    ];
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio|valido
        voto_unico|Voto único|valido
        indentificar_usuario|Identificar usuário|valido
        data_inicio|Data de início da publicação|obrigatorio|vazio|valido
        data_final|Data final da publicação|valido
        status|Status|obrigatorio|vazio|valido
    ';

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->publicado = new Publicado(
            inicio: $this->data_inicio,
            final: $this->data_final,
            ativo: $this->status->indice() == $this->status::ATIVO
        );
        if ($this->status->se(Status::CANCELADO)) {
            $this->bloquear();
        }
        $this->validarStatusVotacao();
    }

    private function bloquear(): void
    {
        $this->bloqueado = new Botao(Botao::SIM);
        $this->estaBloqueado = true;
    }

    private function validarStatusVotacao(): void
    {
        $votacao = 'aguardando';
        $agora = agora();
        if (
            $this->data_inicio->date() <= $agora
            && $this->data_final->date() >= $agora
            && $this->publicado == Publicado::SIM
        ) {
            $this->bloquear();
            $votacao = 'andamento';
        } elseif ($this->data_final->date() < agora()) {
            $this->bloquear();
            $votacao = 'finalizado';
        }
        $this->status_votacao = $votacao;
    }

    protected function regraUpdate(): void
    {
        if ($this->estaBloqueado) {
            $this->mensagemBloqueado();
        }
    }
}
