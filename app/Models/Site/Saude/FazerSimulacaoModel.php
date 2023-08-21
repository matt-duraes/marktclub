<?php

namespace App\Models\Site\Saude;

use App\Helpers\ClubeApiHelper;
use Erro\Excecao;
use Http\Request;

final class FazerSimulacaoModel extends ClubeApiHelper
{
    public array $simulacao = [];

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        Request $request
    ) {
        parent::__construct();
        $this->salvarSimulacao($request);
    }

    /**
     * @param $request
     *
     * @throws Excecao
     */
    private function salvarSimulacao($request): void
    {
        $dado = $this
            ->body([
                'operadora'        => $request->operadora,
                'regiao'           => $request->regiao,
                'plano'            => $request->plano,
                'titular'          => $request->titular,
                'lista_dependente' => $request->dependentes,
                'acomodacao'       => $request->acomodacao
            ])
            ->post('/saude/simulacao')
            ->object();

        $this->montarSimulacao($dado->dado);
    }

    /**
     * @param $dado
     *
     */
    private function montarSimulacao($dado): void
    {
        $this->simulacao = [
            'id'          => $dado->id,
            'titular'     => [
                'data'  => dataBr($dado->titular),
                'valor' => strDinheiro($dado->valor_titular),
            ],
            'dependente'  => $this->montarDependente($dado->lista_dependente),
            'valor_total' => strDinheiro($dado->valor_total)
        ];
    }

    /**
     * @param $lista
     *
     * @return array
     */
    private function montarDependente($lista): array
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = [
                'data'  => dataBr($r->data_nascimento),
                'valor' => strDinheiro($r->valor)
            ];
        }
        return $retorno;
    }
}
