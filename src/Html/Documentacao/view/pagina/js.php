<?php

$Doc = new DocumentacaoConfig\Fw('JS', 'A utilização do JS deve ser feita ao máximo com Vanilla, mesmo assim, para ele funcionar precisa do gulp rodando.');

$Doc
    ->paragrafo('Aqui não vou ser repetitivo, o JS funciona exatamente igual o CSS, a única diferênça é que seus imports devem ser comentados por causa da validação dos editores.')
    ->codigo('
// @template "site"
// @resources "site/variavel"
// @import "responsivo"

...
    ');
echo $Doc;
