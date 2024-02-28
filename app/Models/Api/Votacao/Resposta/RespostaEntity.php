<?php

namespace App\Models\Api\Votacao\Resposta;

use ORM\Entity;
use Modules\Botao;
use App\Models\Api\Votacao\Trait\idPerguntaTrait;

final class RespostaEntity extends Entity
{
    use idPerguntaTrait;

    protected string $ormTabela = TABELA_VOTACAO_RESPOSTA;
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        pode_nulo|Pode Nulo|valido
        escrever_voto|Escrever voto|valido
    ';
    protected array $ormInsert = ['id_votacao_pergunta'];
    protected array $ormSalvar = [
        'titulo', 'texto', 'pode_nulo', 'escrever_voto', 'ordem'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'pode_nulo', 'escrever_voto', 'ordem'
    ];
    protected int $id_votacao_pergunta;
    public string $pergunta;
    public string $titulo;
    public string $texto;
    public Botao $pode_nulo;
    public Botao $escrever_voto;
    public int $ordem;

    protected function regraInsert()
    {
        $this->id_votacao_pergunta = $this->idPergunta($this->pergunta);
    }
}
