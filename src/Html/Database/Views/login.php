<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>LOGIN</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">
    <style>
        <?php
        include __DIR__ . '/../Resources/css/resetar.css';
        include __DIR__ . '/../Resources/css/login.css';
        ?>
    </style>

    <script>
        window.addEventListener('load', () => {
            document.querySelector('input[name=login]').focus();
        });
    </script>

</head>

<body>
    <div id="site">
        <form action="<?= LINK ?>/__base" method="post">
            <header>
                <legend>FAÇA SEU LOGIN</legend>
                <p>Digite o login e senha do banco de dados</p>
            </header>
            <div class="erro"><?= $erro ?></div>
            <div class="input">
                <input type="hidden" focus name="acao" value="login">
                <input type="text" focus name="login" id="login" placeholder="Login..." value="">
                <input type="password" name="senha" placeholder="Senha..." value="">
            </div>
            <button>LOGAR</button>
        </form>
    </div>
</body>

</html>
