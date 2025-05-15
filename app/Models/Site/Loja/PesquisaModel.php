<?php

namespace App\Models\Site\Loja;

use App\Helpers\ClubeApiHelper;

final class PesquisaModel extends ClubeApiHelper
{
    public function salvar($request)
    {
        $dado = $this
            ->validar('Ocorreu um erro ao salvar sua indicação', status: 404)
            ->body([
                'fidelidade'  => $request->programaFidelidade,
                'produtos'    => $request->produtosProcurados,
                'gasto'       => $request->tvSmart,
                'importancia' => $request->opcaoProdutoMarca,
                'cashback'    => $request->acreditaEmCashback,
                'frequencia'  => $request->frequenciaCashback,
                'resgate'     => $request->resgateCashback,
                'desconto'    => $request->sobreParcerias,
                'experiencia' => $request->suaExperiencia,
                'indicaria'   => $request->voceIndicaria,
            ])
            ->post('/enquete-mercado')
            ->object();
        return $dado;
    }
}
