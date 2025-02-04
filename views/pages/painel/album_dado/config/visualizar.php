<?php

use App\Classes\Geral\Status;
use PainelConfig\Visualizar;

$Painel = new Visualizar('album_dado');

$Painel->imagemLogo('imagem')->margin(16);
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Informações do Álbum', function () use ($Painel) {
        $Painel
            ->linha('titulo', 'Título do álbum')
            ->linha('texto', 'Descrição do álbum')
            ->linha('permissao_restrita', 'Disponível na Área restrita?')
            ->linha('permissao_site', 'Disponível ao público?')
            ->dataHora('data_inicio', 'Data de publicação')
            ->dataHora('data_final', 'Data de remoção');
    });

    $Painel->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->linha('status', 'Status')
            ->dataHora('data_criacao', 'Data de criação')
            ->dataHora('data_atualizacao', 'Data da última atualização');
    });

    $Painel->include('fotos');
});

$arr = ['sim' => 'Sim', 'nao' => 'Não'];
$Painel->replace('permissao_restrita', $arr);
$Painel->replace('permissao_site', $arr);
$Painel->replace('status', (new Status())->select());

$Painel->css('painel_album_dado_visualizar');
$Painel->js('painel_album_dado_visualizar');

return $Painel;
