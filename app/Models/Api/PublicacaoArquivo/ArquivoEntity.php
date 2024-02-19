<?php

namespace App\Models\Api\PublicacaoArquivo;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use App\Classes\PublicacaoArquivo\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ArquivoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_ARQUIVO;
    protected array $ormInsert = [
        'id_usuario_equipe' => '->idUsuario',
        'id_admin_empresa'  => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'imagem', 'arquivo', 'data_inicio', 'data_final', 'permissao_restrita',
        'permissao_site', 'tipo', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'imagem', 'arquivo', 'data_inicio', 'data_final', 'permissao_restrita',
        'permissao_site', 'tipo', 'status'
    ];
    protected string $ormValidar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        arquivo|Arquivo|obrigatorio|vazio
        data_inicio|Data de publicação|valido
        data_final|Data de remoção|valido
        tipo|Tipo|valido
        status|Status|valido
    ';
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

    protected function regraPosBuscar()
    {
        $this->imagem = !empty($this->imagem) ? arquivoPrivado($this->imagem) : '';
        $this->arquivo = !empty($this->arquivo) ? arquivoPrivado($this->arquivo) : '';
        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            $this->status->indice() == Status::ATIVO
        );
    }
}
