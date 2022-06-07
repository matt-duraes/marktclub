<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TESTS</title>
    <style>
        <?php
        include __DIR__ . '/../Resources/css/resetar.css';
        include __DIR__ . '/../Resources/css/template.css';
        include __DIR__ . '/../Resources/css/teste.css';
        ?>
    </style>
    <script>
        <?php include __DIR__ . '/../Resources/js/teste.js' ?>
    </script>
</head>

<body>
    <div id="site">
        <div class="header">
            <h1>TESTES</h1>

            <a class="botao" id="botao_voltar" href="<?= LINK ?>/__tests">VOLTAR</a>
            <div class="botao" id="botao_reload" class="reload">RECARREGAR</div>

            <div class="grow"></div>

            <button class="hover" id="botao_falhou">FALHOU <span>(<?= $Testes->falhou ?>)</span></button>
            <button id="botao_passou">PASSOU <span>(<?= $Testes->passou ?>)</span></button>
            <button id="botao_todos">TODOS <span>(<?= $Testes->todos ?>)</span></button>
        </div>

        <div class="bloco" id="bloco_falhou">
            <?php
            if ($Testes->falhou == 0) {
                include 'teste/sem_falha.php';
            } else {
                $acao = 'falhou';
                include 'teste/montar.php';
            }
            ?>
        </div>
        <div class="bloco" id="bloco_passou">
            <?php
            if ($Testes->passou == 0) {
                include 'teste/sem_passou.php';
            } else {
                $acao = 'passou';
                include 'teste/montar.php';
            }
            ?>
        </div>
        <div class="bloco" id="bloco_todos">
            <?php
            $acao = 'todos';
            include 'teste/montar.php';
            ?>
        </div>
    </div>
</body>

</html>
