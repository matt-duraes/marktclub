<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">

    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>DATABASE</title>

</head>
<body>

<div id="site">
    <header>
        <h1>DATABASE</h1>
    </header>

    <?php if ($lista): ?>

    <form action="<?=LINK;?>/__base" method="post" class="lista">
        <input type="hidden" name="acao" value="criar">

        <div class="todo">
            <input type="checkbox" name="marcar" id="id_marcar" value="marcar">
            <label for="id_marcar">Marcar/Desmarcar todos.</label>
        </div>

        <div class="conteudo">
            <p>Selecione as tabelas que deseja criar.</p>
            <?php foreach ($lista as $val): ?>
            <div class="bloco">
                <input type="checkbox" name="tabela[]" id="id_<?=$val;?>" value="<?=$val;?>">
                <label for="id_<?=$val;?>"><?=$val;?></label>
            </div>
            <?php endforeach;?>
        </div>

        <div class="aceito">
            <input type="checkbox" name="aceito" id="id_aceito" value="sim">
            <label for="id_aceito">Ao executar esse comando você irá deletar as tabelas e registros selecionados. Marque o caixa para continuar.</label>
        </div>

        <button>EXECUTAR</button>
    </form>

    <?php else: ?>
    <div class="zero">NENHUM BANCO CONFIGURADO</div>
    <?php endif;?>

</div>

<style>
<?php include __DIR__ . '/../css/html.css';?>
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
<?php include __DIR__ . '/../js/html.js';?>
</script>

</body>
</html>