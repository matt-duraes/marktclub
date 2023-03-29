<?php

use Helpers\ListaHelper;
?>
<div class="bloco_endereco">
    <div class="botao_pequeno_geral">Adicionar endereço</div>
    <div class="lista_endereco">

    </div>
</div>

<div id="bloco_endereco_add">
    <div class="bloco_pagina_popup conteudo">
        <header class="header_pagina_popup">
            <h1>CADASTRAR ENDEREÇO</h1>
            <i class="fechar"><?= iconeFechar() ?></i>
        </header>
        <form action="/" class="form_geral">
            <div class="bloco">
                <h2>Dados do endereço</h2>
                <?= formInput(
                    class: 'bloco_endereco_titulo',
                    name: 'titulo',
                    label: 'Título',
                    placeholder: 'Título do endereço',
                    obrigatorio: true
                ) ?>
                <?= formTelefone(
                    class: 'bloco_endereco_telefone',
                    name: 'telefone',
                    label: 'Telefone',
                    placeholder: 'Telefone'
                ) ?>
            </div>
            <div class="bloco display_none">
                <h2>Endereço</h2>
                <?= formSelect(
                    class: 'bloco_endereco_pais',
                    name: 'endereco_pais',
                    label: 'País',
                    placeholder: 'Escolha um país',
                    lista: (new ListaHelper)->add('', 'Escolha um país')->pais()->r(),
                    value: 'BR',
                    obrigatorio: true
                ) ?>
                <?= formInput(
                    class: 'bloco_endereco_cep',
                    name: 'endereco_cep',
                    label: 'CEP',
                    placeholder: 'CEP do endereço',
                    mascara: '00000-000',
                    obrigatorio: true
                ) ?>
            </div>
            <div class="bloco display_none">
                <?= formInput(
                    class: 'bloco_endereco_logradouro',
                    name: 'endereco_logradouro',
                    label: 'Logradouro',
                    placeholder: 'Logradouro do endereço',
                    obrigatorio: true
                ) ?>
                <?= formNumero(
                    class: 'bloco_endereco_numero',
                    name: 'endereco_numero',
                    label: 'Número',
                    placeholder: 'Número'
                ) ?>
                <?= formInput(
                    class: 'bloco_endereco_referencia',
                    name: 'endereco_referencia',
                    label: 'Referência',
                    placeholder: 'Referência do endereço'
                ) ?>
            </div>
            <div class="bloco display_none">
                <?= formInput(
                    class: 'bloco_endereco_bairro',
                    name: 'endereco_bairro',
                    label: 'Bairro',
                    placeholder: 'Bairro do endereço',
                    obrigatorio: true
                ) ?>
                <?= formSelect(
                    class: 'bloco_endereco_estado',
                    name: 'endereco_estado',
                    label: 'Estado',
                    placeholder: 'Escolha um estado',
                    lista: (new ListaHelper)->add('', 'Escolha um estado')->estado()->r(),
                    obrigatorio: true
                ) ?>
                <?= formSelect(
                    class: 'bloco_endereco_cidade_brasil',
                    name: 'endereco_cidade_brasil',
                    label: 'Cidade',
                    placeholder: 'Escolha uma cidade',
                    lista: (new ListaHelper)->add('', 'Escolha uma cidade')->r(),
                    obrigatorio: true
                ) ?>
                <?= formInput(
                    class: 'bloco_endereco_cidade_estrangeiro display_none',
                    name: 'endereco_cidade_estrangeiro',
                    label: 'Cidade',
                    placeholder: 'Cidade do endereço',
                    obrigatorio: true
                ) ?>
            </div>
            <div class="bloco_mapa">
                <h2>Mapa</h2>
                <div class="geolocalizacao">
                    <?= formInput(
                        class: 'bloco_endereco_latitude',
                        name: 'endereco_latitude',
                        label: 'Latitude',
                        placeholder: 'Digite a latitude'
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_longitude',
                        name: 'endereco_longitude',
                        label: 'Longitude',
                        placeholder: 'Digite a longitude'
                    ) ?>
                    <div class="botao inativo">Como chegar</div>
                    <a class="botao ativo display_none" href="" target="_blank" rel="noopener noreferrer">Como chegar</a>
                </div>
                <div class="mapa" id="mapa"></div>
            </div>
            <div class="footer">
                <div class="botao botao_verde">Salvar</div>
            </div>
        </form>
    </div>
</div>
