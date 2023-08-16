<?php

namespace App\Models\Site\Saude;

use Http\Request;
use App\Helpers\ClubeApiHelper;

final class FazerSimulacaoModel extends ClubeApiHelper
{
    public array $simulacao = [];

    public function __construct(
        Request $request
    ) {
        parent::__construct();
        $this->salvarSimulacao($request);
    }

    private function salvarSimulacao($request)
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

    private function montarSimulacao($dado)
    {
        $this->simulacao = [
            'id'      => $dado->id,
            'titular' => [
                'data'  => dataBr($dado->titular),
                'valor' => strDinheiro($dado->valor_titular),
            ],
            'dependente'  => $this->montarDependente($dado->lista_dependente),
            'valor_total' => strDinheiro($dado->valor_total)
        ];
    }

    private function montarDependente($lista)
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
