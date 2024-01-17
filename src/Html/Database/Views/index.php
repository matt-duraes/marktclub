<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bando de dados</title>
    <style>
        <?php
        include __DIR__ . '/../Resources/css/resetar.css';
        include __DIR__ . '/../Resources/css/index.css';
        ?>
    </style>

    <script>
        <?php include __DIR__ . '/../Resources/js/all.js'; ?>
    </script>
</head>
<body>
    <div id="site">
        <nav>
            <h1>Escolha as tabelas</h1>
            <ul>
                <div class="todos">
                    <input class="input_diretorio" type="checkbox" id="input_marcar_todos">
                    <label for="input_marcar_todos">
                        <div class="check"><?=iconeCheck(12)?></div>
                        <p>Marcar todos</p>
                    </label>
                </div>
                <li>
                    <ul class="lista_teste">
                        <?php foreach($lista as $tabela): ?>
                        <li class="bloco_menu">
                            <div class="grupo">
                                <input class="input_classe" type="checkbox" id="input_<?= $tabela ?>" value="<?=$tabela?>">
                                <label for="input_<?= $tabela ?>">
                                    <div class="check"><?=iconeCheck(12)?></div>
                                    <p><?= $tabela ?></p>
                                </label>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="bloco_retorno" id="bloco_retorno">
            <div class="bloco_loading display_none" id="bloco_loading">
                <p><span id="bloco_nome_atual"></span> - <span id="bloco_numero_atual">1</span> de <span id="bloco_numero_total">50</span></p>
                <div class="botao botao_cancelar" id="botao_cancelar">CANCELAR</div>
                <div class="barra"><span></span></div>
            </div>
            <div class="bloco_cancelar display_none" id="bloco_cancelar">
                Aguardando criação atual finalizar...
            </div>

            <div class="bloco_header display_none" id="bloco_header">
                <i class="passou"><?= iconeLike(16) ?> <span id="bloco_numero_passou"></span></i>
                <i class="falhou"><?= iconeDeslike(16) ?> <span id="bloco_numero_falhou"></span></i>
            </div>

            <div class="bloco_ok display_none" id="bloco_ok">
                <i><?= iconeLike(40) ?></i>
                <h1>OK</h1>
                <p>Todos os testes passaram com sucesso!</p>
            </div>
            <div class="scroll">
                <div id="bloco_conteudo" class="conteudo">

                </div>
            </div>
        </div>
    </div>
    <div class="botao_acao botao_comecar" id="botao_comecar">CRIAR TABELAS</div>
    <form action="/">
        <input type="text" name="LINK" id="LINK" value="<?=LINK?>">
    </form>
</body>
</html>
