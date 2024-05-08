<?php

namespace App\Models\Api\ParceiroLoja\Trait;

trait ListarCampoTrait
{
    private function pegarCampo(): array
    {
        return [
            'id', 'uuid', 'titulo', 'titulo_interno', 'url', 'tipo_loja', 'desconto', 'comissao_minima', 'endereco_estado',
            'data_criacao', 'data_publicacao', 'data_problema', 'data_prospeccao', 'imagem_logo', 'data_auditoria', 'status'
        ];
    }
}
