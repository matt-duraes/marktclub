<?php

namespace ResourcesSite\Componente;

use stdClass;

final class View
{
    private array $class = [];
    private array $css = [];

    public function __construct(
        private stdClass $r
    ) {
    }

    public function __toString()
    {
        $retorno[] = !empty($this->css) ? 'style="' . implode(';', $this->css) . '"' : '';
        $retorno[] = !empty($this->class) ? 'class="' . implode(' ', $this->class) . '"' : '';
        return implode(' ', $retorno);
    }

    public function css()
    {
        $r = $this->r;
        $css = [];

        $topo = $r->margem_topo;
        if (!empty($topo)) {
            $css[] = 'margin-top:' . $topo . 'px';
        }
        $direita = $r->margem_direita;
        if (!empty($direita)) {
            $css[] = 'margin-right:' . $direita . 'px';
        }
        $baixo = $r->margem_baixo;
        if (!empty($baixo)) {
            $css[] = 'margin-bottom:' . $baixo . 'px';
        }
        $esquerda = $r->margem_esquerda;
        if (!empty($esquerda)) {
            $css[] = 'margin-left:' . $esquerda . 'px';
        }
        $iconeTamanho = $r->icone_tamanho;
        if (!empty($iconeTamanho) && $r->tipo == 'icone') {
            $css[] = 'width: ' . $iconeTamanho . 'px';
            $css[] = 'height: ' . $iconeTamanho . 'px';
        }
        if (empty($css)) {
            return $this;
        }
        $this->css = $css;
        return $this;
    }

    public function class(string $class = '')
    {
        $r = $this->r;
        $class = !empty($class) ? [$class] : [];
        $class[] = 'com_pai_' . $r->tipo;
        $class[] = 'com_aparecer_' . $r->local;

        if (!empty($r->div_direcao)) {
            $class[] = 'com_direcao_' . $r->div_direcao;
        }
        if (!empty($r->div_posicao)) {
            $class[] = 'com_posicao_' . $r->div_posicao;
        }
        if (!empty($r->icone_tipo)) {
            $class[] = 'com_icone_' . $r->icone_tipo;
        }
        if (empty($class)) {
            return $this;
        }
        $this->class = $class;
        return $this;
    }
}
