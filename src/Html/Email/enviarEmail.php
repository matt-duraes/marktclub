<?php

include __DIR__ . '/../../../vendor/autoload.php';

use Helpers\CryptHelper;
use PHPMailer\PHPMailer\PHPMailer;

if (!isset($_POST['hash'])) {
    exit();
}
$hash = $_POST['hash'];
$lista = jsonDecode($hash);

if (!$lista) {
    exit();
}

$log = '';
$Crypt = new CryptHelper();
foreach ($lista as $dado) {
    $r = $Crypt->decode($dado);
    $sleep = $r['sleep'];
    $debug = $r['debug'];
    $host = $r['host'];
    $usuario = $r['usuario'];
    $senha = $r['senha'];
    $porta = $r['porta'];
    $email_envio = $r['email_envio'];
    $email_resposta = $r['email_resposta'];
    $email = $r['email'];
    $nome = $r['nome'];
    $copia = $r['copia'];
    $copia_oculta = $r['copia_oculta'];
    $arquivo = $r['arquivo'];
    $titulo = $r['titulo'];
    $mensagem = $r['mensagem'];
    $mensagemTexto = $r['mensagemTexto'];

    if (!empty($sleep) && is_int($sleep) && $sleep > 0) {
        sleep($sleep);
    }

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Priority = 1;
    $mail->SMTPDebug = $debug ? 2 : 0;

    if ($host == 'smtp.gmail.com') {
        $mail->SMTPSecure = 'tls';
    }

    $mail->SMTPSecure = PHPMAILER::ENCRYPTION_STARTTLS;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->Host = gethostbyname($host);
    $mail->Username = $usuario;
    $mail->Password = $senha;
    $mail->Port = $porta;
    $mail->setFrom($email_envio[1], $email_envio[0]);
    $mail->addReplyTo($email_resposta[1], $email_resposta[0]);
    $mail->addAddress($email, $nome);

    if ($copia) {
        foreach ($copia as $lista) {
            if (is_array($lista)) {
                $mail->addCC($lista[1], $lista[0]);
            } elseif (is_string($lista)) {
                $mail->addCC($lista);
            }
        }
    }
    if ($copia_oculta && is_array($copia_oculta)) {
        foreach ($copia_oculta as $lista) {
            if (is_array($lista)) {
                $mail->addBCC($lista[1], $lista[0]);
            } elseif (is_string($lista)) {
                $mail->addBCC($lista);
            }
        }
    }

    if ($arquivo && is_array($arquivo)) {
        foreach ($arquivo as $lista) {
            if (is_array($lista)) {
                $mail->addAttachment($lista[1], $lista[0]);
            } elseif (is_string($lista)) {
                $mail->addAttachment($lista);
            }
        }
    }

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $titulo;
    $mail->Body = $mensagem;
    $mail->AltBody = trim(preg_replace(['/[\n\r]/', '/( ){2,}/'], [' ', ' '], strip_tags($mensagemTexto)));

    if (!$debug) {
        $mail->send();
    } else {
        ob_start();
        $mail->send();
        $log .= ob_get_clean();

        $arquivoLog = fopen(__DIR__ . '/../../../files/log/phpmailer.txt', 'w+');
        fwrite($arquivoLog, $log);
        fclose($arquivoLog);
    }
}
