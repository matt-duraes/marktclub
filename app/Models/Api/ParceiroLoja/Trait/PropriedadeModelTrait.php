<?php

namespace App\Models\Api\ParceiroLoja\Trait;

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoEstabelecimento;
use App\Classes\ParceiroLoja\TipoLoja;
use Modules\Botao;
use Modules\Data;

trait PropriedadeModelTrait
{
    public string $equipe;
    public Botao $favorito;
    public Categoria $categoria;
    public string $subcategoria;
    public TipoEstabelecimento $tipo_estabelecimento;
    public string $titulo;
    public string $pesquisa;
    public TipoLoja $tipo_loja;
    public Status $status;
    public Botao $mais_acessado;
    public string $convenio_direto;
    public float $latitude;
    public float $longitude;
    public array $endereco_estado;
    public string $empresa;
    public array $empresas;
    public string $convenio;
    public string $painel;
    public Data $data_criacao_de;
    public Data $data_criacao_ate;
    public Data $data_publicacao_de;
    public Data $data_publicacao_ate;
    public Data $data_prospeccao_de;
    public Data $data_prospeccao_ate;
    public Data $data_problema_de;
    public Data $data_problema_ate;
    public Data $data_cancelado_de;
    public Data $data_cancelado_ate;
    public Data $data_auditoria_de;
    public Data $data_auditoria_ate;
    private int $idEmpresa;
    private array $idMaisAcessado = [];
    private bool $buscarFavorito = true;
}
