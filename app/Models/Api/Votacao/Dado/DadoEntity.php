<?php

namespace App\Models\Api\Votacao\Dado;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use App\Classes\Geral\Publicado;
use App\Classes\Votacao\Dado\Tipo;
use App\Classes\Votacao\Dado\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DadoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_VOTACAO_DADO;
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
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'tipo', 'voto_unico', 'identificar_usuario', 'data_inicio',
        'data_final', 'bloqueado', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'tipo', 'voto_unico', 'identificar_usuario', 'data_inicio',
        'data_final', 'bloqueado', 'status'
    ];
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

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }

    protected function regraPosBuscar()
    {
        $this->publicado = new Publicado(
            inicio: $this->data_inicio,
            final: $this->data_final,
            ativo: $this->status->indice() == $this->status::ATIVO
        );

        $this->validarStatusVotacao();
    }

    private function validarStatusVotacao()
    {
        $votacao = 'aguardando';
        $agora = agora();
        if ($this->data_inicio->date() <= $agora && $this->data_final->date() >= $agora) {
            $votacao = 'andamento';
            $this->bloqueado = new Botao(Botao::SIM);
        } elseif ($this->data_inicio->date() < agora()) {
            $this->bloqueado = new Botao(Botao::SIM);
            $votacao = 'finalizado';
        }
        $this->status_votacao = $votacao;
    }
}
