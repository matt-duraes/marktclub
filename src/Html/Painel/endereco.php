<?php

use Helpers\ListaHelper;

?>
<div class="bloco_visualizar_conteudo_geral bloco_endereco_geral">
    <input type="hidden" name="local_principal" value="<?= $localPrincipal ?>">
    <input type="hidden" name="local_secundario" value="<?= $localSecundario ?>">
    <div class="bloco_visualizar_lista_geral">
        <div class="botao_pequeno_geral botao_adicionar_endereco">Adicionar endereço</div>
        <form action="" class="form_visualizar_listar">
            <input type="text" class="input_buscar_pesquisa" placeholder="Buscar">
            <input type="text" class="input_buscar_estado" placeholder="Estado">
            <input type="text" class="input_buscar_cidade" placeholder="Cidade">
            <div class="botao botao_buscar_endereco">Buscar</div>
        </form>
        <div class="bloco_visuaizar_listar bloco_endereco_lista">
            <div class="loading bloco_visualizar_loading"></div>
            <div class="zero bloco_visualizar_erro display_none">Erro ao listar endereço</div>
            <div class="zero bloco_visualizar_zero display_none">Sem endereço no momento</div>
        </div>
        <div class="bloco_visualizar_carregar_mais display_none">
            <div class="botao_mais botao_visualizar_carregar_mais">carregar mais</div>
        </div>
    </div>

    <div class="display_none">
        <div class="bloco_visualizar_linha bloco_visualizar_linha_padrao">
            <h1></h1>
            <p><span class="bairro"></span><span class="traco"></span><span class="cidade"></span><span class="barra"></span><span class="estado"></span></p>
            <i class="botao_visualizar_editar editar"><?= iconeEditar(12) ?></i>
            <i class="botao_visualizar_deletar deletar"><?= iconeDeletar(16) ?></i>
        </div>
    </div>

    <div class="bloco_endereco_add display_none">
        <div class="bloco_pagina_popup conteudo">
            <header class="header_pagina_popup">
                <h1>CADASTRAR ENDEREÇO</h1>
                <i class="fechar"><?= iconeFechar() ?></i>
            </header>
            <form action="/" class="form_geral form_endereco">
                <input type="hidden" class="input_id_endereco" value="">
                <div class="bloco">
                    <h2>Dados principais</h2>
                    <?= formSelect(
                        class: 'bloco_endereco_pais',
                        name: 'endereco_pais_' . uuid(),
                        label: 'País',
                        placeholder: 'Escolha um país',
                        lista: (new ListaHelper())->add('', 'Escolha um país')->pais()->r(),
                        value: 'BR',
                        obrigatorio: true
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_titulo',
                        name: 'titulo_' . uuid(),
                        label: 'Título',
                        placeholder: 'Título do endereço',
                        obrigatorio: true,
                    ) ?>
                </div>
                <div class="bloco">
                    <h2>Endereço</h2>
                    <?= formInput(
                        class: 'bloco_endereco_cep_brasil',
                        name: 'endereco_cep_brasil_' . uuid(),
                        label: 'CEP',
                        placeholder: 'CEP do endereço',
                        mascara: '00000-000',
                    ) ?>
                    <div class="buscar_endereco_cep botao_buscar_endereco_cep" data-ajuda="Buscar endereço"><?= iconeBuscar() ?></div>
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
                        obrigatorio: true,
                    ) ?>
                    <?= formNumero(
                        class: 'bloco_endereco_numero',
                        name: 'endereco_numero_' . uuid(),
                        label: 'Número',
                        placeholder: 'Número',
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_complemento',
                        name: 'endereco_complemento_' . uuid(),
                        label: 'Complemento',
                        placeholder: 'Complemento do endereço',
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_referencia',
                        name: 'endereco_referencia_' . uuid(),
                        label: 'Referência',
                        placeholder: 'Referência do endereço',
                    ) ?>
                </div>
                <div class="bloco">
                    <?= formInput(
                        class: 'bloco_endereco_bairro',
                        name: 'endereco_bairro_' . uuid(),
                        label: 'Bairro',
                        placeholder: 'Bairro do endereço',
                        obrigatorio: true,
                    ) ?>
                    <?= formSelect(
                        class: 'bloco_endereco_estado_brasil',
                        name: 'endereco_estado_' . uuid(),
                        label: 'Estado',
                        placeholder: 'Escolha um estado',
                        lista: (new ListaHelper())->add('', 'Escolha um estado')->estado()->r(),
                        obrigatorio: true,
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
                        lista: (new ListaHelper())->add('', 'Escolha uma cidade')->r(),
                        obrigatorio: true,
                    ) ?>
                    <?= formInput(
                        class: 'bloco_endereco_cidade_estrangeiro display_none',
                        name: 'endereco_cidade_estrangeiro_' . uuid(),
                        label: 'Cidade',
                        placeholder: 'Cidade do endereço',
                        obrigatorio: true
                    ) ?>
                    <?= formSwitch(
                        class: 'bloco_endereco_principal',
                        name: 'endereco_principal_' . uuid(),
                        label: 'Esse é o endereço principal?'
                    ) ?>
                </div>
                <div class="bloco_mapa">
                    <h2>Mapa</h2>
                    <div class="geolocalizacao">
                        <?= formInput(
                            class: 'bloco_endereco_latitude',
                            name: 'endereco_latitude_' . uuid(),
                            label: 'Latitude',
                            placeholder: 'Digite a latitude',
                        ) ?>
                        <?= formInput(
                            class: 'bloco_endereco_longitude',
                            name: 'endereco_longitude_' . uuid(),
                            label: 'Longitude',
                            placeholder: 'Digite a longitude',
                        ) ?>
                    </div>
                    <div class="bloco_renderizar">
                        <div class="mapa"></div>
                        <div class="botao_controle">
                            <div class="botao_pequeno_geral botao_buscar_latlong_titulo botao">Buscar por titulo</div>
                            <div class="botao_pequeno_geral botao_buscar_latlong_endereco botao">Buscar por endereço</div>
                            <div class="botao_pequeno_geral botao_buscar_latlong_centro botao">Colocar ponto aqui</div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <?= formCheckbox(name: 'salvar_outro_' . uuid(), class: 'bloco_salvar_outro', label: 'Salvar outro endereço')?>
                    <div class="flex_grow"></div>
                    <div class="botao botao_verde botao_salvar_endereco">Salvar</div>
                </div>
            </form>
        </div>
    </div>
</div>
