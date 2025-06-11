<?php

namespace App\Models\Api\View\Html;

use ORM\Entity;
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
use App\Models\Api\View\Pagina\HelperModel;
use App\Classes\View\Lista\TextoAlinhamento;
use App\Models\Api\ComercialEmpresa\HelperModel as ComercialEmpresaHelperModel;

final class HtmlEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_HTML;
    protected array $ormBuscar = [
        'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'tabela', 'editor',
        'titulo_interno', 'div_direcao_desktop', 'div_direcao_mobile', 'div_posicao_desktop', 'div_posicao_mobile',
        'margem_topo_desktop', 'margem_esquerda_desktop', 'margem_direita_desktop', 'margem_baixo_desktop',
        'margem_topo_mobile', 'margem_esquerda_mobile', 'margem_direita_mobile', 'margem_baixo_mobile',
        'icone_tipo', 'icone_tamanho', 'icone_cor', 'icone_bg', 'icone_borda_cor', 'icone_nome',
        'icone_altura', 'lista_tipo', 'lista_valor', 'link_empresa', 'imagem_arquivo', 'imagem_altura_desktop',
        'imagem_altura_mobile', 'api_status', 'api_metodo', 'api_body', 'api_uri', 'botao_tipo',
        'status', 'ordem', 'id_admin_empresa_ativa', 'id_admin_empresa_inativa', 'div_minimo_desktop',
        'div_minimo_mobile', 'div_maximo_desktop', 'div_maximo_mobile', 'texto_alinhamento_desktop',
        'texto_alinhamento_mobile'
    ];
    protected array $ormInsert = ['id_view_pagina', ];
    protected array $ormSalvar = [
        'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'tabela', 'editor',
        'titulo_interno', 'div_direcao_desktop', 'div_direcao_mobile', 'div_posicao_desktop', 'div_posicao_mobile',
        'margem_topo_desktop', 'margem_esquerda_desktop', 'margem_direita_desktop', 'margem_baixo_desktop',
        'margem_topo_mobile', 'margem_esquerda_mobile', 'margem_direita_mobile', 'margem_baixo_mobile',
        'icone_tipo', 'icone_tamanho', 'icone_cor', 'icone_bg', 'icone_borda_cor', 'icone_nome', 'icone_altura',
        'lista_tipo', 'lista_valor', 'link_empresa', 'api_status', 'api_metodo', 'api_body', 'api_uri', 'botao_tipo',
        'status', 'id_view_html', 'ordem', 'id_admin_empresa_ativa', 'id_admin_empresa_inativa', 'imagem_arquivo',
        'imagem_altura_desktop', 'imagem_altura_mobile', 'div_minimo_desktop', 'div_minimo_mobile',
        'div_maximo_desktop', 'div_maximo_mobile', 'texto_alinhamento_desktop', 'texto_alinhamento_mobile'
    ];
    public int $id_view_pagina;
    public int $id_view_html;
    public array $id_admin_empresa_ativa;
    public array $id_admin_empresa_inativa;
    public array $empresa_ativa;
    public array $empresa_inativa;
    public string $pagina;
    public null|string $pai = null;
    public Tipo $tipo;
    public Local $local;
    public array $titulo;
    public array $texto;
    public TextoAlinhamento $texto_alinhamento_desktop;
    public TextoAlinhamento $texto_alinhamento_mobile;
    public string $link;
    public Target $target;
    public array $tabela;
    public string $editor;
    public string $titulo_interno;
    public int $margem_topo_desktop;
    public int $margem_topo_mobile;
    public int $margem_esquerda_desktop;
    public int $margem_esquerda_mobile;
    public int $margem_direita_desktop;
    public int $margem_direita_mobile;
    public int $margem_baixo_desktop;
    public int $margem_baixo_mobile;
    public string $imagem_arquivo;
    public int $imagem_altura_desktop;
    public int $imagem_altura_mobile;
    public IconeTipo $icone_tipo;
    public int $icone_tamanho;
    public string $icone_nome;
    public int $icone_altura;
    public string $icone_cor;
    public string $icone_bg;
    public string $icone_borda_cor;
    public ListaTipo $lista_tipo;
    public array $lista_valor;
    public array $link_empresa;
    public int $div_minimo_desktop;
    public int $div_minimo_mobile;
    public int $div_maximo_desktop;
    public int $div_maximo_mobile;
    public DivDirecao $div_direcao_desktop;
    public DivDirecao $div_direcao_mobile;
    public DivPosicao $div_posicao_desktop;
    public DivPosicao $div_posicao_mobile;
    public Botao $api_status;
    public Metodo $api_metodo;
    public array $api_body;
    public string $api_uri;
    public BotaoTipo $botao_tipo;
    public Botao $status;
    public array $html;
    public int $ordem;

    protected function regraInsert()
    {
        $this->ordem = 9999;
    }

    protected function regraSalvar()
    {
        if ($this->pExiste('pagina')) {
            $Pagina = new HelperModel();
            $this->id_view_pagina = $Pagina->pegarIdPeloUuid($this->pagina);
        }
        if (!empty($this->pai)) {
            $this->id_view_html = $this->campo(['id'])->where(['uuid', $this->pai])->primeiro('id', padrao: null);
        }
        $Empresa = new ComercialEmpresaHelperModel();
        if ($this->pExiste('empresa_ativa')) {
            $this->id_admin_empresa_ativa = $Empresa->mudarListaUuidParaId($this->empresa_ativa);
        }
        if ($this->pExiste('empresa_inativa')) {
            $this->id_admin_empresa_inativa = $Empresa->mudarListaUuidParaId($this->empresa_inativa);
        }
    }

    protected function regraPosBuscar()
    {
        $Empresa = new ComercialEmpresaHelperModel();
        if ($this->pExiste('empresa_ativa')) {
            $this->empresa_ativa = $Empresa->mudarListaIdParaUuid($this->id_admin_empresa_ativa);
        }
        if ($this->pExiste('empresa_inativa')) {
            $this->empresa_inativa = $Empresa->mudarListaIdParaUuid($this->id_admin_empresa_inativa);
        }
    }
}
