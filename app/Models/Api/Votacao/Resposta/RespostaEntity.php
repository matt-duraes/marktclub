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
        escrever_voto|Escrever voto|valido
        voto_nulo|Voto nulo|valido
    ';
    protected array $ormInsert = ['id_votacao_pergunta'];
    protected array $ormSalvar = [
        'titulo', 'texto', 'escrever_voto', 'voto_nulo', 'ordem'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'escrever_voto', 'voto_nulo', 'ordem'
    ];
    protected int $id_votacao_pergunta;
    public string $pergunta;
    public string $titulo;
    public string $texto;
    public Botao $escrever_voto;
    public Botao $voto_nulo;
    public int $ordem;

    protected function regraInsert()
    {
        $this->id_votacao_pergunta = $this->idPergunta($this->pergunta);
    }
}
