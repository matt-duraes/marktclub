<?php

namespace ResourcesSite\Componente;

final class Botao extends Componente
{
    private string $html = '';
    private string $texto = '';
    private array $classe = [];
    private bool $fixo = false;

    public const ICONE_ESQUERDA = 'esquerda';
    public const ICONE_DIREITA = 'direita';

    public function __toString()
    {
        $replace = [
            '[[CLASSE_PADRAO]]' => implode(' ', $this->classe),
            '[[TEXTO]]'         => $this->texto
        ];
        $html = preg_replace(
            '/\[\[[A-Z\_]{1,}\]\]/',
            '',
            str_replace(array_keys($replace), array_values($replace), $this->html)
        );
        if ($this->fixo) {
            $id = md5(uniqid(time()));
            $html = '
                <div class="com_bloco_botao_scroll" id="bloco_' . $id . '">
                    ' . $html . '
                </div>
                ' . str_replace('com_botao_padrao', 'com_botao_padrao com_botao_fixo', $html) . '
            ';
        }
        return $html;
    }

    private function resetar()
    {
        $this->html = '';
        $this->texto = '';
        $this->classe = [];
        $this->fixo = false;
    }

    public function icone(string $icone, $posicao = self::ICONE_ESQUERDA)
    {
        $icone = '<div class="com_icone">' . $icone . '</div>';
        $replace = $posicao === self::ICONE_ESQUERDA ? '[[ICONE_ESQUERDA]]' : '[[ICONE_DIREITA]]';
        $this->html = str_replace($replace, $icone, $this->html);
        $this->classe[] = $posicao === self::ICONE_ESQUERDA ? 'com_botao_icone_esquerda' : 'com_botao_icone_direita';
        return $this;
    }

    public function botao(
        string $texto = '',
        string $link = null,
        string $id = null,
        string $class = null,
        array $attr = [],
    ) {
        $this->resetar();
        $texto = !empty($texto) ? '<div class="com_texto">' . $texto . '</div>' : '[[TEXTO]]';
        $tag = 'div';
        if (validarUrl($link)) {
            $tag = 'a';
            $attr['href'] = $link;
        }

        $reg = '/^' . str_replace('/', '\/', LINK) . '/';
        if ($tag == 'a' && !preg_match($reg, $link)) {
            $attr['target'] = '_blank';
            $attr['rel'] = 'nofollow noopener';
        }
        $id = !empty($id) ? 'id="' . $id . '"' : '';

        if (!empty($class)) {
            $this->classe[] = $class;
        }
        $this->classe[] = 'com_botao_padrao';

        $this->html = '<' . $tag . ' ' . $this->attr($attr) . ' '
            . $id . ' class="[[CLASSE_PADRAO]]">[[ICONE_ESQUERDA]]' . $texto . '[[ICONE_DIREITA]]</' . $tag . '>';
        return $this;
    }

    public function cor()
    {
        $this->classe[] = 'com_botao_cor';
    }

    public function cinza()
    {
        $this->classe[] = 'com_botao_cinza';
        return $this;
    }

    public function fixo()
    {
        $this->fixo = true;
    }

    public function borda()
    {
        $this->classe[] = 'com_botao_borda';
        return $this;
    }

    public function hover()
    {
        $this->classe[] = 'com_botao_hover';
        return $this;
    }

    public function voltar()
    {
        $this->borda();
        $this->cinza();
        $this->texto('Voltar');
        return $this;
    }

    public function texto(string $texto)
    {
        if (empty($texto)) {
            return $this;
        }
        $this->texto = '<div class="com_texto">' . $texto . '</div>';
        return $this;
    }
}
