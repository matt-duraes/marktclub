<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{$titulo}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
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
        {{$assunto}}
    </div>

    <!-- start body -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">

        <!-- start logo -->
        <tr>
            <td align="center" bgcolor="#F6F6F6">
                <!--[if (gte mso 9)|(IE)]>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                    <tr>
                        <td align="center" valign="top" width="600">
                    <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 24px 20px 24px;">
                            <a href="{{$LINK_SITE}}" target="_blank" style="display: inline-block;">
                                <img src="{{$LINK}}/images/logo_email.png" alt="{{$HOST}}" border="0"
                                    height="50" style="
                                        display: block; height: 65px; max-height: 65px; min-height: 65px;
                                    "
                                >
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
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;" >
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
                                color: #23b9a9
                            ">{{$titulo}}</h1>
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
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
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
                            <p style="margin: 0;">{{$mensagem}}</p>
                        </td>
                    </tr>
                    <!-- end message -->

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
                            <p style="margin: 0;">{{$HOST}}</p>
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
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 700px;">

                    <!-- start permission -->
                    @if(isset($acao) && !empty($acao)):
                    <tr>
                        <td bgcolor="#F6F6F6" style="
                            padding: 12px 20px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            line-height: 20px;
                            color: #666;
                        ">
                            <p style="margin: 0;">
                                Você recebeu este e-mail porque recebemos uma solicitação de <strong>{{$acao}}</strong> para sua conta.
                                Caso não tenha feito essa ação, exclua este email. Se achou essa ação suspeira,
                                verifique sua conta.
                            </p>
                        </td>
                    </tr>
                    @elseif(isset($acaoTexto) && !empty($acaoTexto)):
                    <tr>
                        <td bgcolor="#F6F6F6" style="
                            padding: 12px 20px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            line-height: 20px;
                            color: #666;
                        ">
                            <p style="margin: 0;">{{$acaoTexto}}</p>
                        </td>
                    </tr>
                    @else:
                    <tr>
                        <td bgcolor="#F6F6F6" style="
                            padding: 12px 20px;
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            line-height: 20px;
                            color: #666;
                        ">
                            <p style="margin: 0;">
                                Você recebeu este e-mail por causa da sua conta na plataforma.
                                Caso não tenha feito essa solicitação, exclua este email. Se achou essa ação suspeira,
                                verifique sua conta.
                            </p>
                        </td>
                    </tr>
                    @endif;
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
                            @if(isset($browser) && !empty($browser)):
                            <p style="margin: 0 0 10px 0;">
                                Caso não esteja visualizando esse e-mail, abra no seu navegador:
                                <a href="{{$browser}}" target="_blank">Ver e-mail no navegador</a>.
                            </p>
                            @endif;

                            @if(isset($idPublico) && !empty($idPublico)):
                            <p style="margin: 0;">
                                Para parar de receber esses e-mails, você pode
                                <a href="{{LINK_API}}/email/remover-cadastro/{{$idPublico}}" target="_blank">cancelar</a>
                                a inscrição a qualquer momento. (Você continuará recebendo e-mail solicitados por você
                                como recuperação de senha, validações e etc).
                            </p>
                            @endif;

                            <p style="margin: 20px 0 0 0;"></p>
                            <p style="margin: 0; font-size: 12px;">{{$HOST}}</p>
                            <p style="margin: 0; font-size: 12px;">Em: {{$data}}</p>
                            <p style="margin: 0; font-size: 12px;">IP: {{$ip}}</p>
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
