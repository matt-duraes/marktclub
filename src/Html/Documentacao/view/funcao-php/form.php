<?php

$Doc = new DocumentacaoConfig\Fw('FUNÇÕES DO FORM', 'Cria formulário com layout/script padrão.', classe: 'form_geral');
echo $Doc
    ->systemCssJs(['Form', 'Galeria', 'Calendario', 'Ckeditor'])
    ->margin(30)
    ->funcao(ROOT . '/src/Function/Form.func.php');
