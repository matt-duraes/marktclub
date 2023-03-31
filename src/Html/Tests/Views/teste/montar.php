<?php foreach ($Testes->lista as $arquivo) : ?>
    <?php
    if (
        ($acao == 'falhou' && count($arquivo->test->falhou) == 0) ||
        ($acao == 'passou' && count($arquivo->test->passou) == 0) ||
        ($acao == 'todos' && count($arquivo->test->todos) == 0)
    ) {
        continue;
    }
    ?>
    <article>
        <header>
            <h1><?= $arquivo->arquivo ?></h1>
            <p><?= $arquivo->class ?></p>
        </header>
        <?php foreach ($arquivo->test->$acao as $linha) : ?>
            <div class="teste">
                <header>
                    <?php if ($linha->tipo == 'test') : ?>
                        <h2><?= $linha->nome ?></h2>
                        <div class="status"><?= $linha->status ?></div>
                        <div class="linha"></div>
                        <div class="metodo metodo_<?= $linha->metodo ?>"><?= $linha->metodo ?></div>
                        <p class="url"><?= $linha->url ?></p>
                    <?php else : ?>
                        <h2><?= $linha->nome ?></h2>
                    <?php endif ?>
                </header>
                <ul>
                    <?php
                    if ($linha->tipo == 'test') {
                        foreach ($linha->test as $test) {
                            include 'linha_teste.php';
                        }
                        include 'resposta.php';
                    } elseif ($linha->tipo == 'erro') {
                        include 'linha_erro.php';
                    }
                    ?>
                </ul>
            </div>
        <?php endforeach ?>
    </article>
<?php endforeach; ?>
