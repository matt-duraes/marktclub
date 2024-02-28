<?php

namespace App\Models\Api\Votacao\Pergunta;

use ORM\Entity;
use App\Classes\Votacao\Pergunta\Tipo;
use App\Models\Api\Votacao\Trait\idVotacaoTrait;

final class PerguntaEntity extends Entity
{
    use idVotacaoTrait;

    protected string $ormTabela = TABELA_VOTACAO_PERGUNTA;
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio|valido
    ';
    protected array $ormInsert = ['id_votacao_dado'];
    protected array $ormSalvar = [
        'titulo', 'texto', 'tipo', 'ordem'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'tipo', 'ordem'
    ];
    protected int $id_votacao_dado;
    public string $votacao;
    public string $titulo;
    public string $texto;
    public Tipo $tipo;
    public int $ordem;

    protected function regraInsert()
    {
        $this->id_votacao_dado = $this->idVotacao($this->votacao);
    }
}
