<?php

namespace ResourcesSite\Componente;

use stdClass;
use App\Classes\View\Lista\IconeTipo;

final class View
{
    private array $class = [];
    private array $css = [];
    private string $tema = '';
    private stdClass $r;
    private int $classNumero = 0;
    private string $id;

    public function __construct(
        stdClass $r
    ) {
        $this->setarValor($r);
        $this->tema = tema();
    }

    private function setarValor(stdClass $r)
    {
        $this->id = $r->id;
        $obrigatorio = [
            'margem_topo' => 0,
            'margem_direita' => 0,
            'margem_baixo' => 0,
            'margem_esquerda' => 0
        ];
        foreach($obrigatorio as $ind => $val) {
            if(!object_key_exists($ind, $r)) {
                $r->$ind = $val;
            }
        }
        $this->r = $r;
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
        $iconeTamanho = $r->icone_tamanho ?? '';
        if ($r->tipo == 'icone' && !empty($iconeTamanho)) {
            $css[] = 'width: ' . $iconeTamanho . 'px';
            $css[] = 'height: ' . $iconeTamanho . 'px';
        }
        $iconeCor = $r->icone_cor ?? '';
        if ($r->tipo == 'icone' && $this->validarCor($iconeCor)) {
            $css[] = 'fill: ' . $this->pegarCor($iconeCor);
        } else {
            $css[] = 'fill: #999999';
        }
        $iconeBg = $r->icone_bg ?? '';
        $iconBgValido = $this->validarCor($iconeBg);
        if ($r->tipo == 'icone' && $iconBgValido) {
            $css[] = 'background-color: ' . $this->pegarCor($iconeBg);
        }

        $iconeBordaCor = $r->icone_borda_cor ?? '';
        if ($r->tipo == 'icone' && $this->validarCor($iconeBordaCor)) {
            $css[] = 'border: 1px solid ' . $this->pegarCor($iconeBordaCor);
        } elseif ($r->tipo == 'icone' && !$iconBgValido && in_array($r->icone_tipo, [IconeTipo::QUADRADO, IconeTipo::REDONTO])) {
            $css[] = 'border: 1px solid #CCC';
        }

        if(empty($css)) {
            return '';
        }
        $classe = 'item_css_' . $this->id . '_' . $this->classNumero;
        $this->classNumero++;
        $this->class[] = $classe;
        $classe = '.' . $classe;
        $css = implode(';' . PHP_EOL, $css);

        return <<<HTML
            <style>
                $classe {
                    $css
                }
            </style>
            HTML;
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

    public function api()
    {
        $r = $this->r;
        $apiStatus = $r->api_status ?? 'nao';
        $api = [
            'metodo' => $r->api_metodo ?? '',
            'body' => $r->api_body ?? [],
            'uri' => $r->api_uri ?? ''
        ];
        return $apiStatus === 'sim' ? base64Encode(dado: $api, url: true) : '';
    }

    public function class(string $class = '')
    {
        $r = $this->r;
        $class = explode(' ', $class);
        if(!empty($class)) {
            foreach($class as $val) {
                $this->class[] = $val;
            }
        }
        $this->class[] = 'com_pai_' . $r->tipo;
        $this->class[] = 'com_aparecer_' . $r->local;
        $api = $r->api_status ?? '';
        $tipo = $r->tipo;

        if($api === 'sim') {
            $this->class[] = 'com_api_' . $tipo;
        }
        if (!empty($r->div_direcao)) {
            $this->class[] = 'com_direcao_' . $r->div_direcao;
        }
        if (!empty($r->div_posicao)) {
            $this->class[] = 'com_posicao_' . $r->div_posicao;
        }
        if (!empty($r->icone_tipo)) {
            $this->class[] = 'com_icone_' . $r->icone_tipo;
        }
        return $this;
    }
}
