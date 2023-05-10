<?php

namespace App\Models\Site\Shopping;

use App\Marktclub\Api;

final class MetodoPagamento
{
    public function listar()
    {
        if (!isset($_SESSION['SHOPPING_CARRINHO_FINAL']) || !is_object($_SESSION['SHOPPING_CARRINHO_FINAL']) || !isset($_SESSION['SHOPPING_CARRINHO_FINAL']->carrinho)) {
            return mensagem_erro('Erro!', 'Ocorreu um erro ao gerar a lista de métodos de pagamento.', 500);
        }
        $loja = implode(',', array_keys((array)$_SESSION['SHOPPING_CARRINHO_FINAL']->carrinho));

        $metodoPagamento = (new Api())->parametro([
            'loja' => $loja
        ])->get('/ecommerce/metodo-pagamento')->array();

        if (!is_array($metodoPagamento) || !isset($metodoPagamento['erro'])) {
            return mensagem_erro('Erro!', 'Ocorreu um erro ao gerar a lista de métodos de pagamento.', 500);
        } elseif (true === $metodoPagamento['erro']) {
            return mensagem_erro($metodoPagamento['titulo'], $metodoPagamento['texto'], 400);
        }

        return $this->montarMetodoPagamento($metodoPagamento);
    }
    private function montarMetodoPagamento($lista)
    {
        $array = [];
        foreach ($lista['dado'] as $r) {
            $array[$r['id']] = $r['nome'];
        }
        return [
            'erro' => false,
            'dado' => $array,
        ];
    }
}
