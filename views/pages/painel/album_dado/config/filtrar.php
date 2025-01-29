<?php

use App\Classes\Geral\Status;
use Helpers\ApiHelper;
use PainelConfig\Filtrar;

$Painel = new Filtrar('album_dado');

$equipe = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um usuário'])
    ->get('/usuario-equipe/select')
    ->array();
$Status = new Status();
$Painel
    ->select(
        'empresa',
        'empresa',
        'Empresa',
        'Empresa',
        'Empresa',
        permissao: 'album_dado_empresa'
    )
    ->input(
        'titulo',
        'Título do álbum',
        'Título do álbum',
        'Título do álbum'
    )
    ->select(
        'equipe',
        empty($equipe['dado']) ? [] : $equipe['dado'],
        'Equipe',
        'Equipe',
        'Equipe'
    )
    ->bloco(function () use ($Painel, $Status) {
        $Painel
            ->switch(
                'permissao_restrita',
                'Área restrita',
                'Área restrita'
            )
            ->switch(
                'permissao_site',
                'Público',
                'Público'
            );
    })
    ->bloco(function () use ($Painel, $Status) {
        $Painel
            ->data(
                'data_inicio',
                'Data de publicação',
                'Data de publicação',
                'Data de publicação'
            )
            ->data(
                'data_final',
                'Data de remoção',
                'Data de remoção',
                'Data de remoção'
            );
    })
    ->bloco(function () use ($Painel, $Status) {
        $Painel
            ->numero(
                'quantidade',
                'Quantidade de registros',
                'Quantidade de registros',
                'Quantidade de registros'
            )
            ->select(
                'status',
                $Status->select('Escolha um status'),
                'Status',
                'Status',
                'Status'
            );
    });

return $Painel;
