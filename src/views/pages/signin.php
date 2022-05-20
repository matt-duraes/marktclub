<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css">
</head>

<body>
    <div id="site">
        <figure>
            <img src="<?=$base;?>/assets/images/logo.png" alt="Logo Markt Club">
        </figure>
        <form action="<?=$base;?>/login" method="POST">
            <legend>FAÇA SEU LOGIN</legend>
            <p>Digite seu Email no campo abaixo e clique em logar para fazer seu login.</p>

            <div class="input">
                <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />
            </div>
            <div class="input">
                <input placeholder="Digite sua senha" class="input" type="password" name="password" />
            </div>
            <input class="button" type="submit" value="Acessar o sistema" />
        </form>
    </div>
</body>

</html>
