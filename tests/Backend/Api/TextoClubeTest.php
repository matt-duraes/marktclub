<?php

namespace Tests\Api;

use Tests\Token\Clube;

class TextoClubeTest extends Clube
{
    protected string $scope = 'texto_clube';
    protected string $uri = '/texto-clube';
    public string $automatico = 'lbsad';

    protected function pegarBody()
    {
        return [
            'titulo_painel'    => nomeCompletoAleatorio(),
            'empresa'          => '["14afa776394ada4be23be6acf7e3259e"]',
            'titulo'           => 'Titulo de teste',
            'texto'            => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'header_titulo'    => 'header_titulo',
            'header_descricao' => 'header_descricao',
            'header_tag'       => 'header_tag',
            'tipo'             => 'faq',
            'status'           => 'ativo'
        ];
    }
}
