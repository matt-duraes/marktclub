<?php

namespace App\Models\Api\PublicacaoNoticia;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use App\Classes\PublicacaoNoticia\Tipo;
use App\Classes\PublicacaoNoticia\Local;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class NoticiaEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_NOTICIA;
    protected array $ormInsert = [
        'id_admin_empresa'  => '->idEmpresa',
        'id_usuario_equipe' => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'titulo_grande', 'titulo_pequeno', 'subtitulo', 'texto_grande', 'texto_pequeno',
        'imagem_grande', 'imagem_pequena', 'imagem_galeria', 'imagem_social', 'arquivo',
        'fonte_noticia', 'fonte_link', 'autor_noticia', 'data_inicio', 'data_final',
        'data_atualizada', 'permissao_restrita', 'header_titulo', 'header_descricao',
        'header_tag', 'permissao_site', 'tipo', 'local', 'status'
    ];
    protected array $ormBuscar = [
        'titulo_grande', 'titulo_pequeno', 'subtitulo', 'texto_grande', 'texto_pequeno',
        'imagem_grande', 'imagem_pequena', 'imagem_galeria', 'imagem_social', 'arquivo',
        'fonte_noticia', 'fonte_link', 'autor_noticia', 'data_inicio', 'data_final',
        'data_atualizada', 'permissao_restrita', 'header_titulo', 'header_descricao',
        'header_tag', 'permissao_site', 'tipo', 'local', 'status', 'data_criacao',
        'data_atualizacao', 'url'
    ];
    protected string $ormValidarSalvar = '
        titulo_grande|Título grande|obrigatorio|vazio
        data_inicio|Data de início da publicação|obrigatorio|vazio|valido
        data_final|Data final da publicação|valido
        data_atualizada|Data de atualização da publicação|valido
        texto_grande|Texto grande|obrigatorio|vazio
        local|Local|obrigatorio|valido
        tipo|Tipo|obrigatorio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo_grande;
    public string $titulo_pequeno;
    public string $subtitulo;
    public string $texto_grande;
    public string $texto_pequeno;
    public string $imagem_grande;
    public string $imagem_pequena;
    public array $imagem_galeria;
    public string $imagem_social;
    public array $arquivo;
    public string $fonte_noticia;
    public string $fonte_link;
    public string $autor_noticia;
    public string $url;
    public DataHora $data_inicio;
    public DataHora $data_final;
    public DataHora $data_atualizada;
    public Botao $permissao_restrita;
    public Botao $permissao_site;
    public Status $status;
    public string $header_titulo;
    public string $header_descricao;
    public array $header_tag;
    public Tipo $tipo;
    public Local $local;
    private int $idEmpresa;
    private ?int $idUsuario;
    public Publicado $publicado;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
        $this->ormWherePadrao = ['id_admin_empresa', $this->idEmpresa];
    }

    protected function regraPosBuscar()
    {
        $this->imagem_grande = !empty($this->imagem_grande) ? arquivoPrivado($this->imagem_grande) : '';
        $this->imagem_pequena = !empty($this->imagem_pequena) ? arquivoPrivado($this->imagem_pequena) : '';
        $this->imagem_social = !empty($this->imagem_social) ? arquivoPrivado($this->imagem_social) : '';

        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            $this->status->indice() == Status::ATIVO
        );
    }
}
