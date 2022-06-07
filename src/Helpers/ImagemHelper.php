<?php

namespace Helpers;

use Erro\Excecao;
use Intervention\Image\Image;
use Intervention\Image\Gd\Font;
use Intervention\Image\ImageManager;

final class ImagemHelper
{
    private Image $imagem;
    private string $diretorioFinal;

    public function __construct(
        private string $arquivo,
        private string $diretorio,
        private string $nome,
        private string $path = DIRETORIO_PUBLICO
    ) {
        $this->pegarDadoParaSalvar();
        $this->validarSeDiretorioEValido();

        $this->verificarSeArquivoExiste($this->arquivo);
        $this->verificarSeArquivoEImagem($this->arquivo);
        $this->passarArquivoPeloAntiVirus($this->arquivo);
        $this->imagem = (new ImageManager)->make($this->arquivo);
    }

    public function add($arquivo): self
    {
        $this->verificarSeArquivoExiste($arquivo);
        $this->verificarSeArquivoEImagem($arquivo);
        $this->passarArquivoPeloAntiVirus($arquivo);
        $this->imagem->insert($arquivo);
        return $this;
    }

    /**
     * Adiciona um texto a imagem
     *
     * @param string        $texto      Texto que deseja adicionar
     * @param string        $posicao    Posição do texto em relação a imagem podendo ser: top-left, top, top-right, left, center, right, bottom-left, bottom ou bottom-right
     * @param int           $x          Posição do eixo x em relação a imagem.
     * @param int           $y          Posição do eixo y em relação a imagem.
     * @param null|string   $fonte      Path da fonte que deseja usar
     * @param null|int      $tamanho    Tamanho da fonte
     * @param null|string   $cor        Cor hexadecimal para a fonte
     * @param null|int      $angulo     Angulo que o texto deve ficar
     * @return Self
     */
    public function texto(
        string $texto,
        string $posicao = 'top-left',
        int $x = 0,
        int $y = 0,
        ?string $fonte = null,
        ?int $tamanho = null,
        ?string $cor = null,
        ?int $angulo = null
    ): self {
        $font = new Font(htmlentities($texto));
        $font->valign('top');
        if (!empty($cor)) {
            $font->color($cor);
        }
        if (!empty($fonte)) {
            $font->file($fonte);
        }
        if ($tamanho > 0) {
            $font->size($tamanho);
        }
        if ($angulo > 0) {
            $font->angle($angulo);
        }
        $dimensao = $font->getBoxSize();

        $Manager = new ImageManager();
        $canvas = $Manager->canvas($dimensao['width'], $dimensao['height']);
        $font->applyToImage($canvas);
        $imagemTexto = $Manager->make($canvas->encode('data-url'));
        $imagemTexto->save($this->diretorioFinal . '/' . $this->nome);
        $this->imagem->insert($imagemTexto, $posicao, $x, $y);
        return $this;
    }

    public function salvar(): self
    {
        if (is_null($this->imagem)) {
            throw new Excecao(
                titulo: 'Ocorreu um erro!',
                mensagem: 'Não foi enviado um arquivo para ser salvo.',
                status: 404
            );
        }

        $this->imagem->save($this->diretorioFinal . '/' . $this->nome);
        return $this;
    }

    public function imagem(): string
    {
        if (!file_exists($this->diretorioFinal . '/' . $this->nome)) {
            throw new Excecao(
                titulo: 'Ocorreu um erro!',
                mensagem: 'Não foi possível fazer o upload de seu arquivo, verifique o arquivo enviado ou a permissão.',
                status: 404
            );
        }
        return $this->nome;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function pegarDadoParaSalvar()
    {
        $this->path = '/' . preg_replace(['/^\//', '/\/$/'], '', $this->path);
        $this->diretorio = '/' . preg_replace(['/^\//', '/\/$/'], '', $this->diretorio);
        $this->diretorioFinal = $this->path . $this->diretorio;
    }

    private function validarSeDiretorioEValido()
    {
        if (!is_dir($this->diretorioFinal)) {
            throw new Excecao(
                titulo: 'Erro no diretório!',
                mensagem: 'O diretório de destino não existe.'
            );
        } elseif (!is_writable($this->diretorioFinal)) {
            throw new Excecao(
                titulo: 'Erro no diretório!',
                mensagem: 'Você não tem permissão para salvar nesse diretório.'
            );
        }
    }

    private function verificarSeArquivoExiste($arquivo)
    {
        if (!file_exists($arquivo)) {
            throw new Excecao(
                titulo: 'Erro ao validar arquivo!',
                mensagem: 'O arquivo enviado não foi encontrado.',
                status: 404
            );
        }
    }
    private function verificarSeArquivoEImagem($arquivo): void
    {
        $imagem = getimagesize($arquivo);
        if (!in_array($imagem['mime'] ?? '', ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'])) {
            throw new Excecao(
                titulo: 'Erro ao validar arquivo!',
                mensagem: 'O arquivo enviado não é um arquivo de imagem.'
            );
        }
    }
    private function passarArquivoPeloAntiVirus($arquivo)
    {
        $validar = (new AntiVirusHelper($arquivo))->validar();
        if (true !== $validar) {
            throw new Excecao(
                titulo: $this->mensagem['virus']['titulo'] ?? 'Erro ao validar arquivo!',
                mensagem: $this->mensagem['virus']['mensagem'] ?? 'O arquivo enviado não é um arquivo válido ou não foi possível validar sua segurança.'
            );
        }
    }
}
