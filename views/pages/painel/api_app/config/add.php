<?php

use Helpers\ApiHelper;

$Painel = new PainelConfig\Add('api_app');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Imagem', function () use ($Painel) {
        $Painel->imagem('imagem', 'uuid_aqui', height: 400);
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do AP', function () use ($Painel) {
        $Api = new ApiHelper(scope: 'comercial_empresa:listar');
        $empresa = $Api->get('/comercial-empresa/select')->array()['dado'] ?? [];
        $Painel
            ->input(name: 'nome', label: 'Nome do APP', obrigatorio: 1)
            ->textarea(name: 'descricao', label: 'Descrição para o APP')
            ->select(name: 'id_admin_empresa', label: 'Empresa', placeholder: 'Escolha uma empresa', obrigatorio: true, lista: ['' => 'Escolha uma empresa'] + $empresa);
    });
    $Painel->fieldset('Segurança', function () use ($Painel) {
        $Painel
            ->numero(name: 'tempo_vida', label: 'Tem de vida', placeholder: 'Tempo de vida em segundos', obrigatorio: 1)
            ->switch('authorization_code', 'Token será para login via oAuth 2.0?')
            ->switch('client_credentials', 'Token será para enviar a um cliente?')
            ->switch('refresh_token', 'O Token pode ser renovado?')
            ->switch('chave_publica_publica', 'A chave pública deser ser mostradada na documentação?')
            ->switch('chave_privada_publica', 'A chave privada deve ser mostradada na documentação?')
            ->tag('redirect_uri', 'URL do projeto', tipo: 'url');
    });
});

return $Painel;
