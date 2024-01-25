<?php

$Painel = new PainelConfig\Visualizar('painel_config');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('App Painel', callback: function () use ($Painel) {
        $Painel
            ->array('permissao', 'Permissões')
            ->array('configuracao', 'Configurações')
            ->array('campo_obrigatorio', 'Campos Obrigatórios')
            ->array('campo_permitido', 'Campos Permitidos')
            ->array('upload_grupo', 'Grupo');
    });
});

return $Painel;
