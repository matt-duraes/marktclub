<?php

namespace App\Models\Api\ParceiroLoja\Trait;

trait ListarCampoTrait
{
    private function pegarCampo(): array
    {
        return ['id', 'cod', 'titulo', 'url', 'tipo', 'desconto', 'estado', 'data_publicacao', 'imagem', 'status'];
    }
}
