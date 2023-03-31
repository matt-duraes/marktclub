<?php

namespace Helpers;

use Mpdf\Mpdf;
use Erro\Excecao;
use Mpdf\HTMLParserMode;

final class PdfHelper
{
    private Mpdf $PDF;
    private array $orientacaoLista;

    /**
     * @param null|string   $titulo         Título do documento
     * @param null|string   $autor          Nome do autor do documento
     * @param null|string   $orientacao     Orientação do papel podendo ser: paisagem ou retrato
     * @param int           $largura        Largura da página em milimetros
     * @param int           $altura         Altura da página em milimetros
     * @param int           $margemTopo     Margin do topo do documento em milimetro
     * @param int           $margemDireita  Margin da direita do documento em milimetro
     * @param int           $margemBaixo    Margin de baixo do documento em milimetro
     * @param int           $margemEsquerda Margin da esquerda do documento em milimetro
     * @param int           $margemHeader   Margin do header do documento em milimetro
     * @param int           $margemFooter   Margin do footer do documento em milimetro
     * @param string        $diretorio      Diretório temporário para criar o arquivo
     */
    public function __construct(
        private ?string $titulo = null,
        private ?string $autor = null,
        private string $orientacao = 'retrato',
        private int $largura = 210,
        private int $altura = 297,
        private int $margemTopo = 16,
        private int $margemDireita = 15,
        private int $margemBaixo = 16,
        private int $margemEsquerda = 15,
        private int $margemHeader = 9,
        private int $margemFooter = 9,
        private string $fonte = 'Arial',
        private string $diretorio = DIRETORIO_PRIVADO . '/temp'
    ) {
        $this->orientacaoLista = [
            'L' => 'L',
            'paisagem' => 'L',
            'retrato' => 'P',
            'P' => 'P'
        ];
        $this->PDF = new Mpdf([
            'tempDir' => $this->diretorio,
            'mode' => 'utf-8',
            'format' => [$this->largura, $this->altura],
            'orientation' => $this->orientacaoLista[$this->orientacao] ?? 'L',
            'margin_left' => $this->margemEsquerda,
            'margin_right' => $this->margemDireita,
            'margin_top' => $this->margemTopo,
            'margin_bottom' => $this->margemBaixo,
            'margin_header' => $this->margemHeader,
            'margin_footer' => $this->margemFooter,
            'default_font' => $this->fonte
        ]);
        if (!empty($this->titulo)) {
            $this->PDF->SetTitle($this->titulo);
        }
        if (!empty($this->autor)) {
            $this->PDF->SetAuthor($this->autor);
        }
    }

    /**
     * Adiciona uma nova fonte
     *
     * @param string $fonte Fonte que deseja usar
     * @return Self
     */
    public function fonte(string $fonte, ?string $familia = null): self
    {
        $familia = $familia == null ? arquivoNome($fonte) : $familia;
        $this->PDF->AddFontDirectory(arquivoDiretorio($fonte));
        $this->PDF->fontdata[$familia] = [
            'R' => $fonte
        ];
        return $this;
    }

    /**
     * Adiciona uma nova página
     *
     * @param null|string $orientacao Orientacão da página podendo ser retrato ou paisagem, deixar null para padrão
     * @return Self
     */
    public function pagina(?string $orientacao = null): self
    {
        $orientacao = $orientacao == null ? null : $this->orientacaoLista[$orientacao] ?? 'P';
        $this->PDF->AddPage($orientacao);
        return $this;
    }

    /**
     * Adiciona um HTML ao arquivo PDF
     *
     * @param string $html HTML que deseja adicionar
     * @return Self
     */
    public function html(string $html): self
    {
        $this->setarHtml($html);
        return $this;
    }

    /**
     * Adicionar um link
     *
     * @param string        $link
     * @param null|string   $texto
     * @param null|string   $target
     * @return Self
     */
    public function link(string $link, ?string $texto = null, ?string $target = null): self
    {
        $texto = $texto == null ? $link : $texto;
        $target = in_array($target, ['_self', '_blank']) ? $target : '_self';
        $this->setarHtml('<a href="' . $link . '" target="' . $target . '">' . $texto . '</a>');
        return $this;
    }

