<?php

namespace App\Models\Api\PublicacaoYoutube;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use App\Classes\Geral\Status;
use App\Classes\PublicacaoYoutube\Local;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class YoutubeEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_YOUTUBE;
    protected array $ormInsert = ['id_admin_empresa'];
    protected array $ormSalvar = [
        'titulo', 'texto', 'header_titulo', 'header_descricao', 'header_tag', 'data_inicio',
        'data_final', 'permissao_site', 'permissao_restrita', 'video', 'local', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'header_titulo', 'header_descricao', 'header_tag', 'data_inicio',
        'data_final', 'permissao_site', 'permissao_restrita', 'video', 'local', 'url', 'status'
    ];
    private int $idEmpresa;
    public string $empresa;
    protected int $id_admin_empresa;
    public string $titulo;
    public string $texto;
    public string $video;
    public string $url;
    public string $header_titulo;
    public string $header_descricao;
    public array $header_tag;
    public DataHora $data_inicio;
    public DataHora $data_final;
    public Botao $permissao_site;
    public Botao $permissao_restrita;
    public Local $local;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }

    protected function regraInsert()
    {
        $this->id_admin_empresa = $this->idEmpresa;
    }
}
