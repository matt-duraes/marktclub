<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>LOGIN</title>

</head>

<body>

    <div id="site">

        <form action="<?= LINK ?>/__base" method="post">

            <h1>FAÇA SEU LOGIN</h1>
            <p>Digite o login e senha do banco de dados</p>

            <?php if (isset($erro) && !empty($erro)) : ?>
                <div class="erro"><?= $erro ?></div>
            <?php endif; ?>

            <input type="hidden" name="acao" value="login">

            <input type="text" name="login" placeholder="Digite seu login" value="">
            <input type="password" name="senha" placeholder="Digite sua senha" value="">

            <button>LOGAR</button>

        </form>

    </div>

    <style>
        <?php include __DIR__ . '/../css/login.css' ?>
    </style>

    <script>
        window.addEventListener('load', () => {
            document.querySelector('input[name=login]').focus();
        });
    </script>

</body>

</html>
