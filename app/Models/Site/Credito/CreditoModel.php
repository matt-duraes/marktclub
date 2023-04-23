<?php

namespace App\Models\Site\Credito;

final class CreditoModel
{
    public function listarParcelas(int $quantidade = 96): array
    {
        $parcela = [
            '' => 'Escolha uma opção',
            1 => '1 parcela'
        ];
        for ($i = 2; $i <= 96; ++$i) {
            $parcela[$i] = $i . ' parcelas';
        }
        return $parcela;
    }
}
