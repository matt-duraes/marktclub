<?php

namespace App\Models\Api\Votacao\Resposta;

use App\Models\Api\Votacao\Trait\idPerguntaTrait;
use App\Models\Api\Votacao\Trait\MensagemTrait;
use App\Models\Api\Votacao\Trait\VotacaoBloqueadaTrait;
use Modules\Botao;
use ORM\Entity;

final class RespostaEntity extends Entity
{
    use idPerguntaTrait;
    use VotacaoBloqueadaTrait;
    use MensagemTrait;

    public string $pergunta;
    public string $titulo;
    public string $texto;
    public Botao $escrever_voto;
    public Botao $voto_nulo;
    public int $ordem;
    protected string $ormTabela = TABELA_VOTACAO_RESPOSTA;
    protected array $ormBuscar = [
        'titulo', 'texto', 'escrever_voto', 'voto_nulo', 'ordem'
    ];
    protected array $ormInsert = [
        'id_votacao_pergunta'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'escrever_voto', 'voto_nulo', 'ordem'
    ];
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        escrever_voto|Escrever voto|valido
        voto_nulo|Voto nulo|valido
    ';
    protected int $id_votacao_pergunta;

    protected function regraInsert(): void
    {
        $this->id_votacao_pergunta = $this->idPergunta($this->pergunta);
    }
}
