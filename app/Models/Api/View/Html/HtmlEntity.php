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

final class HtmlEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_HTML;
    protected array $ormBuscar = [
        'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'tabela', 'editor',
        'titulo_interno', 'margem_topo', 'margem_esquerda', 'margem_direita', 'margem_baixo',
        'imagem_arquivo', 'imagem_altura', 'icone_tipo', 'icone_tamanho',
        'icone_nome', 'icone_altura', 'lista_tipo', 'lista_valor', 'link_empresa',
        'div_direcao', 'div_posicao', 'api_status', 'api_metodo', 'api_body', 'api_uri',
        'botao_tipo', 'status', 'ordem'
    ];
    protected array $ormInsert = ['id_view_pagina', ];
    protected array $ormSalvar = [
        'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'tabela', 'editor',
        'titulo_interno', 'margem_topo', 'margem_esquerda', 'margem_direita', 'margem_baixo',
        'imagem_arquivo', 'imagem_altura', 'icone_tipo', 'icone_tamanho',
        'icone_nome', 'icone_altura', 'lista_tipo', 'lista_valor', 'link_empresa',
        'div_direcao', 'div_posicao', 'api_status', 'api_metodo', 'api_body', 'api_uri',
        'botao_tipo', 'status', 'id_view_html', 'ordem'
    ];
    public int $id_view_pagina;
    public int $id_view_html;
    public string $pagina;
    public null|string $pai = null;
    public Tipo $tipo;
    public Local $local;
    public array $titulo;
    public array $texto;
    public string $link;
    public Target $target;
    public array $tabela;
    public string $editor;
    public string $titulo_interno;
    public int $margem_topo;
    public int $margem_esquerda;
    public int $margem_direita;
    public int $margem_baixo;
    public string $imagem_arquivo;
    public int $imagem_altura;
    public IconeTipo $icone_tipo;
    public int $icone_tamanho;
    public string $icone_nome;
    public int $icone_altura;
    public ListaTipo $lista_tipo;
    public array $lista_valor;
    public array $link_empresa;
    public DivDirecao $div_direcao;
    public DivPosicao $div_posicao;
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
    }
}
