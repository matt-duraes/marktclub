<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 400;
                src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 700;
                src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
            }
        }

        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        table {
            border-collapse: collapse !important;
        }

        a {
            color: #1a82e2;
        }

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>

</head>

<body bgcolor="#f6f6f6">

    <div class="preheader" style="
        max-width: 0; max-height: 0; overflow: hidden;
        font-size: 1px; line-height: 1px; color: #F6F6F6;
    ">
        <?= $assunto ?>
    </div>

    <!-- start body -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">

        <!-- start logo -->
        <tr>
            <td align="center" bgcolor="#F6F6F6">
                <!--[if (gte mso 9)|(IE)]>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="700">
                    <tr>
                        <td align="center" valign="top" width="700">
                    <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 24px 30px 24px;">
                            <a href="<?= LINK ?>" target="_blank" style="display: inline-block;">
                                <img src="<?= !empty($logo) ? $logo : LINK_PADRAO . '/images/email/logo.png' ?>" height="50" style="
                                        display: block; height: 65px; max-height: 65px; min-height: 65px;
                                    ">
                            </a>
                        </td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
                        </td>
                    </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
        <!-- end logo -->

        <!-- start hero -->
        <tr>
            <td align="center" bgcolor="#F6F6F6">
                <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="700">
        <tr>
        <td align="center" valign="top" width="700">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;">
                    <tr>
                        <td align="left" bgcolor="#ffffff" style="
                            border-radius: 10px 10px 0 0;
                            padding: 36px 35px 0;
                            font-family: Helvetica, Arial, sans-serif;
                        ">
                            <h1 style="
                                margin: 0; font-size: 32px;
                                font-weight: 700; letter-spacing: -1px;
                                line-height: 48px;
                                color: <?= !empty($cor) ? $cor : '#23b9a9' ?>
                            "><?= $titulo ?></h1>
                        </td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
            </td>
        </tr>
        <!-- end hero -->

        <!-- start copy block -->
        <tr>
            <td align="center" bgcolor="#F6F6F6">
                <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="700">
        <tr>
        <td align="center" valign="top" width="700">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;">

                    <!-- start message -->
                    <tr>
                        <td align="left" bgcolor="#ffffff" style="
                            padding: 24px 35px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 16px;
                            line-height: 24px;
                        ">
                            <p style="margin: 0;"><?= $mensagem ?></p>
                        </td>
                    </tr>
                    <!-- end message -->

                    <?php if (!empty($botaoTexto) && !empty($botaoLink)) : ?>
                        <!-- start button -->
                        <tr>
                            <td align="left" bgcolor="#ffffff">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td align="center" bgcolor="<?= !empty($cor) ? $cor : '#1a82e2' ?>" style="border-radius: 35px;">
                                                        <a href="<?= $botaoLink ?>" target="_blank" style="
                                                        display: inline-block;
                                                        padding: 10px 36px;
                                                        font-family: Helvetica, Arial, sans-serif;
                                                        font-size: 16px;
                                                        color: #ffffff;
                                                        text-decoration: none;
                                                        border-radius: 35px;
                                                    "><?= $botaoTexto ?></a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- end button -->

                        <!-- start copy -->
                        <tr>
                            <td align="left" bgcolor="#ffffff" style="
                            padding: 24px 35px;
                            font-family: Helvetica, Arial, sans-serif;
                            color: #666;
                            font-size: 14px;
                            line-height: 24px;
                        ">
                                <p style="margin: 0; word-break: break-word;">
                                    Caso não consiga clicar no botão acima, copie e cole esse link em seu navegador:<br>
                                    <?= $botaoLink ?>
                                </p>
                            </td>
                        </tr>
                        <!-- end copy -->
                    <?php endif; ?>

                    <?php if (!empty($codigo)) : ?>
                        <!-- start code -->
                        <tr>
                            <td bgcolor="#FFFFFF" style="border-radius: 6px; padding: 24px 35px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 30px; font-weight: bold">
                                <p style="margin-block-start: 0; margin-block-end: 0; display: inline-block; color: #000; border-radius: 6px; letter-spacing: 5px;"><?= $codigo ?></p>
                            </td>
                        </tr>
                        <!-- end code -->
                    <?php endif; ?>

                    <?php if (!empty($posMensagem)) : ?>
                        <tr>
                            <td align="left" bgcolor="#ffffff" style="
                            padding: 24px 35px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 16px;
                            line-height: 24px;
                        ">
                                <p style="margin: 0;"><?= $posMensagem ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($observacao)) : ?>
                        <tr>
                            <td height="20" bgcolor="#FFFFFF"></td>
                        </tr>
                        <tr>
                            <td align="left" bgcolor="#ffffff" style="
                            padding: 24px 35px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 16px;
                            line-height: 24px;
                            background-color: #FF6C60;
                            color: #FFFFFF;
                            text-align: center
                        ">
                                <p style="margin: 0;"><?= $observacao ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td height="20" bgcolor="#FFFFFF"></td>
                        </tr>
                    <?php endif; ?>

                    <!-- start att -->
                    <tr>
                        <td align="left" bgcolor="#ffffff" style="
                            border-radius: 0 0 10px 10px;
                            padding: 24px 35px 40px 35px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 12px; color: #666;
                        ">
                            <p style="
                                width: 50px;
                                height: 1px;
                                background-color: #DDD;
                                margin: 0 0 10px 0;
                            "></p>
                            <p style="margin: 0;">Att,</p>
                            <p style="margin: 0;"><?= $HOST ?></p>
                        </td>
                    </tr>
                    <!-- end att -->

                </table>
                <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
            </td>
        </tr>
        <!-- end copy block -->

        <!-- start footer -->
        <tr>
            <td align="center" bgcolor="#F6F6F6" style="padding: 24px 0;">
                <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="700">
        <tr>
        <td align="center" valign="top" width="700">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;">

                    <!-- start permission -->
                    <?php if (!empty($acao) && str_starts_with($acao, '!')) : ?>
                        <tr>
                            <td bgcolor="#F6F6F6" style="
                            padding: 12px 20px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            line-height: 20px;
                            color: #666;
                        ">
                                <p style="margin: 0;"><?= preg_replace('/^\!/', '', $acao) ?></p>
                            </td>
                        </tr>
                    <?php elseif (!empty($acao)) : ?>
                        <tr>
                            <td bgcolor="#F6F6F6" style="
                            padding: 12px 20px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            line-height: 20px;
                            color: #666;
                        ">
                                <p style="margin: 0;">
                                    Você recebeu este e-mail porque recebemos uma solicitação de <strong><?= $acao ?></strong> para sua conta.
                                    Caso não tenha feito essa ação, exclua este email. Se achou essa ação suspeira,
                                    verifique sua conta.
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <!-- end permission -->

                    <!-- start unsubscribe -->
                    <tr>
                        <td bgcolor="#F6F6F6" style="
                            padding: 12px 20px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            line-height: 20px;
                            color: #666;
                        ">
                            <?php if (!empty($linkBrowser)) : ?>
                                <p style="margin: 10px 0 0 0;">
                                    Caso não esteja visualizando esse e-mail, abra no seu navegador:
                                    <a href="<?= $linkBrowser ?>" target="_blank">Ver e-mail no navegador</a>.
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($linkRemover)) : ?>
                                <p style="margin: 10px 0 0 0;">
                                    Para parar de receber esses e-mails, você pode
                                    <a href="<?= $linkRemover ?>" target="_blank">cancelar</a>
                                    a inscrição a qualquer momento. (Você continuará recebendo e-mail solicitados por você
                                    como recuperação de senha, validações e etc).
                                </p>
                            <?php endif; ?>

                            <?php if ($privado) : ?>
                                <p style="
                                    width: 50px;
                                    height: 1px;
                                    background-color: #CCC;
                                    margin: 40px 0 10px 0;
                                "></p>
                                <p style="margin: 20px 0; color: #999; font-size: 12px;">
                                    Esta mensagem pode conter informação confidencial ou privilegiada, sendo seu sigilo protegido por lei. Se você não for o destinatário ou a pessoa autorizada a receber esta mensagem, não pode usar, copiar ou divulgar as informações nela contidas ou tomar qualquer ação baseada nessas informações. Se você recebeu esta mensagem por engano, por favor, avise imediatamente ao remetente, respondendo o e-mail e em seguida apague-a. Agradecemos sua cooperação.
                                </p>
                                <p style="margin: 0 0 20px 0; color: #999; font-size: 12px;">
                                    This message may contain confidential or privileged information and its confidentiality is protected by law. If you are not the addressed or authorized person to receive this message, you must not use, copy, disclose or take any action based on it or any information herein. If you have received this message by mistake, please advise the sender immediately by replying to the email and then deleting it. Thank you for your cooperation.
                                </p>
                            <?php endif; ?>

                            <p style="margin: 40px 0 0 0;"></p>
                            <p style="margin: 0; font-size: 12px;"><?= $HOST ?></p>
                            <p style="margin: 0; font-size: 12px;">Em: <?= date('d/m/Y H:i:s') ?></p>
                            <p style="margin: 0; font-size: 12px;">IP: <?= ip() ?></p>
                        </td>
                    </tr>
                    <!-- end unsubscribe -->

                </table>
                <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
            </td>
        </tr>
        <!-- end footer -->

    </table>
    <!-- end body -->

</body>

</html>
