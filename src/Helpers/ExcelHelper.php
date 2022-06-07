<?php

namespace Helpers;

use Box\Spout\Writer\WriterInterface;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Style;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

final class ExcelHelper
{
    private $dado = [];
    private WriterInterface $Writer;
    private Style $style;

    /**
     * @param string     $font      Fonte que será usada
     * @param int|float  $fontSize  Tamanho da fonte a será usada
     * @param bool       $border    Se a planilha terá borda
     * @param string     $path      Diretório base para de arquivo
     */
    public function __construct(
        private string $font = 'Arial',
        private int | float $fontSize = 10,
        private bool $border = false,
        private bool $bold = false,
        private bool $italic = false,
        private bool $quebrarTexto = false,
        private string $path = DIRETORIO_PUBLICO,
    ) {
        $this->Writer = WriterEntityFactory::createXLSXWriter();
        $this->setarStylePadrao();
    }

    /**
     * Coloca um título para o arquivo
     *
     * @param array $titulo Lista com os campos para a primeira linha do excel
     * @return Self
     */
    public function titulo(array $titulo): self
    {
        $this->titulo = true;
        $this->dado[] = WriterEntityFactory::createRowFromArray($titulo, $this->styleTitulo());
        return $this;
    }

    /**
     * Adicionar uma linha ao arquivo
     *
     * @param array $dado Dados para montar o excel
     * @return Self
     */
    public function linha(array $dado): self
    {
        $this->dado[] = WriterEntityFactory::createRowFromArray($dado, $this->style);
        return $this;
    }

    /**
     * Gera a planilha e força o download
     *
     * @param string $nome Nome do arquivo
     */
    public function download(string $nome = ''): void
    {
        if (empty($nome)) {
            $nome = md5(uniqid(time()));
        }

        $writer = $this->Writer;
        $writer->openToBrowser($nome);
        $writer->addRows($this->dado);

        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename=' . $nome . '.xlsx');

        $writer->close();
        exit();
    }

    private function setarStylePadrao(): void
    {
        $style = (new StyleBuilder)
            ->setFontName($this->font)
            ->setFontSize($this->fontSize)
            ->setFontColor(Color::rgb(0, 0, 0));

        if ($this->border) {
            $style->setBorder(
                (new BorderBuilder)
                    ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->build()
            );
        }

        if ($this->bold) {
            $style->setFontBold();
        }
        if ($this->italic) {
            $style->setFontItalic();
        }
        if ($this->quebrarTexto) {
            $style->setShouldWrapText();
        }

        $this->style = $style->build();
    }

    private function styleTitulo(): Style
    {
        $style = (new StyleBuilder)
            ->setFontBold()
            ->setFontName($this->font)
            ->setFontSize($this->fontSize)
            ->setFontColor(Color::rgb(255, 255, 255))
            ->setBackgroundColor(Color::rgb(50, 50, 50));

        if ($this->border) {
            $style->setBorder(
                (new BorderBuilder)
                    ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                    ->build()
            );
        }

        return $style->build();
    }
}
