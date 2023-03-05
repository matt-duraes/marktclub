<?php

namespace App\Models\Api\PublicacaoNoticia;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use App\Classes\StatusGeral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class NoticiaEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_PUBLICACAO_NOTICIA;

    protected array $_insert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario_equipe' => '->idUsuario'
    ];

    protected array $_salvar = [
        'titulo_grande', 'titulo_pequeno', 'subtitulo', 'texto_grande', 'texto_pequeno',
        'imagem_grande', 'imagem_pequena', 'imagem_galeria', 'imagem_social', 'arquivo',
        'fonte_noticia', 'fonte_link', 'autor_noticia', 'data_publicacao_inicio',
        'data_publicacao_final', 'data_publicacao_atualizada', 'permissao_restrita',
        'permissao_site', 'permissao_banner', 'status'
    ];
    protected array $_buscar = [
        'titulo_grande', 'titulo_pequeno', 'subtitulo', 'texto_grande', 'texto_pequeno',
        'imagem_grande', 'imagem_pequena', 'imagem_galeria', 'imagem_social', 'arquivo',
        'fonte_noticia', 'fonte_link', 'autor_noticia', 'data_publicacao_inicio',
        'data_publicacao_final', 'data_publicacao_atualizada', 'permissao_restrita',
        'permissao_site', 'permissao_banner', 'url', 'status'
    ];

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
    public DataHora $data_publicacao_inicio;
    public DataHora $data_publicacao_final;
    public DataHora $data_publicacao_atualizada;
    public Botao $permissao_restrita;
    public Botao $permissao_site;
    public Botao $permissao_banner;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
