<?php

namespace Helpers;

use Erro\Excecao;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadHelper
{
    private string $nomeReal;
    private string $extensao;
    private string $diretorioFinal;
    private ?Image $imagem = null;

    /**
     * Manipula arquivos de upload
     *
     * @param null|UploadedFile $arquivo    Arquivo do UploadedFile para manipular
     * @param string            $diretorio  Diretório que deseja salvar o arquivo
     * @param array             $ext        Extensões aceitas
     * @param array             $mimeType   MimeTypes aceitos
     * @param null|string       $nome       Nome para o arquivo, caso não seja passado, pega o nome real do arquivo
     * @param null|int          $nomeMaximo Número de caracteres máximo para o nome
     * @param bool              $nomeForcar Força salvar com o nome mesmo que já exista um arquivo com o mesmo nome
     * @param null|int          $mbMaximo   MB máximo do arquivo
     * @param array             $mensagem   Mensagem personalidas de erro
     * @param string            $path       Path do diretório raiz
     */
    public function __construct(
        private ?UploadedFile $arquivo,
        private string $diretorio,
        private array $ext = [],
        private array $mimeType = [],
        private ?string $nome = null,
        private ?int $nomeMaximo = null,
        private bool $nomeForcar = false,
        private ?int $mbMaximo = null,
        private array $mensagem = [],
        private string $path = DIRETORIO_PUBLICO
    ) {
        $this->criarListaMimeTypeParaExtensao();
        $this->verificarSeArquivoMaiorQueMaximoDoSistema();
        $this->passarArquivoPeloAntiVirus();
        $this->converterExtEmMimeType();
        $this->pegarExtensao();
        $this->validarExtensao();
        $this->validarMimeType();
        $this->validarTamanhoArquivo();
        $this->pegarDadoParaSalvar();
        $this->validarSeDiretorioEValido();
        $this->pegarNomeArquivo();
    }

    /**
     * Nome final do arquivo
     *
     * @return string
     */
    public function nome()
    {
        return $this->nome;
    }

    /**
     * Nome original do arquivo
     *
     * @return string
     */
    public function nomeReal()
    {
        return $this->nomeReal;
    }

    /**
     * Extensão do arquivo
     *
     * @return string
     */
    public function extensao()
    {
        return $this->extensao;
    }

    /**
     * Valida se a imagem tem o tamanho informado
     *
     * @param  int  $width  A largura que a imagem deve ter
     * @param  int  $height A altura que a imagem deve ter
     * @param  bool $erro   true para retornar uma exceção ou false para retornar bool
     * @return bool Retorna true para se a imagem tiver válida
     */
    public function validarTamanho(int $width, int $height, bool $erro = true): bool
    {
        $largura = $this->largura();
        $altura = $this->altura();
        $validar = $width == $largura && $height == $altura;
        if ($erro && !$validar) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'A imagem deve ter ' . $width . 'x' . $height . ' pixels.');
        }
        return $validar;
    }

    public function tamanho()
    {
        if ($this->verificarSeArquivoEImagem()) {
            $tamanho = $this->imagem->filesize();
        } else {
            $tamanho = $this->arquivo->getSize();
        }
        return round(($tamanho / 1000) / 1000, 2);
    }

    public function largura()
    {
        if (!$this->verificarSeArquivoEImagem()) {
            throw new Excecao(
                titulo: 'Arquivo incorreto!',
                mensagem: 'Você só pode pegar a largura de um arquivo que seja uma imagem.'
            );
        }
        return $this->imagem->width();
    }

    public function altura()
    {
        if (!$this->verificarSeArquivoEImagem()) {
            throw new Excecao(
                titulo: 'Arquivo incorreto!',
                mensagem: 'Você só pode pegar a altura de um arquivo que seja uma imagem.'
            );
        }
        return $this->imagem->height();
    }

    public function salvar()
    {
        if (!$this->verificarSeArquivoEImagem()) {
            $this->arquivo->move($this->diretorioFinal, $this->nome);
            return $this;
        }
        $this->imagem->save($this->diretorioFinal . '/' . $this->nome);
        return $this;
    }

    /**
     * @param mixed $width  Largura que a imagem deve ficar
     * @param mixed $height Altura que a imagem deve ficar
     * @param mixed $top    Margin para o topo onde deve começar a cortar a imagem
     * @param mixed $left   Margin para a esquerda onde deve começar a cortar a imagem
     * @param int   $sobra  Valor a ser somado ao tamanho da imagem por questões de erro
     */
    public function cortar($width, $height, $top = null, $left = null, int $sobra = 0): self
    {
        if (!$this->verificarSeArquivoEImagem()) {
            throw new Excecao(
                titulo: 'Arquivo incorreto!',
                mensagem: 'Você só pode cortar um arquivo que seja uma imagem.'
            );
        }

        $imagem = $this->imagem;

        $imagemWidth = $imagem->width();
        $imagemHeight = $imagem->height();

        if ($sobra > 0) {
            $imagemWidth += $sobra;
            $imagemHeight += $sobra;
        }

        if ($imagemWidth < $width) {
            throw new Excecao(
                titulo: 'Imagem pequena!',
                mensagem: 'A largura da imagem é menor que o tamanho do corte desejado.'
            );
        } elseif ($imagemHeight < $height) {
            throw new Excecao(
                titulo: 'Imagem pequena!',
                mensagem: 'A altura da imagem é menor que o tamanho do corte desejado.'
            );
        }
        $top = is_numeric($top) ? (int)$top : null;
        $left = is_numeric($left) ? (int)$left : null;

        $this->imagem = $imagem->crop($width, $height, $left, $top);
        return $this;
    }

    /**
     * @param ?int $width  Tamanho que a imagem deve ficar
     * @param ?int $height Altura que a imagem deve ficar
     */
    public function redimencionar(?int $width = null, ?int $height = null): self
    {
        if (!$this->verificarSeArquivoEImagem()) {
            throw new Excecao(
                titulo: 'Arquivo incorreto!',
                mensagem: 'Você só pode cortar um arquivo que seja uma imagem.'
            );
        } elseif (empty($width) && empty($height)) {
            return mensagemErro('Tamanho obrigatório', 'Você deve passar o tamanho da largura e/ou altura.', 400);
        }

        $imagem = $this->imagem;

        $imagemWidth = $imagem->width();
        $imagemHeight = $imagem->height();

        if (is_numeric($width) && $imagemWidth < $width) {
            $width = $imagemWidth;
        }
        if (is_numeric($height) && $imagemHeight < $height) {
            $height = $imagemHeight;
        }

        $this->imagem = $imagem->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $this;
    }

    public function arquivo(): string
    {
        $this->validarSeArquivoExiste();
        return $this->nome;
    }

    public function diretorio(): string
    {
        $this->validarSeArquivoExiste();
        return $this->diretorio;
    }

    public function path(): string
    {
        $this->validarSeArquivoExiste();
        return $this->path;
    }

    public function validarSeArquivoExiste()
    {
        if (!file_exists($this->diretorioFinal . '/' . $this->nome)) {
            throw new Excecao(
                titulo: 'Ocorreu um erro!',
                mensagem: 'Não foi possível fazer o upload de seu arquivo, verifique o arquivo enviado ou a permissão.',
                status: 404
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function verificarSeArquivoMaiorQueMaximoDoSistema()
    {
        $maxFileSize = $this->arquivo->getMaxFilesize();
        $tamanhoArquivo = $this->arquivo->getSize();
        if (empty($tamanhoArquivo) || $tamanhoArquivo > $maxFileSize) {
            throw new Excecao(
                titulo: $this->mensagem['virus']['titulo'] ?? 'Arquivo muito grande!',
                mensagem: $this->mensagem['virus']['mensagem'] ?? 'O arquivo enviado tem um tamanho maior que o permitido pelo servidor.'
            );
        }
    }

    private function passarArquivoPeloAntiVirus()
    {
        return;
        if (eLocalhost()) {
            return;
        }
        $validar = (new AntiVirusHelper($this->arquivo->getPathname()))->validar();
        if (true !== $validar) {
            throw new Excecao(
                titulo: $this->mensagem['virus']['titulo'] ?? 'Erro ao validar arquivo!',
                mensagem: $this->mensagem['virus']['mensagem'] ?? 'O arquivo enviado não é um arquivo válido ou não foi possível validar sua segurança.'
            );
        }
    }

    private function converterExtEmMimeType()
    {
        $extLista = $this->ext;
        if (empty($extLista)) {
            return;
        }
        $mimeType = $this->mimeType;
        $mimeTypeLista = $this->mimeTypeLista;
        foreach ($extLista as $ext) {
            if (array_key_exists($ext, $mimeTypeLista) && !in_array($mimeTypeLista[$ext], $mimeType)) {
                if (is_array($mimeTypeLista[$ext])) {
                    foreach ($mimeTypeLista[$ext] as $mimeLista) {
                        $this->mimeType[] = $mimeLista;
                    }
                    continue;
                }
                $this->mimeType[] = $mimeTypeLista[$ext];
            }
        }
    }

    private function pegarExtensao()
    {
        $ext = $this->arquivo->getClientOriginalExtension();
        $mimeType = $this->arquivo->getMimeType();
        $ext = !empty($ext) ? $ext : $this->mimeTypeExt[$mimeType] ?? '';
        $this->extensao = str_replace('jpeg', 'jpg', $ext);
    }

    private function validarExtensao()
    {
        $extLista = $this->ext;
        if (empty($extLista)) {
            return;
        }
        if (!in_array($this->extensao, $extLista)) {
            throw new Excecao(
                titulo: $this->mensagem['ext']['titulo'] ?? 'Arquivo incorreto!',
                mensagem: $this->mensagem['ext']['mensagem'] ?? 'A extensão do arquivo não é uma extensão válida.'
            );
        }
    }

    private function validarMimeType()
    {
        $mimeType = $this->mimeType;
        if (empty($mimeType)) {
            return;
        }
        try {
            $arquivoMimeType = $this->arquivo->getMimeType();
        } catch (\Throwable) {
            $arquivoMimeType = '';
        }

        if (!in_array($arquivoMimeType, $mimeType)) {
            throw new Excecao(
                titulo: $this->mensagem['mime_type']['titulo'] ?? 'Arquivo incorreto!',
                mensagem: $this->mensagem['mime_type']['mensagem'] ?? 'O tipo de arquivo enviado não é um valor válido.'
            );
        }
    }

    private function validarTamanhoArquivo()
    {
        if (empty($this->mbMaximo)) {
            return;
        }
        $tamanhoArquivo = $this->arquivo->getSize();
        $tamanho = $this->converterBites($tamanhoArquivo);
        if (in_array($tamanho['medida'], ['B', 'KB']) || $tamanho['tamanho'] < $this->mbMaximo) {
            return;
        }
        throw new Excecao(
            titulo: $this->mensagem['mb']['titulo'] ?? 'Arquivo inválido!',
            mensagem: $this->mensagem['mb']['mensagem'] ?? 'O arquivo enviado deve ter no máximo ' . $this->mbMaximo . 'MB.'
        );
    }

    private function converterBites($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return [
            'tamanho' => round($bytes, $precision),
            'medida'  => $units[$pow]
        ];
    }

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

    private function pegarNomeArquivo()
    {
        $nome = $this->nome;
        $this->nomeReal = pathinfo($this->arquivo->getClientOriginalName(), PATHINFO_FILENAME);
        if (empty($this->nome)) {
            $nome = $this->nomeReal;
        }
        $nome = (new TextoHelper())->valor($nome)->slug('_')->r();
        $this->nome = $this->criarNomeUnico($nome, $this->extensao, 0);
    }

    private function criarNomeUnico(string $nome, string $ext, int $numero = 0)
    {
        $final = '';
        if ($numero > 0) {
            $final = '_' . $numero;
        }
        $nomeTemporario = $this->cortarNomeSeMaiorQueMaximo($nome, $final, $ext);
        if (file_exists($this->diretorioFinal . '/' . $nomeTemporario . '.' . $ext) && !$this->nomeForcar) {
            $numero += 1;
            return $this->criarNomeUnico($nome, $ext, $numero);
        }
        return $nomeTemporario . '.' . $ext;
    }

    private function cortarNomeSeMaiorQueMaximo(string $nome, string $final, string $ext)
    {
        $maximo = $this->nomeMaximo;
        if (empty($maximo)) {
            return $nome . $final;
        }
        $tamanhoNome = mb_strlen($nome, 'UTF-8');
        $tamanhoFinal = mb_strlen($final, 'UTF-8');
        $tamanhoExt = mb_strlen($ext, 'UTF-8');
        $tamanhoNomeInteiro = $tamanhoNome + $tamanhoFinal + $tamanhoExt + 1;
        if ($tamanhoNomeInteiro <= $maximo) {
            return $nome . $final;
        }
        $tamanhoMaximoNome = $maximo - ($tamanhoFinal + $tamanhoExt + 1);
        return (new TextoHelper($nome))->cortar($tamanhoMaximoNome, '', true) . $final;
    }

    private function verificarSeArquivoEImagem()
    {
        if (!is_null($this->imagem) && $this->imagem instanceof Image) {
            return true;
        }
        if (in_array($this->arquivo->getMimeType(), ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'])) {
            $img = new ImageManager();
            $this->imagem = $img->make($this->arquivo->getPathname());
            return true;
        }
        return false;
    }

    private function criarListaMimeTypeParaExtensao()
    {
        $retorno = [];
        foreach ($this->mimeTypeLista as $ext => $mime) {
            if (is_string($mime)) {
                $retorno[$mime] = $ext;
                continue;
            }
            foreach ($mime as $val) {
                $retorno[$val] = $ext;
            }
        }
        $this->mimeTypeExt = $retorno;
    }

    private array $mimeTypeExt = [];
    private array $mimeTypeLista = [
        'psd' => 'image/vnd.adobe.photoshop',
        'avi' => 'video/x-msvideo',
        'ai'  => [
            'application/postscript', 'application/pdf'
        ],
        'csv' => [
            'text/comma-separated-values', 'text/csv', 'application/vnd.ms-excel', 'text/x-comma-separated-values'
        ],
        'cdr'  => 'application/cdr',
        'doc'  => 'application/msword',
        'dot'  => 'application/msword',
        'docx' => [
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        'eps'  => 'application/postscript',
        'gif'  => 'image/gif',
        'gz'   => 'application/gzip',
        'gtar' => 'application/x-gtar',
        'ico'  => 'image/x-icon',
        'jpe'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'midi' => 'audio/midi',
        'mid'  => 'audio/midi',
        'mov'  => 'video/quicktime',
        'mp3'  => 'audio/mpeg',
        'mpeg' => 'video/mpeg',
        'mpg'  => 'video/mpeg',
        'ogg'  => 'application/ogg',
        'pdf'  => 'application/pdf',
        'png'  => 'image/png',
        'pps'  => 'application/mspowerpoint',
        'ppt'  => [
            'application/mspowerpoint', 'application/vnd.ms-powerpoint'
        ],
        'pptx' => [
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/mspowerpoint', 'application/vnd.ms-powerpoint'
        ],
        'ppz'  => 'application/mspowerpoint',
        'pot'  => 'application/mspowerpoint',
        'rar'  => 'application/x-rar-compressed',
        'svg'  => 'image/svg+xml',
        'tar'  => 'application/x-tar',
        'tiff' => 'image/tiff',
        'tif'  => 'image/tiff',
        'tgz'  => 'application/x-compressed',
        'txt'  => 'text/plain',
        'xla'  => 'application/msexcel',
        'xlc'  => 'application/vnd.ms-excel',
        'xls'  => [
            'application/msexcel', 'application/excel', 'application/vnd.ms-excel'
        ],
        'xlsx' => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msexcel', 'application/excel', 'application/vnd.ms-excel'
        ],
        'zip' => [
            'application/x-zip-compressed', 'application/zip'
        ],
    ];
}
