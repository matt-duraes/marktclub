<?php

$wh = explode('x', $_GET['wh'] ?? $_GET['whc'] ?? '');
$width = array_key_exists(0, $wh) ? $wh[0] : 0;
$height = array_key_exists(1, $wh) ? $wh[1] : 0;

if (!$download && !empty($width) && !empty($height) && array_key_exists('whc', $_GET)) {
    gerarImagem($path, $width, $height, true);
} elseif (!$download && !empty($width) && !empty($height) && array_key_exists('wh', $_GET)) {
    gerarImagem($path, $width, $height);
}

function gerarImagem($path, $largura, $altura, bool $cortar = false)
{
    try {
        $mimeType = mime_content_type($path);
        $mimeType = !empty($mimeType) ? mb_strtolower($mimeType, 'UTF-8') : '';
        $eImagem = in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
        if (!$eImagem) {
            return;
        }

        if (in_array($mimeType, ['image/jpeg', 'image/jpg'])) {
            $imagem = imagecreatefromjpeg($path);
        } elseif ($mimeType == 'image/png') {
            $imagem = imagecreatefrompng($path);
        } elseif ($mimeType == 'image/gif') {
            $imagem = imagecreatefromgif($path);
        }

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
        imagecopyresized($imagemRedimensionada, $imagem, 0, 0, 0, 0, $larguraNova, $alturaNova, $larguraImagem, $alturaImagem);

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

        header('Content-Type: image/png');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
        header('Cache-Control: max-age=86400');
        header('Pragma: cache');
        if ($cortar) {
            imagepng($imagemCortada);
        } else {
            imagepng($imagemRedimensionada);
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
