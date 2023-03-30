<?php

use Helpers\ListaHelper;
?>
<div class="bloco_endereco_geral">
    <input type="hidden" name="tabela" value="<?= $enderecoTabela ?>">
    <input type="hidden" name="local" value="<?= $enderecoLocal ?>">
    <div class="bloco_endereco">
        <div class="botao_pequeno_geral botao_adicionar_endereco">Adicionar endereço</div>
        <div class="lista_endereco bloco_lista_endereco_previa">
        </div>
        <div class="botao_pequeno_geral botao_ver_todos display_none">Ver todos</div>
    </div>

    <div class="bloco_endereco_add display_none">
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
                        name: 'titulo_' . uuid(),
                        label: 'Título',
                        placeholder: 'Título do endereço',
                        obrigatorio: true
                    ) ?>
                    <?= formTelefone(
                        class: 'bloco_endereco_telefone',
                        name: 'telefone_' . uuid(),
                        label: 'Telefone',
                        placeholder: 'Telefone'
                    ) ?>
                </div>
                <div class="bloco">
                    <h2>Endereço</h2>
                    <?= formSelect(
                        class: 'bloco_endereco_pais',
                        name: 'endereco_pais_' . uuid(),
                        label: 'País',
                        placeholder: 'Escolha um país',
                        lista: (new ListaHelper)->add('', 'Escolha um país')->pais()->r(),
                        value: 'BR',
                        obrigatorio: true,
                        change: 'mudarPais'
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_cep_brasil',
                        name: 'endereco_cep_brasil_' . uuid(),
                        label: 'CEP',
                        placeholder: 'CEP do endereço',
                        mascara: '00000-000'
                    ) ?>
                    <div class="buscar_endereco botao_buscar_endereco" data-ajuda="Buscar endereço"><?= iconeBuscar() ?></div>
                    <?= formInput(
                        class: 'bloco_endereco_cep_estrangeiro display_none',
                        name: 'endereco_cep_estrangeiro_' . uuid(),
                        label: 'CEP',
                        placeholder: 'CEP do endereço',
                        mascara: 'numero'
                    ) ?>
                </div>
                <div class="bloco">
                    <?= formInput(
                        class: 'bloco_endereco_logradouro',
                        name: 'endereco_logradouro_' . uuid(),
                        label: 'Logradouro',
                        placeholder: 'Logradouro do endereço',
                        obrigatorio: true
                    ) ?>
                    <?= formNumero(
                        class: 'bloco_endereco_numero',
                        name: 'endereco_numero_' . uuid(),
                        label: 'Número',
                        placeholder: 'Número'
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_complemento',
                        name: 'endereco_complemento_' . uuid(),
                        label: 'Complemento',
                        placeholder: 'Complemento do endereço'
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_referencia',
                        name: 'endereco_referencia_' . uuid(),
                        label: 'Referência',
                        placeholder: 'Referência do endereço'
                    ) ?>
                </div>
                <div class="bloco">
                    <?= formInput(
                        class: 'bloco_endereco_bairro',
                        name: 'endereco_bairro_' . uuid(),
                        label: 'Bairro',
                        placeholder: 'Bairro do endereço',
                        obrigatorio: true
                    ) ?>
                    <?= formSelect(
                        class: 'bloco_endereco_estado_brasil',
                        name: 'endereco_estado_' . uuid(),
                        label: 'Estado',
                        placeholder: 'Escolha um estado',
                        lista: (new ListaHelper)->add('', 'Escolha um estado')->estado()->r(),
                        obrigatorio: true,
                        change: 'buscarCidade'
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_estado_estrangeiro display_none',
                        name: 'endereco_estado_estrangeiro_' . uuid(),
                        label: 'Estado',
                        placeholder: 'Estado do endereço',
                    ) ?>
                    <?= formSelect(
                        class: 'bloco_endereco_cidade_brasil',
                        name: 'endereco_cidade_brasil_' . uuid(),
                        label: 'Cidade',
                        placeholder: 'Escolha uma cidade',
                        lista: (new ListaHelper)->add('', 'Escolha uma cidade')->r(),
                        obrigatorio: true
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_cidade_estrangeiro display_none',
                        name: 'endereco_cidade_estrangeiro_' . uuid(),
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
                            name: 'endereco_latitude_' . uuid(),
                            label: 'Latitude',
                            placeholder: 'Digite a latitude'
                        ) ?>
                        <?= formInput(
                            class: 'bloco_endereco_longitude',
                            name: 'endereco_longitude_' . uuid(),
                            label: 'Longitude',
                            placeholder: 'Digite a longitude'
                        ) ?>
                        <div class="botao inativo">Como chegar</div>
                        <a class="botao ativo display_none" href="" target="_blank" rel="noopener noreferrer">Como chegar</a>
                    </div>
                    <div class="bloco_renderizar">
                        <div class="mapa"></div>
                        <div class="buscar_titulo">
                            <input type="text" class="input_geolocalizacao_titulo" placeholder="Buscar por título...">
                        </div>
                        <div class="botao_controle">
                            <div class="botao_pequeno_geral botao_buscar_geolocalizacao_endereco botao">Localizar endereço</div>
                            <div class="botao_pequeno_geral botao_colocar_marcado botao">Colocar ponto aqui</div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <div class="botao botao_verde botao_salvar_endereco">Salvar</div>
                </div>
            </form>
        </div>
    </div>
</div>
