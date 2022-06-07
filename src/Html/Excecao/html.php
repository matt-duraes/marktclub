<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title><?= $titulo ?></title>
</head>

<body>

    <div id="pagina_erro">
        <?php if (defined('ROOT') && defined('LINK') && file_exists(ROOT . '/public/images/logo_sistema.png')) : ?>
            <figure>
                <img src="<?= LINK ?>/images/logo_sistema.png">
            </figure>
        <?php endif; ?>
        <i>!</i>
        <header>
            <?php if (!empty($titulo)) : ?>
                <h1 class="titulo"><?= $titulo ?></h1>
            <?php endif; ?>
            <?php if (!empty($mensagem)) : ?>
                <p class="mensagem"><?= $mensagem ?></p>
            <?php endif; ?>
            <p class="texto">Para continuar a navegar, clique no botão abaixo:</p>
        </header>

        <?php if (isset($linkRetorno) && !empty($linkRetorno)) : ?>
            <a href="<?= $linkRetorno ?>">RETORNAR</a>
        <?php elseif (defined('LINK')) : ?>
            <a href="<?= LINK ?>">RETORNAR</a>
        <?php endif; ?>

        <?php if (SISTEMA == 'LOCALHOST' && isset($erroArquivo, $erroLinha, $erroTrace) && (!empty($erroArquivo) || !empty($erroLinha) || !empty($erroTrace))) : ?>
            <div class="localhost">
                <h2>Dados para localhost:</h2>
                <p><?= $erroArquivo ?>. Linha: <?= $erroLinha ?></p>
                <?php if ($erroTrace) : ?>
                    <h3>Trace2:</h3>
                    <?php foreach ($erroTrace as $linha) : ?>
                        <p><?= $linha ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style media="screen">
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
            -webkit-font-smoothing: antialiased;
            -moz-font-smoothing: antialiased
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
            text-decoration: none
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

        html {
            font-size: 62.5%;
        }

        body {
            font-family: 'Open Sans', sans-serif;
        }

        #pagina_erro {
            width: 100vw;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        figure {
            width: 100%;
            text-align: center;
            margin-top: 50px;
        }

        i {
            width: 80px;
            height: 80px;
            line-height: 80px;
            margin-top: 40px;
            border: 1px solid #CCC;
            text-align: center;
            font-size: 5rem;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #AAA;
            border-radius: 50%;
        }

        header {
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin: 40px 0 20px 0;
        }

        h1 {
            font-size: 3rem;
            font-weight: bold;
            color: #FF6C60;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 2rem;
            color: #FF6C60;
        }

        h3 {
            font-size: 1.6rem;
            color: #666;
        }

        .localhost {
            margin-top: 80px;
        }

        .localhost h2 {
            margin-bottom: 10px;
        }

        .localhost h3 {
            margin: 20px 0 10px 0;
        }

        p {
            font-size: 1.6rem;
            line-height: 1.5em;
            color: #666;
        }

        p.mensagem {
            margin-bottom: 50px;
        }

        p.texto {
            font-size: 1.4rem;
            color: #999;
        }

        #pagina_erro>a {
            height: 40px;
            line-height: 40px;
            color: #FFF;
            background-color: #18718B;
            padding: 0 20px;
            border-radius: 3px;
            font-size: 1.2rem;
        }
    </style>

</body>

</html>
