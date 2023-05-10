<?php

namespace App\Classes\SolicitacaoAlfa;

class Helper
{
    public const CRIPTOGRAFAR = [
        'codigo_solicitacao', 'valor_emprestimo', 'prazo', 'valor_parcela_atual',
        'quantidade_parcelas_restantes', 'taxa', 'nome', 'documento_cpf', 'email',
        'telefone_celular', 'telefone_fixo', 'orgao', 'observacao', 'status', 'tipo'
    ];
}
