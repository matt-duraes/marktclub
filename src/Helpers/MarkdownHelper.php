<?php

namespace Helpers;

final class MarkdownHelper
{
    private $regTudo;
    public function __construct()
    {
        $this->regTudo = '\wÀ-ú0-9\.\,\?\!\/\\\|\(\)\[\]\{\}\@\#\$\%\&\;\:\ \'\"';
    }

    public function criarHtml($markdown)
    {
        $retorno = '';
        $explode = explode(PHP_EOL, $markdown);

        $reg = [
            "/^\#\ (.*)/" => "<h1>$1</h1>",
            "/^\#{2}\ (.*)/" => "<h2>$1</h2>",
            "/\*{2}([" . $this->regTudo . "]+)\*{2}/" => "<strong>$1</strong>",
            "/\*{1}([" . $this->regTudo . "]+)\*{1}/" => "<i>$1</i>",
            "/^[0-9]+\.\ (.*)/" => '<li>$1</li>',
            "/^\*\ (.*)/" => '<li>$1</li>',
            "/^\"\ (.*)/" => '<blockquote><p>$1</p></blockquote>',
            "/^\-{3}$/" => '<hr>',
            "/\[([a-zA-ZàúÀÚ0-9\ \_\-\.\,\;\:\!\@\#\$\%\&\*\"\']+)\]\((https?:\/\/[a-zA-Z0-9\-\_\.]+)\)/" => '<a href="$2" target="_blank">$1</a>',
            "/\[([a-zA-ZàúÀÚ0-9\ \_\-\.\,\;\:\!\@\#\$\%\&\*\"\']+)\]\(!(https?:\/\/[a-zA-Z0-9\-\_\.]+)\)/" => '<a href="$2" target="_self">$1</a>'
        ];

        $ul = false;
        $ol = false;
        foreach ($explode as $linha) {
            $linha = trim($linha);
            if (empty($linha)) {
                continue;
            }

            $eOl = preg_match("/^[0-9]+\.\ (.*)/", $linha);
            $eUl = preg_match("/^\*\ (.*)/", $linha);

            if ($eOl && !$ol) {
                $retorno .= '<ol>' . PHP_EOL;
                $ol = true;
            } elseif (!$eOl && $ol) {
                $retorno .= '</ol>' . PHP_EOL;
                $ol = false;
            } elseif ($eUl && !$ul) {
                $retorno .= '<ul>' . PHP_EOL;
                $ul = true;
            } elseif (!$eUl && $ul) {
                $retorno .= '</ul>' . PHP_EOL;
                $ul = false;
            }

            $existeExpressao = preg_match("/^#|^[0-9]+\.\ |^\*|^\-{3}|^\"\ /", $linha);

            $linha = preg_replace(
                array_keys($reg),
                array_values($reg),
                $linha
            );
            if (!$existeExpressao) {
                $linha = '<p>' . $linha . '</p>';
            }

            $retorno .= $linha . PHP_EOL;
        }
        return $retorno;
    }

    public function criarMarkdown(string $html): string
    {
        $retorno = '';
        $explode = explode(PHP_EOL, $html);

        $reg = [
            "/\<strong\>([" . $this->regTudo . "]+)\<\/strong\>/" => '**$1**',
            "/\<i\>([" . $this->regTudo . "]+)\<\/i\>/" => '*$1*',
            "/\<hr\>/" => '---',
            "/\<h1\>/" => '# ',
            "/\<h2\>/" => '## ',
            "/\<blockquote\>/" => '" ',
            "/\<a href\=\"(https?:\/\/[a-zA-Z0-9\-\_\.]+)\" target\=\"_self\"\>([a-zA-ZàúÀÚ0-9\ \_\-\.\,\;\:\!\@\#\$\%\&\*\"\']+)<\/a\>/" => "[$2](!$1)",
            "/\<a href\=\"(https?:\/\/[a-zA-Z0-9\-\_\.]+)\" target\=\"_blank\"\>([a-zA-ZàúÀÚ0-9\ \_\-\.\,\;\:\!\@\#\$\%\&\*\"\']+)<\/a\>/" => "[$2]($1)",
        ];

        $numero = 1;
        $eOl = false;
        $eUl = false;
        foreach ($explode as $linha) {
            $linha = trim($linha);
            if (empty($linha)) {
                continue;
            }

            $linha = preg_replace(array_keys($reg), array_values($reg), $linha);
            if (preg_match("/^\<ol\>$/", $linha)) {
                $numero = 1;
                $eOl = true;
                continue;
            } elseif (preg_match("/^\<\/ol\>$/", $linha)) {
                $eOl = false;
                continue;
            } elseif (preg_match("/^\<ul\>$/", $linha)) {
                $eUl = true;
                continue;
            } elseif (preg_match("/^\<\/ul\>$/", $linha)) {
                $eUl = false;
                continue;
            }
            $linha = trim(
                str_replace([
                    '<ul>', '</ul>', '<ol>', '</ol>', '<li>', '</li>', '</h1>', '</h2>', '<p>', '</p>', '</blockquote>'
                ], '', $linha)
            );

            if (empty($linha)) {
                continue;
            }
            if ($eOl) {
                $linha = $numero . '. ' . $linha;
                $numero++;
            } elseif ($eUl) {
                $linha = '* ' . $linha;
            }
            $retorno .= $linha . PHP_EOL;
        }
        return $retorno;
    }
}
