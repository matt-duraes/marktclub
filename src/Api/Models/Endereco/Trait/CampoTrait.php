<?php

namespace ApiModel\Endereco\Trait;

trait CampoTrait
{
    private function pegarCampos()
    {
        return [
            'uuid', 'titulo', 'cep', 'logradouro', 'complemento', 'referencia',
            'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
        ];
    }
}
