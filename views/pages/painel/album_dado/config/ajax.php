<?php

use PainelConfig\Ajax;

$Painel = new Ajax();

$Painel
    ->grupo('foto-buscar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('album_dado_foto')
            ->metodo('get')
            ->rota('/album-foto/{id}');
    })
    ->grupo('foto-salvar', function () use ($Painel) {
        $Painel
            ->request(['album', 'titulo', 'imagem', 'status'])
            ->permissao('album_dado_foto')
            ->metodo('post')
            ->rota('/album-foto');
    })
    ->grupo('foto-atualizar', function () use ($Painel) {
        $Painel
            ->request(['album', 'titulo', 'imagem', 'status'])
            ->permissao('album_dado_foto')
            ->metodo('put')
            ->rota('/album-foto/{id}');
    })
    ->grupo('foto-deletar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('album_dado_foto')
            ->metodo('delete')
            ->rota('/album-foto/{id}');
    });

return $Painel;
