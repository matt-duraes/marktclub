<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POSTMAN</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        <?php require_once __DIR__ . '/../css/resetar.css'; ?>
        <?php require_once __DIR__ . '/../css/form.css'; ?>
        <?php require_once __DIR__ . '/../css/alerta.css'; ?>
        <?php require_once __DIR__ . '/../css/layout.css'; ?>
    </style>

</head>
<body>

<div id="site">
    <div class="bloco_menu">
        <div class="bloco_criar">
            <h1>Requisições</h1>
            <i id="botao_adicionar_grupo"><?= iconeMais(13)?></i>
        </div>
        <div class="lista" id="bloco_menu">
        <?= $menu ?>
        </div>
    </div>
    <div class="bloco_header">
        <div class="lista" id="bloco_aba_lista">
        </div>
        <div class="botao_mais" id="botao_nova_aba"><?= iconeMais(13)?></div>
    </div>
    <div class="bloco_conteudo bloco_icone" id="bloco_requisicao_vazio">
        <i><svg height="80" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4.99958 12.9998C4.99958 7.91186 7.90222 3.56348 11.9996 1.81787C16.0969 3.56348 18.9996 7.91186 18.9996 12.9998C18.9996 13.8227 18.9236 14.6263 18.779 15.4026L20.7194 17.2352C20.8845 17.3911 20.9238 17.6388 20.815 17.8381L18.3196 22.4132C18.1873 22.6556 17.8836 22.7449 17.6412 22.6127C17.5993 22.5898 17.5608 22.5611 17.5271 22.5273L15.2925 20.2927C15.1049 20.1052 14.8506 19.9998 14.5854 19.9998H9.41379C9.14857 19.9998 8.89422 20.1052 8.70668 20.2927L6.47209 22.5273C6.27683 22.7226 5.96025 22.7226 5.76498 22.5273C5.73122 22.4935 5.70246 22.4551 5.67959 22.4132L3.18412 17.8381C3.07537 17.6388 3.11464 17.3911 3.27975 17.2352L5.22014 15.4026C5.07551 14.6263 4.99958 13.8227 4.99958 12.9998ZM6.47542 19.6955L7.29247 18.8785C7.85508 18.3159 8.61814 17.9998 9.41379 17.9998H14.5854C15.381 17.9998 16.1441 18.3159 16.7067 18.8785L17.5237 19.6955L18.5056 17.8954L17.4058 16.8566C16.9117 16.39 16.6884 15.7044 16.8128 15.0363C16.9366 14.3721 16.9996 13.691 16.9996 12.9998C16.9996 9.13025 15.0045 5.69953 11.9996 4.04021C8.99462 5.69953 6.99958 9.13025 6.99958 12.9998C6.99958 13.691 7.06255 14.3721 7.18631 15.0363C7.31078 15.7044 7.08746 16.39 6.59338 16.8566L5.49353 17.8954L6.47542 19.6955ZM11.9996 12.9998C10.895 12.9998 9.99958 12.1044 9.99958 10.9998C9.99958 9.89525 10.895 8.99982 11.9996 8.99982C13.1041 8.99982 13.9996 9.89525 13.9996 10.9998C13.9996 12.1044 13.1041 12.9998 11.9996 12.9998Z"></path></svg></i>
        <p>Selecione uma requisição para começar</p>
    </div>
    <div class="bloco_conteudo display_none" id="bloco_requisicao_lista"></div>

    <div class="bloco_modelo_geral display_none">
        <!-- GRUPO PADRAO -->
        <div class="grupo novo fechado update ativo bloco_keyup" dat-nome="" id="bloco_menu_grupo_modelo">
            <div class="nome hover">
                <i class="pasta pasta_aberta"><?= iconePasta(20) ?></i>
                <i class="pasta pasta_fechada"><?= iconePastaAberta(20) ?></i>
                <p class="nome_grupo bloco_nome"></p>
                <input type="text" class="nome_grupo bloco_input input_salvar input_nome" placeholder="Nome do grupo">
                <i class="opcao botao_opcao_grupo"><?= iconeOpcao() ?></i>
            </div>
            <div class="requisicao_vazio">Sem requisição</div>
        </div>
        <!-- SEM REQUISICAO -->
        <div class="requisicao_vazio" id="bloco_menu_sem_requisicao">Sem requisição</div>
        <!-- REQUEST PADRAO -->
        <div class="requisicao novo update ativo bloco_keyup" data-id="" data-metodo="GET" data-nome="" id="bloco_menu_requisicao_modelo">
            <div class="metodo GET">GET</div>
            <div class="nome bloco_nome"></div>
            <input name="nome" class="nome_requisicao bloco_input input_salvar input_nome" value="" placeholder="Nome da rota">
            <i class="opcao botao_opcao_requisicao"><?= iconeOpcao() ?></i>
        </div>
        <!-- ABA PADRAO -->
        <div class="aba ativo" id="bloco_aba_modelo">
            <div class="metodo"></div>
            <div class="nome"></div>
            <div class="fechar"><?= iconeFechar(8) ?></div>
        </div>
        <!-- LINHA PADRAO -->
        <li id="bloco_linha_modelo">
            <div class="bloco_checkbox">
                <input type="checkbox" class="check monitorar_salvar" ${checked}>
                <span><?= iconeCheck(10) ?></span>
            </div>
            <div class="bloco_select bloco_tipo_linha">
                <select name="tipo_linha" class="tipo monitorar_salvar">
                    <option value="texto">Texto</option>
                    <option value="cript">Cript</option>
                </select>
                <i><?= iconeSetaBaixo(6) ?></i>
            </div>
            <input class="chave monitorar_salvar" type="text" value="" name="chave" placeholder="chave">
            <input class="valor monitorar_salvar" type="text" value="" name="valor" placeholder="valor">
            <input class="descricao monitorar_salvar" type="text" value="" name="descricao" placeholder="descrição">
            <i class="deletar"><?= iconeDeletar(17)?></i>
        </li>
        <!-- CONTEUDO PADRAO -->
        <div class="bloco_requisicao ativo" id="bloco_requisicao_modelo">
            <div class="link">
                <div class="bloco_select bloco_token">
                    <select name="token" class="input_token monitorar_salvar">
                        <option value="sem_token">Sem token</option>
                        <option value="token">Token</option>
                        <option value="painel">Painel</option>
                    </select>
                    <i><?= iconeSetaBaixo(6) ?></i>
                </div>
                <div class="bloco_select bloco_metodo">
                    <select name="metodo" class="input_metodo monitorar_salvar">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                    <i><?= iconeSetaBaixo(6) ?></i>
                </div>
                <input type="text" class="input_uri monitorar_salvar" name="uri" placeholder="Digite a URL">
                <div class="botao botao_enviar"></div>
            </div>
            <div class="parametro">
                <ul>
                    <li class="item botao_parametro" data-id="bloco_parametro_parametro">Parametro</li>
                    <li class="item botao_body" data-id="bloco_parametro_body">Body</li>
                    <li class="item botao_header" data-id="bloco_parametro_header">Header</li>
                    <li class="item botao_json" data-id="bloco_parametro_json">JSON</li>
                    <li class="item botao_variavel" data-id="bloco_parametro_variavel">Variável</li>
                    <li class="item botao_documentacao" data-id="bloco_parametro_documentacao">Doc</li>
                    <li class="salvar display_none botao_salvar">salvar</li>
                </ul>
                <ol class="bloco_scroll bloco_parametro_parametro">
                </ol>
                <ol class="bloco_scroll bloco_parametro_body">
                </ol>
                <ol class="bloco_scroll bloco_parametro_header">
                </ol>
                <ol class="bloco_scroll bloco_parametro_json">
                </ol>
                <ol class="bloco_scroll bloco_parametro_variavel">
                </ol>
                <div class="bloco_scroll bloco_parametro_documentacao">
                    <div class="botao_add_documentacao">
                        <div class="checked">
                            <input class="monitorar_salvar input_documentacao" type="checkbox">
                            Adicionar a documentação?
                            <div class="bloco_bola"><span></span></div>
                        </div>
                    </div>
                    <p>Descrição:</p>
                    <textarea name="descricao" placeholder="Digite uma descrição" class="input_descricao"></textarea>
                    <p>Exemplo de requisição:</p>
                    <textarea name="requisicao" placeholder="Digite um CURL de exemplo" class="input_requisicao"></textarea>
                    <p>Exemplo da resposta:</p>
                    <textarea name="resposta" placeholder="Digite um CURL de exemplo" class="input_resposta"></textarea>
                </div>
            </div>
            <div class="bloco_resposta_lista">
                <div class="titulo"><p>Resposta:</p><span class="bloco_codigo_html"></span></div>
                <ul class="bloco_resposta_botao display_none">
                    <li class="tipo_resposta botao_resposta_body ativo" data-id="bloco_resposta_body">Body</li>
                    <li class="tipo_resposta botao_resposta_json" data-id="bloco_resposta_json">Json</li>
                    <li class="tipo_resposta botao_resposta_html" data-id="bloco_resposta_html">HTML</li>
                </ul>
                <div class="bloco_resposta bloco_resposta_body ativo"></div>
                <pre class="bloco_resposta bloco_resposta_json"></pre>
                <iframe class="bloco_resposta bloco_resposta_html"></iframe>
            </div>
        </div>
    </div>
