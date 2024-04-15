<?php

namespace App\Models\Api\ParceiroLoja\Trait;

trait ListarCampoTrait
{
    private function pegarCampo(): array
    {
        return [
            'id', 'uuid', 'titulo', 'titulo_interno', 'url', 'tipo_loja', 'desconto', 'endereco_estado',
            'data_publicacao', 'imagem_logo', 'data_auditoria', 'status'
        ];
    }
}
