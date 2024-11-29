<?php

$wh = explode('x', $_GET['wh'] ?? $_GET['whc'] ?? '');
$width = array_key_exists(0, $wh) ? $wh[0] : 0;
$height = array_key_exists(1, $wh) ? $wh[1] : 0;

$mimeType = mime_content_type($path);
$mimeType = !empty($mimeType) ? mb_strtolower($mimeType, 'UTF-8') : '';
$eImagem = in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/avif']);

if (!$download && $eImagem && !empty($width) && !empty($height) && array_key_exists('whc', $_GET)) {
    gerarImagemRedirecionada($path, $mimeType, $width, $height, true);
} elseif (!$download && $eImagem && !empty($width) && !empty($height) && array_key_exists('wh', $_GET)) {
    gerarImagemRedirecionada($path, $mimeType, $width, $height);
} elseif (!$download && $eImagem && $mimeType != 'image/avif') {
    gerarImagemAvif($path, $mimeType);
}

function pegarTipoImagem($path, $mimeType)
{
    if (in_array($mimeType, ['image/jpeg', 'image/jpg'])) {
        return imagecreatefromjpeg($path);
    } elseif ($mimeType == 'image/png') {
        return imagecreatefrompng($path);
    } elseif ($mimeType == 'image/gif') {
        return imagecreatefromgif($path);
    } elseif ($mimeType == 'image/avif') {
        return imagecreatefromavif($path);
    }
}

function gerarImagemAvif($path, $mimeType)
{
    try {
        $imagem = pegarTipoImagem($path, $mimeType);

        $larguraImagem = imagesx($imagem);
        $alturaImagem = imagesy($imagem);

        $imagemNova = imagecreatetruecolor($larguraImagem, $alturaImagem);
        $white = imagecolorallocate($imagemNova, 255, 255, 255);
        imagefill($imagemNova, 0, 0, $white);
        imagecopyresampled($imagemNova, $imagem, 0, 0, 0, 0, $larguraImagem, $alturaImagem, $larguraImagem, $alturaImagem);

        header('Content-Type: image/avif');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
        header('Cache-Control: max-age=86400');
        header('Pragma: cache');

        imageavif($imagemNova, quality: 90, speed: 8);

        imagedestroy($imagem);
        imagedestroy($imagemNova);
        exit();
    } catch (\Throwable) {
        return '';
    }
}

function gerarImagemRedirecionada($path, $mimeType, $largura, $altura, bool $cortar = false)
{
    try {
        $imagem = pegarTipoImagem($path, $mimeType);

        $larguraImagem = imagesx($imagem);
        $alturaImagem = imagesy($imagem);
        $proporcaoOriginal = $larguraImagem / $alturaImagem;
        $proporcaoFinal = $largura / $altura;

        if ($proporcaoOriginal > $proporcaoFinal) {
            $larguraNova = $largura;
            $alturaNova = round($largura / $proporcaoOriginal);
        } else {
            $alturaNova = $altura;
            $larguraNova = round($altura * $proporcaoOriginal);
        }
        if ($alturaNova < $altura) {
            $larguraNova = round($altura * $proporcaoOriginal);
            $alturaNova = $altura;
        }
        if ($larguraNova < $largura) {
            $larguraNova = $largura;
            $alturaNova = round($largura / $proporcaoOriginal);
        }

        $imagemRedimensionada = imagecreatetruecolor($larguraNova, $alturaNova);
        $white = imagecolorallocate($imagemRedimensionada, 255, 255, 255);
        imagefill($imagemRedimensionada, 0, 0, $white);
        imagecopyresampled($imagemRedimensionada, $imagem, 0, 0, 0, 0, $larguraNova, $alturaNova, $larguraImagem, $alturaImagem);

        if ($cortar) {
            $corteX = 0;
            if ($larguraNova > $largura) {
                $corteX = round(($larguraNova - $largura) / 2);
            }

            $corteY = 0;
            if ($alturaNova > $altura) {
                $corteY = round(($alturaNova - $altura) / 2);
            }

            $imagemCortada = imagecrop($imagemRedimensionada, [
                'x'      => $corteX,
                'y'      => $corteY,
                'width'  => $largura,
                'height' => $altura
            ]);
        }

        header('Content-Type: image/avif');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
        header('Cache-Control: max-age=86400');
        header('Pragma: cache');
        if ($cortar) {
            imageavif($imagemCortada, quality: 90, speed: 8);
        } else {
            imageavif($imagemRedimensionada, quality: 90, speed: 8);
        }

        imagedestroy($imagem);
        imagedestroy($imagemRedimensionada);
        if ($cortar) {
            imagedestroy($imagemCortada);
        }
        exit();
    } catch (\Throwable) {
        return '';
    }
}
