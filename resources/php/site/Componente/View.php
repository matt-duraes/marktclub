<?php

namespace ResourcesSite\Componente;

use stdClass;
use App\Classes\View\Lista\IconeTipo;

final class View
{
    private array $class = [];
    private array $css = [];
    private string $tema = '';

    public function __construct(
        private stdClass $r
    ) {
        $this->tema = tema();
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
        if ($r->tipo == 'icone' && !empty($iconeTamanho)) {
            $css[] = 'width: ' . $iconeTamanho . 'px';
            $css[] = 'height: ' . $iconeTamanho . 'px';
        }
        $iconeCor = $r->icone_cor;
        if ($r->tipo == 'icone' && $this->validarCor($iconeCor)) {
            $css[] = 'fill: ' . $this->pegarCor($iconeCor);
        } else {
            $css[] = 'fill: #999999';
        }
        $iconeBg = $r->icone_bg;
        $iconBgValido = $this->validarCor($iconeBg);
        if ($r->tipo == 'icone' && $iconBgValido) {
            $css[] = 'background-color: ' . $this->pegarCor($iconeBg);
        }

        $iconeBordaCor = $r->icone_borda_cor;
        if ($r->tipo == 'icone' && $this->validarCor($iconeBordaCor)) {
            $css[] = 'border: 1px solid ' . $this->pegarCor($iconeBordaCor);
        } elseif ($r->tipo == 'icone' && !$iconBgValido && in_array($r->icone_tipo, [IconeTipo::QUADRADO, IconeTipo::REDONTO])) {
            $css[] = 'border: 1px solid #CCC';
        }

        if (empty($css)) {
            return $this;
        }
        $this->css = $css;
        return $this;
    }

    private function validarCor($cor)
    {
        return !empty($cor) && preg_match('/^(\#[0-9a-fA-F]{6})|padrao$/', $cor);
    }

    private function pegarCor($cor)
    {
        if ($cor != 'padrao') {
            return $cor;
        }
        return $this->tema === 'light' ? CLUBE_COR_PRINCIPAL : CLUBE_COR_SECUNDARIA;
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
