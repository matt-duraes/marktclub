<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_parceiro_loja')->relacionado(TABELA_PARCEIRO_LOJA, 'id')
    ->varchar('descricao')->tamanho(100)
    ->varchar('cupom')->tamanho(100)
    ->varchar('desconto')->tamanho(20)
    ->int('categoria')->tamanho(2)
    ->varchar('link')->tamanho(50)->null()
    ->datetime('validade')
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
