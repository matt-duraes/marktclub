<?php

$Painel = new PainelConfig\Filtrar('comercial_subempresa');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
    )
    ->input(
        name: 'titulo',
        titulo: 'Título',
        label: 'Título',
        placeholder: 'Digite o título'
    );

return $Painel;
