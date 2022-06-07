<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>TESTE</title>

    <style>
        <?php
        include __DIR__ . '/../Resources/css/resetar.css';
        include __DIR__ . '/../Resources/css/template.css';
        include __DIR__ . '/../Resources/css/index.css';
        ?>
    </style>

    <script>
        <?php include __DIR__ . '/../Resources/js/index.js'; ?>
    </script>

</head>

<body>

    <div id="site">
        <div class="header">
            <h1>TESTES</h1>
        </div>
        <?php
        $lista = listarArquivoDiretorio(ROOT . '/tests/Api', final: 'Test', ext: ['php']);
        if ($lista) :
        ?>

            <form action="<?= LINK; ?>/__tests" method="get" class="lista">
                <input type="hidden" id="LINK" value="<?= LINK ?>">
                <div class="checkbox">
                    <input type="checkbox" id="id_marcar" value="marcar">
                    <label for="id_marcar">Marcar/Desmarcar todos.</label>
                    <i>
                        <svg height="12" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                            <path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z" />
                        </svg>
                    </i>
                </div>

                <div class="conteudo">
                    <p>Selecione os testes que deseja executar:</p>
                    <?php
                    foreach ($lista as $val) :
                        $val = preg_replace('/\.php$/', '', $val);
                        $nome = trim(preg_replace(['/Test$/', '/([A-Z])/'], ['', ' $0'], $val));
                    ?>
                        <div class="checkbox">
                            <input type="checkbox" class="input_teste" name="arquivo" id="id_<?= $val; ?>" value="<?= $val; ?>">
                            <label for="id_<?= $val; ?>"><?= $nome; ?></label>
                            <i>
                                <svg height="12" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                    <path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z" />
                                </svg>
                            </i>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="footer">
                    <div class="botao" id="botao_download">EXECUTAR</div>
                </div>
            </form>
        <?php endif; ?>

    </div>

</body>

</html>
