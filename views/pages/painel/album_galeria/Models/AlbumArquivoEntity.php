<?php

namespace Painel\Album\Models;

use ORM\Entity;
use Helpers\UploadHelper;
use Painel\Album\Models\AlbumDadoEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class AlbumArquivoEntity extends Entity
{
    protected string $ormTabela = TABELA_ALBUM_ARQUIVO;
    protected array $ormBuscar = ['id_album_dado', 'titulo', 'imagem', 'status'];
    protected array $ormInsert = ['id_album_dado', 'imagem'];
    protected array $ormSalvar = ['titulo', 'status'];
    protected array $ormUpdate = ['ordem'];

    private AlbumDadoEntity $Album;
    public int $id_album_dado;
    public UploadHelper|string $imagem;

    const DIRETORIO = DIRETORIO_VIEW . '/arquivos/album/';

    public function __construct(
        protected ?string $album = null,
        protected ?UploadedFile $arquivo = null,
        protected ?bool $capa = null
    ) {
        parent::__construct();
    }

    protected function regraPosDestruir()
    {
        $imagem = $this->imagem;
        if (!empty($imagem) && file_exists(self::DIRETORIO . $imagem)) {
            unlink(self::DIRETORIO . $imagem);
        }
    }

    protected function regraInsert()
    {
        $Album = new AlbumDadoEntity();
        try {
            $Album->uuid($this->album);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi possível encotrar o álbum dessa imagem.');
        }

        $this->id_album_dado = $Album->get('id');
        $this->imagem = $this->setarImagem($Album->tipo, $Album->width, $Album->height, $Album->extensao);
        $this->titulo = $this->imagem->nomeReal();
        $this->status = 1;
        $this->Album = $Album;
    }
    private function setarImagem($tipo, $width, $height, $ext): UploadHelper
    {
        $Imagem = new UploadHelper(
            arquivo: $this->arquivo,
            diretorio: 'album',
            ext: $ext,
            nome: nomeUnico(),
            nomeMaximo: 36
        );
        if ($tipo == 2) {
            $Imagem->validarTamanho($width, $height);
        } elseif ($tipo == 3) {
            $Imagem->redimencionar($width);
        } elseif ($tipo == 4) {
            $Imagem->redimencionar(height: $height);
        }
        return $Imagem;
    }
    protected function regraPosInsert()
    {
        if ($this->capa) {
            $this->Album->imagem = $this->imagem;
            $this->Album->salvar();
        }
    }

    protected function getImagem()
    {
        return arquivoPublico('/album/', $this->imagem);
    }
}