    /**
     * Coloca uma quebra de linha
     *
     * @return Self
     */
    public function quebraLinha(): self
    {
        $this->setarHtml('<br>');
        return $this;
    }

    /**
     * Adicionar um título ao arquivo
     *
     * @param string    $titulo     Título que deseja adicionar
     * @param int       $fonte      Tamanho da fonte
     * @param string    $cor        Cor da fonte
     * @return Self
     */
    public function titulo(string $titulo, int $fonte = 24, string $cor = '#000000'): self
    {
        $this->setarHtml('<h1 style="color=' . $cor . '; font-size: ' . $fonte . 'px">' . $titulo . '</h1>');
        return $this;
    }

    /**
     * Adicionar um subtítulo ao arquivo
     *
     * @param string $subTitulo Subtítulo que deseja adicionar
     * @return Self
     */
    public function subTitulo(string $subTitulo, int $fonte = 20, string $cor = '#000000'): self
    {
        $this->setarHtml('<h2 style="color=' . $cor . '; font-size: ' . $fonte . 'px">' . $subTitulo . '</h2>');
        return $this;
    }

    /**
     * Adicionar um texto ao arquivo
     *
     * @param string $texto Texto que deseja adicionar
     * @return Self
     */
    public function texto(string $texto, int $fonte = 14, string $cor = '#000000'): self
    {
        $this->setarHtml('<p style="color=' . $cor . '; font-size: ' . $fonte . 'px">' . $texto . '</p>');
        return $this;
    }

    public function imagem(
        string $arquivo,
        int $x = 0,
        int $y = 0,
        int $largura = 0,
        int $altura = 0,
        string $extensao = ''
    ): self {
        if (!file_exists($arquivo)) {
            throw new Excecao('A imagem ' . $arquivo . ' não foi encontrada.');
        }
        $this->PDF->Image($arquivo, $x, $y, $largura, $altura, $extensao);
        return $this;
    }

    /**
     * Seta o header do documento
     *
     * @param null|string   $esquerda   Texto da esquerda do header. Passar: algo + {PAGINA} para paginar, ex: Pag: {PAGINA}
     * @param null|string   $centro     Texto do centro do header. Passar: algo + {PAGINA} para paginar, ex: Pag: {PAGINA}
     * @param null|string   $direita    Texto da direita do header. Passar: algo + {PAGINA} para paginar, ex: Pag: {PAGINA}
     * @param int           $fonte      Tamanho da fonte do header
     * @param bool          $bold       Se o texto vai ter bold
     * @param bool          $italic     Se o texto vai ter itálico
     * @return Self
     */
    public function header(
        ?string $esquerda = null,
        ?string $centro = null,
        ?string $direita = null,
        int $fonte = 10,
        bool $bold = false,
        bool $italic = false
    ): self {
        $texto = [
            $esquerda == null ? '' : str_replace('{PAGINA}', '{PAGENO}', $esquerda),
            $centro == null ? '' : str_replace('{PAGINA}', '{PAGENO}', $centro),
            $direita == null ? '' : str_replace('{PAGINA}', '{PAGENO}', $direita),
        ];

        $this->PDF->SetHeader(implode('|', $texto));
        $this->PDF->defaultheaderfontsize = $fonte;
        if ($bold && $italic) {
            $this->PDF->defaultheaderfontstyle = 'BI';
        } elseif ($bold) {
            $this->PDF->defaultheaderfontstyle = 'B';
        } elseif ($italic) {
            $this->PDF->defaultheaderfontstyle = 'I';
        }
        return $this;
    }

    /**
     * Adiciona um header passando HTML
     *
     * @param string $html HTML que deseja adicionar. Passar: algo + {PAGINA} para paginar, ex: <p>Pag:</p> <spam>{PAGINA}</spam>
     * @return Self
     */
    public function headerHtml(string $html): self
    {
        $this->PDF->SetHTMLHeader(str_replace('{PAGINA}', '{PAGENO}', $html));
        return $this;
    }

