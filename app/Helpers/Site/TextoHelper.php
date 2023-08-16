<?php

namespace App\Helpers\Site;

final class TextoHelper
{
    public function destaque(?string $texto)
    {
        if (empty($texto)) {
            return '';
        }

        $replace = [
            '<br>'                                 => '/\< ?br ?\/? ?\>/',
            '<strong class="vermelho">$1</strong>' => '/\*{2}([^*]+)\*{2}/',
            '<strong>$1</strong>'                  => '/\*([^*]+)\*/',
        ];
        $texto = preg_replace(array_values($replace), array_keys($replace), $texto);
        $explode = explode(PHP_EOL, str_replace('<br>', PHP_EOL, $texto));
        $ul = [];
        $retorno = [];
        foreach ($explode as $linha) {
            if (str_starts_with($linha, '-')) {
                $ul[] = '<li>' . preg_replace('/^\-/', '', $linha) . '</li>';
                continue;
            } elseif ($ul) {
                $retorno[] = '<ul>' . implode('', $ul) . '</ul>';
                $ul = [];
                continue;
            }
            $retorno[] = '<p>' . $linha . '</p>';
        }
        if ($ul) {
            $retorno[] = '<ul>' . implode('', $ul) . '</ul>';
        }
        return str_replace('*', '', implode('', $retorno));
    }
}
