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
    <div class="bloco_menu">
        <?php foreach ($Rota->listar() as $grupo) : ?>
        <div class="grupo fechado">
            <div class="nome">
                <i><svg height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19 15" style="enable-background:new 0 0 19 15;" xml:space="preserve"><path d="M17.1,13.8c-0.1,0-0.3,0-0.4,0c-0.4,0-0.8,0-1.1,0c-0.6,0-1.1,0-1.7,0c-0.7,0-1.3,0-2,0c-0.8,0-1.5,0-2.2,0s-1.5,0-2.2,0c-0.7,0-1.4,0-2,0c-0.6,0-1.1,0-1.8,0c-0.4,0-0.8,0-1.2,0c-0.2,0-0.4,0-0.6,0c0,0-0.1,0-0.2,0c0,0,0.1,0,0.1,0c-0.1,0-0.2,0-0.3-0.1c0,0,0.1,0,0.1,0c-0.1,0-0.1,0-0.2-0.1c0,0,0,0,0,0c-0.1,0,0.1,0.1,0,0s0,0-0.1-0.1c0,0,0,0,0-0.1c0-0.1,0,0.1,0,0c0,0,0,0,0,0c0,0-0.1-0.1-0.1-0.2c0,0,0,0.1,0,0.1c0-0.1,0-0.2-0.1-0.3c0,0,0,0.1,0,0.1c0-0.1,0-0.3,0-0.4c0-0.3,0-0.6,0-0.8c0-0.9,0-1.8,0-2.7c0-1.1,0-2.2,0-3.3c0-0.9,0-1.9,0-2.9c0-0.5,0-0.9,0-1.4c0,0,0-0.1,0-0.2c0,0,0,0.1,0,0.1c0-0.1,0-0.2,0.1-0.3c0,0,0,0.1,0,0.1c0.1,0,0.1,0,0.2-0.1c0,0,0,0,0,0c0,0-0.1,0.1,0,0l0,0c0,0,0,0,0.1,0c0.1-0.1-0.1,0,0,0c0,0,0,0,0,0c0,0,0.1-0.1,0.2-0.1c0,0-0.1,0-0.1,0c0.1,0,0.2,0,0.3-0.1c0,0-0.1,0-0.1,0c0.2,0,0.4,0,0.6,0c0.4,0,0.8,0,1.2,0c1,0,1.9,0,2.8,0c0.2,0,0.4,0,0.7,0C6.9,1.1,6.8,1,6.7,0.9C7.2,1.5,7.8,2,8.4,2.6c0,0.1,0.1,0.2,0.2,0.3C8.8,3,8.9,3.1,9.1,3.1c0.2,0,0.4,0,0.6,0c0.9,0,1.8,0,2.7,0c1,0,2,0,3.1,0c0.5,0,1.1,0,1.6,0c0,0,0.1,0,0.2,0c0,0-0.1,0-0.1,0c0.1,0,0.2,0,0.3,0.1c0,0-0.1,0-0.1,0c0.1,0,0.1,0,0.2,0.1c0,0,0,0,0,0c0.1,0-0.1-0.1,0,0c0,0,0,0,0.1,0c0,0,0,0,0,0.1c0,0.1,0-0.1,0,0c0,0,0,0,0,0c0,0,0.1,0.1,0.1,0.2c0,0,0-0.1,0-0.1c0,0.1,0,0.2,0.1,0.3c0,0,0-0.1,0-0.1c0,0.1,0,0.2,0,0.4c0,0.2,0,0.5,0,0.7c0,0.8,0,1.5,0,2.3c0,0.9,0,1.9,0,2.8c0,0.8,0,1.6,0,2.4c0,0.4,0,0.8,0,1.1c0,0,0,0.1,0,0.2c0,0,0-0.1,0-0.1c0,0.1,0,0.2-0.1,0.3c0,0,0-0.1,0-0.1c0,0.1,0,0.1-0.1,0.2c0,0,0,0,0,0c0,0.1,0.1-0.1,0,0c0,0,0,0-0.1,0.1c0,0,0,0-0.1,0c-0.1,0,0.1,0,0,0c0,0,0,0,0,0C17.4,13.8,17.3,13.9,17.1,13.8c0.1,0,0.2,0,0.2,0C17.3,13.8,17.2,13.8,17.1,13.8c0,0,0.1,0,0.1,0C17.2,13.8,17.2,13.8,17.1,13.8c-0.3,0-0.6,0.3-0.6,0.6c0,0.3,0.3,0.6,0.6,0.6c0.9,0,1.7-0.7,1.9-1.5c0-0.1,0-0.3,0-0.5c0-0.6,0-1.1,0-1.7c0-0.9,0-1.8,0-2.6c0-0.9,0-1.8,0-2.7c0-0.6,0-1.2,0-1.8c0-0.1,0-0.2,0-0.3c0-0.8-0.5-1.5-1.3-1.7C17.5,2,17.2,2,17,2s-0.5,0-0.8,0c-0.9,0-1.7,0-2.6,0c-1,0-1.9,0-2.8,0c-0.6,0-1.1,0-1.7,0c0,0,0,0-0.1,0c0.1,0,0.3,0.1,0.4,0.2C9,1.7,8.6,1.3,8.1,0.8C7.9,0.7,7.6,0.5,7.5,0.3C7.3,0.1,7.2,0.1,7,0.1c-0.1,0-0.1,0-0.2,0C6,0,5.2,0,4.5,0S2.9,0,2.2,0C1.9,0,1.7,0,1.5,0C0.7,0.2,0,0.9,0,1.7C0,2,0,2.2,0,2.5c0,0.6,0,1.2,0,1.8C0,5.2,0,6,0,6.8c0,0.9,0,1.7,0,2.6c0,0.8,0,1.5,0,2.2c0,0.5,0,0.9,0,1.4c0,0,0,0.1,0,0.2c0,0.8,0.6,1.5,1.4,1.7C1.7,15,2,15,2.4,15c0.5,0,1,0,1.5,0c0.8,0,1.5,0,2.2,0c0.9,0,1.8,0,2.6,0c0.9,0,1.8,0,2.7,0c0.8,0,1.7,0,2.5,0c0.7,0,1.3,0,1.9,0c0.4,0,0.8,0,1.1,0c0,0,0.1,0,0.1,0c0.3,0,0.6-0.3,0.6-0.6C17.7,14.1,17.5,13.8,17.1,13.8z"/></svg></i>
                <p><?= $grupo->grupo ?></p>
            </div>
            <?php foreach ($grupo->rota as $rota) : ?>
            <div class="request" data-id="<?= $rota->id ?>" data-metodo="<?= $rota->metodo ?>" data-uri="<?= $rota->uri ?>">
                <div class="metodo"><?= strCortar($rota->metodo, 3, '', true) ?></div> <div class="uri"><?= $rota->uri ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="bloco_conteudo display_none" id="bloco_erro">
        <p>Erro!</p>
    </div>
    <div class="bloco_conteudo display_none" id="bloco_loading">
        <p>Carregando requisição</p>
    </div>
    <div class="bloco_conteudo display_none" id="bloco_vazio">
        <p>Selecione uma requisição para começar</p>
    </div>
    <form class="bloco_conteudo form_geral" id="bloco_request">
        <div class="link">
            <?= formSelect(name: 'token', value: 'sem_token', class: 'bloco_token', lista: ['sem_token' => 'Sem token', 'token' => 'Token', 'painel' => 'Painel']) ?>
            <?= formSelect(name: 'metodo', value: 'GET', class: 'bloco_metodo', lista: ['GET' => 'GET', 'POST', 'PUT', 'DELETE']) ?>
            <?= formInput(name: 'uri', placeholder: 'Digite a URL')?>
            <div class="botao" id="botao_enviar">ENVIAR</div>
        </div>
        <div class="parametro">
            <ul>
                <li class="item" id="botao_parametro" class="hover">Parametro</li>
                <li class="item" id="botao_body">Body</li>
                <li class="item" id="botao_header">Header</li>
                <li class="item" id="botao_json">JSON</li>
                <li class="resto"></li>
                <li class="salvar display_none">salvar</li>
            </ul>
            <ol id="bloco_parametro" class="bloco_scroll bloco_parametro">
            </ol>
            <ol id="bloco_body" class="bloco_scroll bloco_parametro display_none">
            </ol>
            <ol id="bloco_header" class="bloco_scroll bloco_parametro display_none">
            </ol>
            <div id="bloco_json" class="bloco_scroll display_none">
                <textarea name="json" id="input_json" placeholder="Digite o json"></textarea>
            </div>
        </div>
        <div class="bloco_resposta">
            <p>Resposta:</p>
            <pre id="bloco_resposta"><?php print_r(['teste' => 1, 'teste_2' => 2])?>
            </pre>
        </div>
    </form>
</div>

<div id="fw_form_select"></div>

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
