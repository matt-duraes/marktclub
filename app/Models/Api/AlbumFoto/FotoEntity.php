<?php

namespace App\Models\Api\AlbumFoto;

use ORM\Entity;
use Helpers\OrmHelper;
use Helpers\UploadHelper;
use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FotoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_ALBUM_FOTO;
    protected array $ormInsert = [
        'id_album_dado', 'imagem'
    ];
    protected array $ormSalvar = [
        'titulo', 'ordem', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'imagem', 'ordem', 'status'
    ];
    private int $idEmpresa;
    private string $diretorio = 'album_foto';
    protected int $id_album_dado;
    public string $album;
    public string $titulo;
    public string|UploadedFile|UploadHelper $imagem;
    public int $ordem;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }

    protected function regraPosBuscar()
    {
        $this->imagem = arquivoPublico($this->diretorio, $this->imagem);
    }

    protected function regraInsert()
    {
        $this->validarInsert();
        $this->pegarIdAlbum();
        $this->criarUploadImagem();
    }

    private function validarInsert()
    {
        if (!$this->pExiste('album', vazio: false)) {
            mensagemErro('Campo obrigatório', 'Você deve passar um album para a foto.');
        } elseif (!$this->pExiste('imagem') || !($this->imagem instanceof UploadedFile)) {
            mensagemErro('Campo inválido', 'A imagem enviada não pode ser validada.');
        }
    }

    private function pegarIdAlbum()
    {
        $Album = (new OrmHelper(TABELA_ALBUM_DADO))->pegarPrimeiroRegistro(
            where: [
                ['id_admin_empresa', $this->idEmpresa],
                ['id_album_dado', $this->album]
            ],
            campo: ['id'],
            erroMensagem: 'Não foi possível achar o álbum selecionado, por favor, tente novamente.'
        );
        $this->id_album_dado = $Album['id'];
    }

    private function criarUploadImagem()
    {
        $this->imagem = new UploadHelper(
            arquivo: $this->imagem,
            diretorio: $this->diretorio,
            ext: ['jpg', 'jpeg', 'png'],
            nome: uuid(),
        );
        $this->titulo = $this->imagem->nomeReal();
    }
}
