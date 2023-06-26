<?php

$mensagem = '';
$login = '';
$senha = '';
if (isset($_POST['login']) && isset($_POST['senha'])) {
    if ($_POST['login'] == env('APP_LOGIN') && $_POST['senha'] == env('APP_SENHA')) {
        cookie(nome: 'APP_LOGADO', valor: true, hora: 1);
        header("Refresh:0");
        exit();
    } else {
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $mensagem = 'Login ou senha incorreto.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofolow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Login</title>

</head>

<body>

    <div id="site">
        <form action="" method="post">
            <header>
                <legend>FAÇA SEU LOGIN</legend>
                <p>Digite seu login e senha para continuar</p>
            </header>
            <?php if (!empty($mensagem)) : ?>
                <div class="erro"><?= $mensagem; ?></div>
            <?php endif; ?>
            <div class="input">
                <input type="text" focus name="login" id="login" placeholder="Login..." value="<?= $login; ?>">
                <input type="password" name="senha" placeholder="Senha..." value="<?= $senha; ?>">
            </div>
            <button>LOGAR</button>
        </form>
    </div>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">
    <script>
        window.addEventListener('load', () => {
            document.getElementById('login').focus();
        });
    </script>
    <style>
        * {
            box-sizing: border-box
        }

        a,
        abbr,
        acronym,
        address,
        applet,
        article,
        aside,
        audio,
        b,
        big,
        blockquote,
        body,
        button,
        canvas,
        caption,
        center,
        cite,
        code,
        dd,
        del,
        details,
        dfn,
        div,
        dl,
        dt,
        em,
        embed,
        fieldset,
        figcaption,
        figure,
        footer,
        form,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        header,
        hgroup,
        html,
        i,
        iframe,
        img,
        input,
        ins,
        kbd,
        label,
        legend,
        li,
        main,
        mark,
        menu,
        nav,
        object,
        ol,
        output,
        p,
        pre,
        q,
        ruby,
        s,
        samp,
        section,
        select,
        small,
        span,
        strike,
        strong,
        sub,
        summary,
        sup,
        table,
        tbody,
        td,
        textarea,
        tfoot,
        th,
        thead,
        time,
        tr,
        tt,
        u,
        ul,
        var,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-weight: 400;
            font-size: 1em;
            vertical-align: baseline;
            box-sizing: border-box;
            word-wrap: break-word;
            text-shadow: none
        }

        button,
        input,
        select,
        textarea {
            cursor: pointer;
            background: 0 0;
            font-size: 100%;
            font-family: inherit;
            resize: none;
            border-radius: 0;
            background: 0 0;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none
        }

        button:focus,
        input:focus,
        select:focus,
        textarea:focus {
            outline: 0
        }

        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        main,
        menu,
        nav,
        section {
            display: block
        }

        body {
            line-height: 1;
            font-size: 62.5%;
            overflow-x: hidden;
        }

        ol,
        ul {
            list-style: none
        }

        blockquote,
        q {
            quotes: none
        }

        blockquote:after,
        blockquote:before,
        q:after,
        q:before {
            content: '';
            content: none
        }

        a,
        a:active,
        a:hover,
        a:link,
        a:visited {
            outline: 0;
            text-decoration: none;
            color: inherit
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }

        i {
            font-style: normal
        }

        input[type=number] {
            -moz-appearance: textfield
        }

        input::-webkit-inner-spin-button,
        input::-webkit-outer-spin-button {
            -webkit-appearance: none
        }

        input[type=search]::-webkit-search-cancel-button,
        input[type=search]::-webkit-search-decoration,
        input[type=search]::-webkit-search-results-button,
        input[type=search]::-webkit-search-results-decoration {
            display: none
        }

        body {
            font-family: 'Roboto', Arial, Helvetica, sans-serif;
            font-size: 62.5%;
            background-color: #F6F6F6;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #site {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        form {
            width: 100%;
            max-width: 350px;
            background-color: #FFF;
            box-shadow: 0 5px 5px rgba(0, 0, 0, .1);
            display: flex;
            flex-direction: column;
            padding: 40px 20px;
            /* border-top: 3px solid #23B9A9; */
            border-radius: 5px;
            align-items: center;
        }

        form header {
            width: 100%;
            margin-bottom: 40px;
        }

        form legend {
            color: #23B9A9;
            font-size: 2.4em;
            font-weight: bold;
        }

        form p {
            font-size: 1.4em;
            color: #666;
            line-height: 1.5em;
            margin-top: 5px;
        }

        form .erro {
            font-size: 1.4em;
            color: #FF6C60;
            margin-bottom: 10px;
        }

        form .input {
            width: calc(100% + 20px);
            display: flex;
            flex-direction: column;
            border-left: 2px solid #23B9A9;
            margin-left: -20px;
            padding-left: 18px;
        }

        form input {
            width: 100%;
            height: 40px;
            padding: 0 10px;
            background-color: #EEE;
            border-radius: 3px;
            font-size: 1.4em;
        }

        form .input input:last-child {
            margin-top: 15px;
        }

        form button {
            font-size: 1.4em;
            font-weight: bold;
            margin-top: 30px;
            color: #23B9A9;
        }
    </style>

</body>

</html>
