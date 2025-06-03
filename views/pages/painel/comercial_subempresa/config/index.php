<?php

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\UsuarioCliente\Helper;
use PainelConfig\Index;

$Painel = new Index('comercial_subempresa', new Ordem());

$Painel
    ->campo(
        'empresa_matriz.nome_fantasia',
        'Empresa Matriz',
        Index::TIPO_GRANDE,
        permissao: 'comercial_subempresa_empresa'
    )
    ->campo('titulo', 'Nome', Index::TIPO_GRANDE)
    ->campo('cnpj', 'CNPJ', Index::TIPO_NORMAL, Index::FORMATAR_CNPJ)
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
