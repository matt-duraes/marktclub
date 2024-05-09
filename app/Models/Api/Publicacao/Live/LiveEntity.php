<?php

namespace App\Models\Api\Publicacao\Live;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use Modules\ArquivoPrivado;
use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class LiveEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_LIVE;
    protected array $ormSalvar = [
        'titulo', 'titulo_interno', 'texto', 'imagem_site_desktop', 'imagem_site_mobile', 'imagem_restrito_desktop',
        'imagem_restrito_mobile', 'link', 'permissao_restrita', 'permissao_site', 'link_restrito',
        'data_inicio', 'data_final', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'titulo_interno', 'texto', 'imagem_site_desktop', 'imagem_site_mobile', 'imagem_restrito_desktop',
        'imagem_restrito_mobile', 'link', 'permissao_restrita', 'permissao_site', 'link_restrito',
        'data_inicio', 'data_final', 'status'
    ];
    public string $titulo;
    public string $titulo_interno;
    public string $texto;
    public ArquivoPrivado $imagem_site_desktop;
    public ArquivoPrivado $imagem_site_mobile;
    public ArquivoPrivado $imagem_restrito_desktop;
    public ArquivoPrivado $imagem_restrito_mobile;
    public string $link;
    public Botao $permissao_restrita;
    public Botao $permissao_site;
    public Botao $link_restrito;
    public DataHora $data_inicio;
    public DataHora $data_final;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
        if (empty($this->idEmpresa)) {
            mensagemStatus(404);
        }

        $this->buscar(['id_admin_empresa', $this->idEmpresa]);
    }
}
