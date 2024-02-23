<?php

namespace App\Models\Api\AlbumDado;

use ORM\Entity;
use Modules\Botao;
use Modules\DataHora;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class AlbumEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_ALBUM_DADO;
    protected array $ormInsert = [
        'id_admin_empresa', 'id_usuario_equipe'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'imagem', 'data_inicio', 'data_final', 'permissao_restrita', 'permissao_site', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'data_inicio', 'data_final', 'permissao_restrita', 'permissao_site', 'status'
    ];
    protected string $ormValidar = '
        titulo|Titulo|obrigatorio|vazio
        data_inicio|Data de início da publicação|obrigatorio|vazio|valido
        data_final|Data final da publicação|valido
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo;
    public string $texto;
    public string $imagem;
    public DataHora $data_inicio;
    public DataHora $data_final;
    public Botao $permissao_restrita;
    public Botao $permissao_site;
    public Publicado $publicado;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function regraPosBuscar()
    {
        if (empty($this->imagem)) {
            $this->setarImagemCapa();
        }
        $this->imagem = arquivoPublico('album_foto', $this->imagem, padrao: '');
        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            $this->status->indice() == $this->status::ATIVO
        );
    }

    private function setarImagemCapa()
    {
        $imagem = (new OrmHelper(TABELA_ALBUM_FOTO))->pegarPrimeiroRegistro(
            where: [
                ['id_album_dado', $this->prop('id')],
            ],
            campo: ['imagem']
        )['imagem'] ?? '';
        if (empty($imagem)) {
            return;
        }
        $this->dado(['imagem' => $imagem])->where(['id', $this->prop('id')])->update();
        $this->imagem = $imagem;
    }
}
