<?php

namespace App\Models\Api\Votacao\Resposta;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Votacao\Trait\idPerguntaTrait;

final class RespostaModel extends ORM implements ModelListarInterface
{
    use idPerguntaTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_VOTACAO_RESPOSTA;
    public string $pergunta;
    public Pagina $pagina;
    public Quantidade $quantidade;

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'texto', 'escrever_voto', 'voto_nulo'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('ordem', 'ASC')
            ->read();

        if (!chaveExiste('lista', $dado)) {
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno($dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'            => $r->uuid,
                'titulo'        => $r->titulo,
                'texto'         => $r->texto,
                'escrever_voto' => (new Botao($r->escrever_voto))->valor(),
                'voto_nulo'     => (new Botao($r->voto_nulo))->valor(),
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where->naoVazio('pergunta', function () use ($Where) {
            $Where->manual(['id_votacao_pergunta', $this->idPergunta($this->pergunta)]);
        });

        return $Where;
    }
}
