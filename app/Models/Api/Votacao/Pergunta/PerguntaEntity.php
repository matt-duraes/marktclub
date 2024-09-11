<?php

namespace App\Models\Api\Votacao\Pergunta;

use App\Classes\Votacao\Pergunta\Tipo;
use App\Models\Api\Votacao\Trait\idVotacaoTrait;
use App\Models\Api\Votacao\Trait\MensagemTrait;
use App\Models\Api\Votacao\Trait\VotacaoBloqueadaTrait;
use Modules\Botao;
use ORM\Entity;

final class PerguntaEntity extends Entity
{
    use idVotacaoTrait;
    use VotacaoBloqueadaTrait;
    use MensagemTrait;

    public string $votacao;
    public string $titulo;
    public string $texto;
    public Tipo $tipo;
    public Botao $pode_nulo;
    public int $ordem;
    protected string $ormTabela = TABELA_VOTACAO_PERGUNTA;
    protected array $ormBuscar = [
        'titulo', 'texto', 'tipo', 'pode_nulo', 'ordem'
    ];
    protected array $ormInsert = [
        'id_votacao_dado'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'tipo', 'pode_nulo', 'ordem'
    ];
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio|valido
        pode_nulo|Não é obrigatório|valido
    ';
    protected int $id_votacao_dado;

    protected function regraSalvar(): void
    {
        if (!empty($this->id_votacao_dado) && $this->votacaoBloqueada($this->id_votacao_dado)) {
            $this->mensagemBloqueado();
        }
    }

    protected function regraInsert(): void
    {
        $this->id_votacao_dado = $this->idVotacao($this->votacao);
    }
}