    /**
     * Seta o footer do documento
     *
     * @param null|string   $esquerda   Texto da esquerda do header
     * @param null|string   $centro     Texto do centro do header
     * @param null|string   $direita    Texto da direita do header
     * @param int           $fonte      Tamanho da fonte do header
     * @param bool          $bold       Se o texto vai ter bold
     * @param bool          $italic     Se o texto vai ter itálico
     * @return Self
     */
    public function footer(
        ?string $esquerda = null,
        ?string $centro = null,
        ?string $direita = null,
        int $fonte = 10,
        bool $bold = false,
        bool $italic = false
    ): self {
        $texto = [
            $esquerda == null ? '' : str_replace('{PAGINA}', '{PAGENO}', $esquerda),
            $centro == null ? '' : str_replace('{PAGINA}', '{PAGENO}', $centro),
            $direita == null ? '' : str_replace('{PAGINA}', '{PAGENO}', $direita),
        ];

        $this->PDF->defaultfooterfontsize = $fonte;
        if ($bold && $italic) {
            $this->PDF->defaultfooterfontstyle = 'BI';
        } elseif ($bold) {
            $this->PDF->defaultfooterfontstyle = 'B';
        } elseif ($italic) {
            $this->PDF->defaultfooterfontstyle = 'I';
        }

        $this->PDF->SetFooter(implode('|', $texto));
        return $this;
    }

    /**
     * Adiciona um footer passando HTML
     *
     * @param string $html HTML que deseja adicionar
     * @return Self
     */
    public function footerHtml(string $html): self
    {
        $this->PDF->SetHTMLFooter(str_replace('{PAGINA}', '{PAGENO}', $html));
        return $this;
    }

    /**
     * Adiciona uma marca d'água no documento
     *
     * @param string    $imagem     Imagem que deseja usar
     * @param float     $opacidade  Opacidade da imagem podendo ser de 0 a 1
     * @param null|int  $x          Posição X da imagem (obrigatório passar o parametro $y), deixar null para centralidar
     * @param null|int  $y          Posição Y da imagem (obrigatório passar o parametro $x), deixar null para ficar no topo
     * @return Self
     */
    public function marcaDagua(string $imagem, float $opacidade = 0.2, ?int $x = null, ?int $y = null): self
    {
        $posicao = 'D';
        if (is_int($x) && is_int($y)) {
            $posicao = [$x, $y];
        }
        $this->PDF->SetWatermarkImage($imagem, $opacidade, 'D', $posicao);
        $this->PDF->showWatermarkImage = true;
        return $this;
    }

    /**
     * Adiciona arquivo CSS
     *
     * @param string $css CSS para ser adicionado a documento
     * @return Self
     */
    public function css(string $css): self
    {
        $this->setarCss($css);
        return $this;
    }
    /**
     * Path para o arquivo CSS que será adicionado
     *
     * @param string $arquivo path do arquivo
     * @return Self
     */
    public function cssArquivo(string $arquivo): self
    {
        if (!file_exists($arquivo)) {
            return $this;
        }
        $this->setarCss(file_get_contents($arquivo));
        return $this;
    }
    private function setarCss($css): void
    {
        $this->PDF->WriteHTML($css, HTMLParserMode::HEADER_CSS);
    }

    /**
     * Abre o documento criado
     */
    public function abrir()
    {
        $this->PDF->Output();
    }

    /**
     * Salvar arquivo em um diretório
     */
    public function salvar($nome, $destino)
    {
        $this->PDF->Output($nome, $destino);
    }

    /**
     * Pega ou devolte a classe Mpdf
     *
     * @param Mpdf|null $mpdf Classe Mpdf
     * @return Mpdf|Self
     */
    public function mPdf(?Mpdf $mpdf = null): Mpdf|self
    {
        if ($mpdf == null) {
            return $this->PDF;
        }
        $this->PDF = $mpdf;
        return $this;
    }

    private function setarHtml($html)
    {
        $this->PDF->WriteHTML($html, HTMLParserMode::HTML_BODY);
    }
}
