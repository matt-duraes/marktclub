<?php

$Painel = new PainelConfig\Add(app: 'galapagos__lead', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Titulo', function () use ($Painel) {
        // campos
    });
});

return $Painel;
