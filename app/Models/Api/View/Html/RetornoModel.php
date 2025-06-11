<?php

namespace App\Models\Api\View\Html;

use ORM\ORM;
use Modules\Botao;
use App\Classes\Geral\Metodo;
use App\Classes\Geral\Target;
use App\Classes\View\Lista\Tipo;
use App\Classes\View\Lista\Local;
use App\Classes\View\Lista\BotaoTipo;
use App\Classes\View\Lista\IconeTipo;
use App\Classes\View\Lista\ListaTipo;
use App\Classes\View\Lista\DivDirecao;
use App\Classes\View\Lista\DivPosicao;
use App\Classes\View\Lista\TextoAlinhamento;
use App\Models\Api\ComercialEmpresa\HelperModel as EmpresaModel;

abstract class RetornoModel extends ORM
{
    protected array $dado = [];
    public array $retorno = [];

    protected function montarRetorno()
    {
        $pai = [];
        $grupo = [];
        foreach ($this->dado as $r) {
            if (empty($r->id_view_html)) {
                $pai[] = $r;
                continue;
            }
            $grupo[$r->id_view_html][] = $r;
        }

        $retorno = [];
        foreach ($pai as $r) {
            $temp = $this->montarArrayRetorno($r);
            $temp['lista'] = $this->montarLista($r, $grupo);
            $retorno[] = $temp;
        }
        $this->retorno = $retorno;
    }

    private function montarLista($item, $grupo)
    {
        if (!array_key_exists($item->id, $grupo)) {
            return [];
        }
        $retorno = [];
        foreach ($grupo[$item->id] as $r) {
            $temp = $this->montarArrayRetorno($r);
            $temp['lista'] = $this->montarLista($r, $grupo);
            $retorno[] = $temp;
        }
        return $retorno;
    }

    private function montarArrayRetorno($r)
    {
        $painel = defined('TOKEN') && TOKEN['app']->audience == 'painel';

        $direcaoDesktop = (new DivDirecao($r->div_direcao_desktop))->indice();
        $direcaoMobile = (new DivDirecao($r->div_direcao_mobile))->indice();
        $posicaoDesktop = (new DivPosicao($r->div_posicao_desktop))->indice();
        $posicaoMobile = (new DivPosicao($r->div_posicao_mobile))->indice();
        $textoAlinhamentoDesktop = (new TextoAlinhamento($r->texto_alinhamento_desktop))->indice();
        $textoAlinhamentoMobile = (new TextoAlinhamento($r->texto_alinhamento_mobile))->indice();

        return $this->removerValorVazio([
            'id'              => $r->uuid,
            'empresa_ativa'   => $this->converterIdEmpresa($r->id_admin_empresa_ativa),
            'empresa_inativa' => $this->converterIdEmpresa($r->id_admin_empresa_inativa),
            'tipo'            => (new Tipo($r->tipo))->indice(),
            'local'           => (new Local($r->local))->indice(),
            'titulo_interno'  => $r->titulo_interno,
            'titulo'          => $r->titulo,
            'texto'           => $r->texto,
            'texto_alinhamento_desktop' => $textoAlinhamentoDesktop,
            'texto_alinhamento_mobile' => !empty($textoAlinhamentoMobile) ? $textoAlinhamentoMobile : $textoAlinhamentoDesktop,
            'link'            => $r->link,
            'target'          => (new Target($r->target))->indice(),
            'tabela'          => $r->tabela,
            'editor'          => $r->editor,
            'margem_topo_desktop'     => $r->margem_topo_desktop,
            'margem_topo_mobile'     => $r->margem_topo_mobile,
            'margem_esquerda_desktop' => $r->margem_esquerda_desktop,
            'margem_esquerda_mobile' => $r->margem_esquerda_mobile,
            'margem_direita_desktop'  => $r->margem_direita_desktop,
            'margem_direita_mobile'  => $r->margem_direita_mobile,
            'margem_baixo_desktop'    => $r->margem_baixo_desktop,
            'margem_baixo_mobile'    => $r->margem_baixo_mobile,
            'imagem_arquivo'  => $painel ? $r->imagem_arquivo : imagemPrivada($r->imagem_arquivo),
            'imagem_altura_desktop' => $r->imagem_altura_desktop,
            'imagem_altura_mobile' => !$painel && empty($r->imagem_altura_mobile) ? $r->imagem_altura_desktop : $r->imagem_altura_mobile,
            'icone_tipo'      => (new IconeTipo($r->icone_tipo))->indice(),
            'icone_tamanho'   => $r->icone_tamanho,
            'icone_altura'    => $r->icone_altura,
            'icone_nome'      => $r->icone_nome,
            'icone_cor'       => $r->icone_cor,
            'icone_bg'        => $r->icone_bg,
            'icone_borda_cor' => $r->icone_borda_cor,
            'lista_tipo'      => (new ListaTipo($r->lista_tipo))->indice(),
            'lista_valor'     => $r->lista_valor,
            'link_empresa'    => $r->link_empresa,
            'div_minimo_desktop' => $r->div_minimo_desktop,
            'div_minimo_mobile' => $r->div_minimo_mobile,
            'div_maximo_desktop' => $r->div_maximo_desktop,
            'div_maximo_mobile' => $r->div_maximo_mobile,
            'div_direcao_desktop' => $direcaoDesktop,
            'div_direcao_mobile' => !$painel && empty($direcaoMobile) ? $direcaoDesktop : $direcaoMobile,
            'div_posicao_desktop' => $posicaoDesktop,
            'div_posicao_mobile' => !$painel && empty($posicaoMobile) ? $posicaoDesktop : $posicaoMobile,
            'api_status'      => (new Botao($r->api_status ? 'sim' : 'nao'))->valor(),
            'api_metodo'      => (new Metodo($r->api_metodo))->indice(),
            'api_body'        => $r->api_body,
            'api_uri'         => $r->api_uri,
            'minimizado'      => (new Botao($r->minimizado ? 'sim' : 'nao'))->valor(),
            'botao_tipo'      => (new BotaoTipo($r->botao_tipo))->indice(),
            'ordem'           => $r->ordem,
            'status'          => (new Botao($r->status ? 'sim' : 'nao'))->valor(),
        ], $painel);
    }

    private function removerValorVazio($array, $painel): array
    {
        $retorno = [];
        $remover = ['titulo_interno', 'minimizado'];
        foreach($array as $ind => $val) {
            if(empty($val) || (!$painel && in_array($ind, $remover))) {
                continue;
            }
            $retorno[$ind] = $val;
        }
        return $retorno;
    }

    private function converterIdEmpresa($empresa)
    {
        $empresa = jsonDecode($empresa, true, true);
        if (empty($empresa)) {
            return [];
        }
        $Empresa = new EmpresaModel();
        return $Empresa->mudarListaIdParaUuid($empresa);
    }
}