</div>

<div class="bloco_opcao display_none" id="bloco_opcao_grupo">
    <div class="botao botao_grupo_renomear">Renomear grupo</div>
    <div class="botao botao_grupo_deletar">Deletar grupo</div>
    <div class="botao botao_requisicao_adicionar">Adicionar Request</div>
</div>
<div class="bloco_opcao display_none" id="bloco_opcao_requisicao">
    <div class="botao botao_requisicao_renomear">Renomear request</div>
    <div class="botao botao_requisicao_deletar">Deletar request</div>
</div>

<script>
    <?php require_once __DIR__ . '/../js/all.js'; ?>
    <?php require_once __DIR__ . '/../js/enviar.js'; ?>
    <?php require_once __DIR__ . '/../js/menu.js'; ?>
    <?php require_once __DIR__ . '/../js/aba.js'; ?>
    <?php require_once ROOT . '/src/Html/Scripts/js/Player.system.js'; ?>
    <?php require_once ROOT . '/src/Html/Scripts/js/Alerta.system.js'; ?>
    <?php require_once ROOT . '/src/Html/Scripts/js/Form.init.js'; ?>
    <?php require_once ROOT . '/src/Html/Scripts/js/Form.select.js'; ?>
    <?php require_once ROOT . '/src/Html/Scripts/js/Form.cor.js'; ?>
    <?php require_once ROOT . '/src/Html/Scripts/js/Form.tag.js'; ?>
    <?php require_once ROOT . '/src/Html/Scripts/js/Form.system.js'; ?>
</script>
</body>
</html>
