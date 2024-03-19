<?php

use Helpers\ListaHelper;
use System\Classes\Contato\Tipo;

?>
<div class="bloco_visualizar_conteudo_geral bloco_contato_geral">
    <input type="hidden" name="local_principal" value="<?= $localPrincipal ?>">
    <input type="hidden" name="local_secundario" value="<?= $localSecundario ?>">
    <div class="bloco_visualizar_lista_geral">
        <div class="botao_pequeno_geral botao_adicionar_contato">Adicionar contato</div>
        <form action="" class="form_visualizar_listar">
            <input type="text" class="input_buscar_pesquisa" placeholder="Buscar">
            <div class="botao botao_buscar_contato">Buscar</div>
        </form>
        <div class="bloco_visuaizar_listar bloco_contato_lista">
            <div class="loading bloco_visualizar_loading"></div>
            <div class="zero bloco_visualizar_erro display_none">Erro ao listar contato</div>
            <div class="zero bloco_visualizar_zero display_none">Sem contato no momento</div>
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

    <div class="bloco_contato_add display_none">
        <div class="bloco_pagina_popup conteudo">
            <header class="header_pagina_popup">
                <h1>CADASTRAR CONTATO</h1>
                <i class="fechar"><?= iconeFechar() ?></i>
            </header>
            <form action="/" class="form_geral">
                <input type="hidden" class="input_id_contato" value="">
                <div class="bloco">
                    <h2>Dados pessoais</h2>
                    <?= formInput(
                        class: 'bloco_contato_titulo',
                        name: 'titulo_' . uuid(),
                        label: 'Título',
                        placeholder: 'Título para o contato',
                        obrigatorio: true,
                    ) ?>
                    <?= formInput(
                        class: 'bloco_contato_nome',
                        name: 'nome_' . uuid(),
                        label: 'Nome',
                        placeholder: 'Nome do contato'
                    ) ?>
                    <?= formCpf(
                        class: 'bloco_contato_cpf',
                        name: 'cpf_' . uuid(),
                        label: 'CPF',
                        placeholder: 'CPF do contato'
                    ) ?>
                </div>
                <div class="bloco">
                    <h2>Contato</h2>
                    <?= formSelect(
                        class: 'bloco_contato_tipo',
                        name: 'contato_tipo_' . uuid(),
                        label: 'Tipo',
                        placeholder: 'Escolha um tipo',
                        lista: (new Tipo())->select('Escolha uma opção'),
                        obrigatorio: true
                    ) ?>
                    <?= formInput(
                        class: 'bloco_contato_telefone display_none',
                        name: ['contato_telefone_ddi_' . uuid(), 'contato_telefone_numero_' . uuid()],
                        label: 'Telefone',
                        placeholder: ['DDI', 'Telefone'],
                        mascara: ['numero', 'telefone'],
                        html: '<div class="mais">+</div>',
                        obrigatorio: true,
                    ) ?>
                    <?= formEmail(
                        class: 'bloco_contato_email display_none',
                        name: 'contato_email_' . uuid(),
                        label: 'E-mail',
                        placeholder: 'E-mail',
                        obrigatorio: true,
                    ) ?>
                    <?= formSwitch(
                        class: 'bloco_contato_whatsapp display_none',
                        name: 'contato_whatsapp_' . uuid(),
                        label: 'O número é WhatsApp?'
                    ) ?>
                    <?= formSwitch(
                        class: 'bloco_contato_principal',
                        name: 'contato_principal_' . uuid(),
                        label: 'Esse é o contato principal?'
                    ) ?>
                </div>
                <div class="footer">
                    <?= formCheckbox(name: 'salvar_outro_' . uuid(), class: 'bloco_salvar_outro', label: 'Salvar outro contato')?>
                    <div class="flex_grow"></div>
                    <div class="botao botao_verde botao_salvar_contato">Salvar</div>
                </div>
            </form>
        </div>
    </div>
</div>
