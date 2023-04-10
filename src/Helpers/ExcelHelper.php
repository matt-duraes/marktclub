<?php

namespace Helpers;

use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Style;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Box\Spout\Writer\WriterInterface;
use JetBrains\PhpStorm\NoReturn;

final class ExcelHelper
{
    private array $dado = [];
    private WriterInterface $Writer;
    private Style $style;

    /**
     * @param  string     $font      [optional] Fonte que será usada
     * @param  int|float  $fontSize  [optional] Tamanho da fonte a será usada
     * @param  bool       $border    [optional] Se a planilha terá borda
     * @param  string     $path      [optional] Diretório base para de arquivo
     */
    public function __construct(
        private readonly string $font = 'Arial',
        private readonly int|float $fontSize = 10,
        private readonly bool $border = false,
        private readonly bool $bold = false,
        private readonly bool $italic = false,
        private readonly bool $quebrarTexto = false,
        private readonly string $path = DIRETORIO_PUBLICO,
    ) {
        $this->Writer = WriterEntityFactory::createXLSXWriter();
        $this->setarStylePadrao();
    }

    private function setarStylePadrao(): void
    {
        $style = (new StyleBuilder())
            ->setFontName($this->font)
            ->setFontSize($this->fontSize)
            ->setFontColor(Color::rgb(0, 0, 0));

        if ($this->border) {
            $style->setBorder(
                (new BorderBuilder())
                    ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
                    ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN)
                    ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
                    ->setBorderRight(Color::BLACK, Border::WIDTH_THIN)
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

    /**
     * Coloca um título para o arquivo
     *
     * @param  array  $titulo  Lista com os campos para a primeira linha do excel
     * @return ExcelHelper
     */
    public function titulo(array $titulo): ExcelHelper
    {
        $this->dado[] = WriterEntityFactory::createRowFromArray($titulo, $this->styleTitulo());
        return $this;
    }

    private function styleTitulo(): Style
    {
        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontName($this->font)
            ->setFontSize($this->fontSize)
            ->setFontColor(Color::rgb(255, 255, 255))
            ->setBackgroundColor(Color::rgb(50, 50, 50));

        if ($this->border) {
            $style->setBorder(
                (new BorderBuilder())
                    ->setBorderTop(Color::BLACK, Border::WIDTH_THIN)
                    ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN)
                    ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN)
                    ->setBorderRight(Color::BLACK, Border::WIDTH_THIN)
                    ->build()
            );
        }

        return $style->build();
    }

    /**
     * Adicionar uma linha ao arquivo
     *
     * @param  array  $dado  Dados para montar o excel
     * @return ExcelHelper
     */
    public function linha(array $dado): ExcelHelper
    {
        $this->dado[] = WriterEntityFactory::createRowFromArray($dado, $this->style);
        return $this;
    }

    /**
     * @param  string|null  $nome  Nome do arquivo
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws WriterNotOpenedException
     */
    public function salvar(string $nome = null): void
    {
        if (empty($nome)) {
            $nome = md5(uniqid(time()));
        }

        $nome = preg_replace(['/\.(xlsx|xls)$/', '/^\//'], ['', ''], $nome) . '.xlsx';
        $writer = $this->Writer;
        $writer->openToFile(preg_replace('/\/$/', '', $this->path) . '/' . $nome);
        $writer->addRows($this->dado);
        $writer->close();
    }

    /**
     * Gera a planilha e força o download
     *
     * @param  string|null  $nome  Nome do arquivo
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws WriterNotOpenedException
     */
    #[NoReturn] public function download(string $nome = null): void
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
}
