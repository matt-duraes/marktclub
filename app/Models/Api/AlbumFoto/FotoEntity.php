<?php

namespace App\Models\Api\AlbumFoto;

use App\Classes\Geral\Status;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\Entity;

class FotoEntity extends Entity
{
    public string $album;
    public string $titulo;
    public string $imagem;
    public int $ordem;
    public Status $status;
    protected string $ormTabela = TABELA_ALBUM_FOTO;
    protected array $ormBuscar = [
        'titulo', 'imagem', 'ordem', 'status'
    ];
    protected array $ormInsert = [
        'id_album_dado', 'imagem'
    ];
    protected array $ormSalvar = [
        'titulo', 'ordem', 'status'
    ];
    protected string $ormValidarSalvar = '
        imagem|Imagem|obrigatorio|vazio
        titulo|Título|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_album_dado;

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->imagem = arquivoPrivado($this->imagem);
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->validarAlbum();
        $this->pegarAlbum();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarAlbum(): void
    {
        if (empty($this->album)) {
            mensagemErro(
                'Campo não encontrado!',
                'O Álbum informado é obrigatorio.'
            );
        }

        if (!validarUuid($this->album, false)) {
            mensagemErro(
                'Campo inválido!',
                'O Álbum informado não é válido.'
            );
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function pegarAlbum(): void
    {
        $ormHelper = new OrmHelper(TABELA_ALBUM_DADO);
        $idAlbum = $ormHelper->pegarPrimeiroRegistro(
            ['uuid', $this->album],
            ['id'],
            'object'
        );

        if (empty($idAlbum)) {
            mensagemErro(
                'ÁLbum inválido!',
                'O Álbum informado não é válido ou inexistente.'
            );
        }
        $this->id_album_dado = $idAlbum->id;
    }
}
