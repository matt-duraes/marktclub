<?php

namespace App\Models\Api\ParceiroLoja\Trait;

trait ListarCampoTrait
{
    private function pegarCampo(): array
    {
        return [
            'id', 'uuid', 'titulo', 'url', 'tipo_loja', 'texto_desconto', 'endereco_estado',
            'data_publicacao', 'imagem_logo', 'data_auditoria', 'status'
        ];
    }
}
