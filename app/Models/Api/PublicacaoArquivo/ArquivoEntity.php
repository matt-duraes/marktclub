<?php

namespace App\Models\Api\PublicacaoArquivo;

use App\Classes\Geral\Publicado;
use App\Classes\Geral\Status;
use App\Classes\PublicacaoArquivo\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Botao;
use Modules\DataHora;
use ORM\Entity;

final class ArquivoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $titulo;
    public string $texto;
    public string $imagem;
    public string $arquivo;
    public DataHora $data_inicio;
    public DataHora $data_final;
    public Botao $permissao_restrita;
    public Botao $permissao_site;
    public Tipo $tipo;
    public Status $status;
    public Publicado $publicado;
    protected string $ormTabela = TABELA_PUBLICACAO_ARQUIVO;
    protected array $ormInsert = [
        'id_admin_empresa'  => '->idEmpresa',
        'id_usuario_equipe' => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'imagem', 'arquivo', 'data_inicio', 'data_final',
        'permissao_restrita', 'permissao_site', 'tipo', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'imagem', 'arquivo', 'data_inicio', 'data_final',
        'permissao_restrita', 'permissao_site', 'tipo', 'status'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        arquivo|Arquivo|obrigatorio|vazio
        data_inicio|Data de publicação|valido
        data_final|Data de remoção|valido
        tipo|Tipo|valido
        status|Status|valido
    ';

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->imagem = !empty($this->imagem) ? arquivoPrivado($this->imagem) : '';
        $this->arquivo = !empty($this->arquivo) ? arquivoPrivado($this->arquivo) : '';
        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            $this->status->indice() === Status::ATIVO
        );
    }
}
