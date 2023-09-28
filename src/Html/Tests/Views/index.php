<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testes</title>
    <style>
        <?php
        include __DIR__ . '/../Resources/css/resetar.css';
        include __DIR__ . '/../Resources/css/index.css';
        ?>
    </style>

    <script>
        <?php include __DIR__ . '/../Resources/js/index.js'; ?>
    </script>
</head>
<body>
    <div id="site">
        <nav>
            <h1>Escolha os testes desejados</h1>
            <ul>
                <?php $i = 0; ?>
                <?php foreach($menu as $diretorio): ?>
                    <?php if(!isset($diretorio->lista) || empty($diretorio->lista)) continue; ?>
                    <?php $i++; ?>
                <li class="bloco_diretorio menu_fechado">
                    <h2 class="botao_abrir_diretorio"><?= $diretorio->diretorio ?></h2>
                    <div class="todos">
                        <input class="input_diretorio" type="checkbox" id="todas_classes_<?=$i?>">
                        <label for="todas_classes_<?=$i?>">
                            <div class="check"><?=iconeCheck(12)?></div>
                            <p>Marcar todos</p>
                        </label>
                    </div>
                    <ul class="lista_teste">
                        <?php foreach($diretorio->lista as $arquivo): ?>
                            <?php $i++; ?>
                        <li class="bloco_menu">
                            <div class="grupo">
                                <input class="input_classe" data-diretorio="<?= $diretorio->diretorio ?>" data-class="<?= $arquivo->class ?>" type="checkbox" id="todos_metodos_<?= $i ?>">
                                <label for="todos_metodos_<?= $i ?>">
                                    <div class="check"><?=iconeCheck(12)?></div>
                                    <p><?= $arquivo->nome ?></p>
                                </label>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="bloco_retorno" id="bloco_retorno">
            <div class="bloco_loading display_none" id="bloco_loading">
                <p><span id="bloco_nome_atual"></span> - <span id="bloco_numero_atual">1</span> de <span id="bloco_numero_total">50</span></p>
                <div class="botao botao_cancelar" id="botao_cancelar_teste">CANCELAR</div>
                <div class="barra"><span></span></div>
            </div>
            <div class="bloco_cancelar display_none" id="bloco_cancelar">
                Aguardando o teste atual finalizar...
            </div>

            <div class="bloco_header display_none" id="bloco_header">
                <i class="passou"><?= iconeLike(16) ?> <span id="bloco_numero_passou"></span></i>
                <i class="falhou"><?= iconeDeslike(16) ?> <span id="bloco_numero_falhou"></span></i>

                <div class="grow"></div>

                <div class="botao ativo" id="botao_geral_todos">Todos</div>
                <div class="botao" id="botao_geral_passou">Passou</div>
                <div class="botao" id="botao_geral_falhou">Falhou</div>
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
    <div class="botao_acao botao_comecar" id="botao_fazer_teste">FAZER TESTE</div>
    <form action="/">
        <input type="text" name="LINK" id="LINK" value="<?=LINK?>">
    </form>
</body>
</html>
