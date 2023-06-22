<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API</title>

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
    <div class="bloco_menu" id="bloco_menu">
        <?php foreach ($Rota->listar() as $grupo) : ?>
        <div class="grupo fechado">
            <div class="nome">
                <i><svg height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19 15" style="enable-background:new 0 0 19 15;" xml:space="preserve"><path d="M17.1,13.8c-0.1,0-0.3,0-0.4,0c-0.4,0-0.8,0-1.1,0c-0.6,0-1.1,0-1.7,0c-0.7,0-1.3,0-2,0c-0.8,0-1.5,0-2.2,0s-1.5,0-2.2,0c-0.7,0-1.4,0-2,0c-0.6,0-1.1,0-1.8,0c-0.4,0-0.8,0-1.2,0c-0.2,0-0.4,0-0.6,0c0,0-0.1,0-0.2,0c0,0,0.1,0,0.1,0c-0.1,0-0.2,0-0.3-0.1c0,0,0.1,0,0.1,0c-0.1,0-0.1,0-0.2-0.1c0,0,0,0,0,0c-0.1,0,0.1,0.1,0,0s0,0-0.1-0.1c0,0,0,0,0-0.1c0-0.1,0,0.1,0,0c0,0,0,0,0,0c0,0-0.1-0.1-0.1-0.2c0,0,0,0.1,0,0.1c0-0.1,0-0.2-0.1-0.3c0,0,0,0.1,0,0.1c0-0.1,0-0.3,0-0.4c0-0.3,0-0.6,0-0.8c0-0.9,0-1.8,0-2.7c0-1.1,0-2.2,0-3.3c0-0.9,0-1.9,0-2.9c0-0.5,0-0.9,0-1.4c0,0,0-0.1,0-0.2c0,0,0,0.1,0,0.1c0-0.1,0-0.2,0.1-0.3c0,0,0,0.1,0,0.1c0.1,0,0.1,0,0.2-0.1c0,0,0,0,0,0c0,0-0.1,0.1,0,0l0,0c0,0,0,0,0.1,0c0.1-0.1-0.1,0,0,0c0,0,0,0,0,0c0,0,0.1-0.1,0.2-0.1c0,0-0.1,0-0.1,0c0.1,0,0.2,0,0.3-0.1c0,0-0.1,0-0.1,0c0.2,0,0.4,0,0.6,0c0.4,0,0.8,0,1.2,0c1,0,1.9,0,2.8,0c0.2,0,0.4,0,0.7,0C6.9,1.1,6.8,1,6.7,0.9C7.2,1.5,7.8,2,8.4,2.6c0,0.1,0.1,0.2,0.2,0.3C8.8,3,8.9,3.1,9.1,3.1c0.2,0,0.4,0,0.6,0c0.9,0,1.8,0,2.7,0c1,0,2,0,3.1,0c0.5,0,1.1,0,1.6,0c0,0,0.1,0,0.2,0c0,0-0.1,0-0.1,0c0.1,0,0.2,0,0.3,0.1c0,0-0.1,0-0.1,0c0.1,0,0.1,0,0.2,0.1c0,0,0,0,0,0c0.1,0-0.1-0.1,0,0c0,0,0,0,0.1,0c0,0,0,0,0,0.1c0,0.1,0-0.1,0,0c0,0,0,0,0,0c0,0,0.1,0.1,0.1,0.2c0,0,0-0.1,0-0.1c0,0.1,0,0.2,0.1,0.3c0,0,0-0.1,0-0.1c0,0.1,0,0.2,0,0.4c0,0.2,0,0.5,0,0.7c0,0.8,0,1.5,0,2.3c0,0.9,0,1.9,0,2.8c0,0.8,0,1.6,0,2.4c0,0.4,0,0.8,0,1.1c0,0,0,0.1,0,0.2c0,0,0-0.1,0-0.1c0,0.1,0,0.2-0.1,0.3c0,0,0-0.1,0-0.1c0,0.1,0,0.1-0.1,0.2c0,0,0,0,0,0c0,0.1,0.1-0.1,0,0c0,0,0,0-0.1,0.1c0,0,0,0-0.1,0c-0.1,0,0.1,0,0,0c0,0,0,0,0,0C17.4,13.8,17.3,13.9,17.1,13.8c0.1,0,0.2,0,0.2,0C17.3,13.8,17.2,13.8,17.1,13.8c0,0,0.1,0,0.1,0C17.2,13.8,17.2,13.8,17.1,13.8c-0.3,0-0.6,0.3-0.6,0.6c0,0.3,0.3,0.6,0.6,0.6c0.9,0,1.7-0.7,1.9-1.5c0-0.1,0-0.3,0-0.5c0-0.6,0-1.1,0-1.7c0-0.9,0-1.8,0-2.6c0-0.9,0-1.8,0-2.7c0-0.6,0-1.2,0-1.8c0-0.1,0-0.2,0-0.3c0-0.8-0.5-1.5-1.3-1.7C17.5,2,17.2,2,17,2s-0.5,0-0.8,0c-0.9,0-1.7,0-2.6,0c-1,0-1.9,0-2.8,0c-0.6,0-1.1,0-1.7,0c0,0,0,0-0.1,0c0.1,0,0.3,0.1,0.4,0.2C9,1.7,8.6,1.3,8.1,0.8C7.9,0.7,7.6,0.5,7.5,0.3C7.3,0.1,7.2,0.1,7,0.1c-0.1,0-0.1,0-0.2,0C6,0,5.2,0,4.5,0S2.9,0,2.2,0C1.9,0,1.7,0,1.5,0C0.7,0.2,0,0.9,0,1.7C0,2,0,2.2,0,2.5c0,0.6,0,1.2,0,1.8C0,5.2,0,6,0,6.8c0,0.9,0,1.7,0,2.6c0,0.8,0,1.5,0,2.2c0,0.5,0,0.9,0,1.4c0,0,0,0.1,0,0.2c0,0.8,0.6,1.5,1.4,1.7C1.7,15,2,15,2.4,15c0.5,0,1,0,1.5,0c0.8,0,1.5,0,2.2,0c0.9,0,1.8,0,2.6,0c0.9,0,1.8,0,2.7,0c0.8,0,1.7,0,2.5,0c0.7,0,1.3,0,1.9,0c0.4,0,0.8,0,1.1,0c0,0,0.1,0,0.1,0c0.3,0,0.6-0.3,0.6-0.6C17.7,14.1,17.5,13.8,17.1,13.8z"/></svg></i>
                <p><?= $grupo->grupo ?></p>
            </div>
            <?php foreach ($grupo->rota as $rota) : ?>
            <div class="request" data-id="<?= $rota->id ?>" data-arquivo="<?= $rota->arquivo ?>" data-metodo="<?= $rota->metodo ?>" data-uri="<?= $rota->uri ?>">
                <div class="metodo <?=$rota->metodo?>"><?= $rota->metodo == 'POST' ? 'POST' : strCortar($rota->metodo, 3, '', true) ?></div> <div class="uri"><?= $rota->uri ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="bloco_header">
        <div class="lista" id="bloco_aba_lista">
            <?php for ($i = 0; $i < 0; ++$i) :?>
            <div class="aba ativa" id="bloco_aba_modelo">
                <div class="metodo"></div>
                <div class="uri"></div>
                <div class="fechar"><?= iconeFechar(8) ?></div>
            </div>
            <?php endfor; ?>
        </div>
        <div class="botao_mais" id="botao_nova_aba"><?= iconeMais(13)?></div>
    </div>
    <div class="bloco_conteudo bloco_icone display_none" id="bloco_erro">
        <i><svg height="80" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 9H14V4H5V11.8571L6.5 13.25L10 9.5L13 14.5L15 12L18 15L15 14.5L13 17L10 13L7 16.5L5 15.25V20H19V9ZM21 8V20.9932C21 21.5501 20.5552 22 20.0066 22H3.9934C3.44495 22 3 21.556 3 21.0082V2.9918C3 2.45531 3.4487 2 4.00221 2H14.9968L21 8Z"></path></svg></i>
        <p>Erro!</p>
    </div>

    <div class="bloco_conteudo bloco_icone" id="bloco_vazio">
        <i><svg height="80" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4.99958 12.9998C4.99958 7.91186 7.90222 3.56348 11.9996 1.81787C16.0969 3.56348 18.9996 7.91186 18.9996 12.9998C18.9996 13.8227 18.9236 14.6263 18.779 15.4026L20.7194 17.2352C20.8845 17.3911 20.9238 17.6388 20.815 17.8381L18.3196 22.4132C18.1873 22.6556 17.8836 22.7449 17.6412 22.6127C17.5993 22.5898 17.5608 22.5611 17.5271 22.5273L15.2925 20.2927C15.1049 20.1052 14.8506 19.9998 14.5854 19.9998H9.41379C9.14857 19.9998 8.89422 20.1052 8.70668 20.2927L6.47209 22.5273C6.27683 22.7226 5.96025 22.7226 5.76498 22.5273C5.73122 22.4935 5.70246 22.4551 5.67959 22.4132L3.18412 17.8381C3.07537 17.6388 3.11464 17.3911 3.27975 17.2352L5.22014 15.4026C5.07551 14.6263 4.99958 13.8227 4.99958 12.9998ZM6.47542 19.6955L7.29247 18.8785C7.85508 18.3159 8.61814 17.9998 9.41379 17.9998H14.5854C15.381 17.9998 16.1441 18.3159 16.7067 18.8785L17.5237 19.6955L18.5056 17.8954L17.4058 16.8566C16.9117 16.39 16.6884 15.7044 16.8128 15.0363C16.9366 14.3721 16.9996 13.691 16.9996 12.9998C16.9996 9.13025 15.0045 5.69953 11.9996 4.04021C8.99462 5.69953 6.99958 9.13025 6.99958 12.9998C6.99958 13.691 7.06255 14.3721 7.18631 15.0363C7.31078 15.7044 7.08746 16.39 6.59338 16.8566L5.49353 17.8954L6.47542 19.6955ZM11.9996 12.9998C10.895 12.9998 9.99958 12.1044 9.99958 10.9998C9.99958 9.89525 10.895 8.99982 11.9996 8.99982C13.1041 8.99982 13.9996 9.89525 13.9996 10.9998C13.9996 12.1044 13.1041 12.9998 11.9996 12.9998Z"></path></svg></i>
        <p>Selecione uma requisição para começar</p>
    </div>
    <div class="bloco_conteudo display_none" id="bloco_request_lista"></div>

    <div class="bloco_modelo_geral display_none">
        <!-- ABA PADRAO -->
        <div class="aba ativa" id="bloco_aba_modelo">
            <div class="metodo"></div>
            <div class="uri"></div>
            <div class="fechar"><?= iconeFechar(8) ?></div>
        </div>
        <!-- LINHA PADRAO -->
        <li id="bloco_linha_modelo">
            <div class="bloco_checkbox">
                <input type="checkbox" class="check monitorar_salvar" ${checked}>
                <span><?= iconeCheck(10) ?></span>
            </div>
            <input class="chave monitorar_salvar" type="text" value="" name="key" placeholder="chave">
            <input class="valor monitorar_salvar" type="text" value="" name="value" placeholder="valor">
            <i class="deletar"><?= iconeDeletar(17)?></i>
        </li>
        <!-- CONTEUDO PADRAO -->
        <div class="bloco_request ativo" id="bloco_request_modelo">
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
                <div class="botao botao_enviar">ENVIAR</div>
            </div>
            <div class="parametro">
                <ul>
                    <li class="item botao_parametro ativo" data-id="bloco_parametro_parametro">Parametro</li>
                    <li class="item botao_body" data-id="bloco_parametro_body">Body</li>
                    <li class="item botao_header" data-id="bloco_parametro_header">Header</li>
                    <li class="item botao_json" data-id="bloco_parametro_json">JSON</li>
                    <li class="item botao_variavel" data-id="bloco_parametro_variavel">Variável</li>
                    <li class="item botao_documentacao" data-id="bloco_parametro_documentacao">Doc</li>
                    <li class="salvar display_none botao_salvar">salvar</li>
                </ul>
                <ol class="bloco_scroll bloco_parametro_parametro ativo">
                </ol>
                <ol class="bloco_scroll bloco_parametro_body">
                </ol>
                <ol class="bloco_scroll bloco_parametro_header">
                </ol>
                <div class="bloco_scroll bloco_parametro_json">
                    <textarea name="json" class="monitorar_salvar input_json" placeholder="Digite o json"></textarea>
                </div>
                <ol class="bloco_scroll bloco_parametro_variavel">
                </ol>
                <div class="bloco_scroll bloco_parametro_documentacao">
                    <p>Descrição:</p>
                    <textarea name="descricao" placeholder="Digite uma descrição" class="input_descricao"></textarea>
                    <p>Exemplo de requisição:</p>
                    <textarea name="request" placeholder="Digite um CURL de exemplo" class="input_requisicao"></textarea>
                    <p>Exemplo da resposta:</p>
                    <textarea name="resposta" placeholder="Digite um CURL de exemplo" class="input_resposta"></textarea>
                </div>
            </div>
            <div class="bloco_resposta">
                <div class="titulo"><p>Resposta:</p><span class="bloco_codigo_html"></span></div>
                <pre class="bloco_resposta_html"></pre>
            </div>
        </div>
    </div>
</div>

<script>
    <?php require_once __DIR__ . '/../js/all.js'; ?>
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
