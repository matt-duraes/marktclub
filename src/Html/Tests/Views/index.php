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
<nav>
    <h1>Escolha os testes desejados</h1>
    <ul>
        <li class="diretorio">
            <h2>Api</h2>
            <div class="todos linha">
                <input type="checkbox" id="todos_1">
                <label for="todos_1">
                    <div class="check"><?=$check?></div>
                    Marcar todos
                </label>
            </div>
            <ul>
                <li>
                    <input checked type="checkbox" id="todos_2">
                    <label for="todos_2">
                        <div class="check"><?=$check?></div>
                        Usuario Cliente
                    </label>
                </li>
                <li>
                    <input type="checkbox" id="todos_3">
                    <label for="todos_3">
                        <div class="check"><?=$check?></div>
                        Usuario Cliente
                    </label>
                </li>
            </ul>
        </li>
        <li class="diretorio">
            <h2>Site</h2>
            <div class="todos">
                <input type="checkbox" id="todos_4">
                <label for="todos_4">
                    <div class="check"><?=$check?></div>
                    Marcar todos
                </label>
            </div>
            <ul>
                <li>
                    <input type="checkbox" id="todos_5">
                    <label for="todos_5">
                        <div class="check"><?=$check?></div>
                        Usuario Cliente
                    </label>
                </li>
                <li>
                    <input type="checkbox" id="todos_6">
                    <label for="todos_6">
                        <div class="check"><?=$check?></div>
                        Usuario Cliente
                    </label>
                </li>
            </ul>
        </li>
    </ul>
</nav>
</body>
</html>
