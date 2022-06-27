<?php

use Painel\FaleConosco\Models\FaleConoscoHelper;

return [
    'mensagem' => [
        [
            'titulo,Dados da mensagem',
            'linha,Nome,nome',
            'linha,E-mail,email',
            'linha,Telefone,telefone',
            'linha,Mensagem,mensagem'
        ],
        [
            'titulo,Data',
            'linha,Data de criação,data_criacao',
            'linha,Data de atualização,data_atualizacao'
        ],
        [
            'titulo,Endereço estimado',
            'linha,Pais,endereco_pais',
            'linha,Estado,endereco_estado',
            'linha,Cidade,endereco_cidade',
        ],
        [
            'titulo,Dados do sistema',
            'linha,IP,ip',
            'linha,Sistema Operacional,sistema_operacional',
            'linha,Navegador,browser',
        ],
        [
            'select,Mudar Status,status,' . painelSelectConfig(FaleConoscoHelper::STATUS_VALOR_TEXTO),
        ]
    ],
    'app' => 'visualizar',
    'historico' => true
];
