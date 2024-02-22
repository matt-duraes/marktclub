<?php

use App\Classes\PublicacaoHome\Tipo;

$Painel = new PainelConfig\Add(app: 'publicidade_home', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Notícias', function () use ($Painel) {
        $Painel
            ->input(name: 'noticia_1', label: 'Notícia 1', placeholder: 'Colocao o código da primeira notícia')
            ->input(name: 'noticia_2', label: 'Notícia 2', placeholder: 'Colocao o código da segunda notícia')
            ->input(name: 'noticia_3', label: 'Notícia 3', placeholder: 'Colocao o código da terceira notícia');
    });
});

return $Painel;
