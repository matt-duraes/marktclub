<?php

use PainelConfig\Index;
use App\Classes\Geral\Status;

$Painel = new Index('usuario_grupo');

$Painel
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL, permissao: 'usuario_grupo_empresa')
    ->campo('titulo', 'Titulo', Index::TIPO_NORMAL)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
