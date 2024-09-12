<?php

namespace App\Models\Api\Votacao\Resposta;

use App\Models\Api\Votacao\Trait\idPerguntaTrait;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use Where\Where;

final class RespostaModel extends ORM implements ModelListarInterface
{
    use idPerguntaTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    public string $pergunta;
    public Pagina $pagina;
    public Quantidade $quantidade;
    protected string $ormTabela = TABELA_VOTACAO_RESPOSTA;

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

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where->naoVazio('pergunta', function () use ($Where) {
            $Where->manual(['id_votacao_pergunta', $this->idPergunta($this->pergunta)]);
        });

        return $Where;
    }

    private function montarRetorno($dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'            => $r->uuid,
                'titulo'        => $r->titulo,
                'texto'         => $r->texto,
                'escrever_voto' => !empty($r->escrever_voto) && $r->escrever_voto == '1' ? Botao::SIM : Botao::NAO,
                'voto_nulo'     => !empty($r->voto_nulo) && $r->voto_nulo == '1' ? Botao::SIM : Botao::NAO,
            ];
        }
        return $retorno;
    }
}
