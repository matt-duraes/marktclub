<?php

namespace Erro;

final class Alerta extends \Exception
{
    protected array $retorno;
    /**
     * @param string      $mensagem      Mensagem de erro de retorno para o programador ou log
     * @param string      $titulo        Titulo da explicação do erro
     * @param string      $texto         Texto da explicação do erro
     * @param array       $sugestao      Array com sugestões de como corrigir o problema
     * @param int         $codigo        Código do erro
     * @param string      $arquivo       Força um nome de arquivo caso ele exista no trace
     * @param Exceptcion  $previous      Próxima Excecão que será lançada
     * @param bool|int    $traceRemover  Se true, vai remover o primeiro arquivo do trace, se int,
     *                                   vai remover  a quantidade informada
     */
    public function __construct(
        private string $mensagem,
        private string $titulo = '',
        private string $texto = '',
        private array $sugestao = [],
        private int $codigo = 0,
        private string $arquivo = '',
        ?\Exception $previous = null
    ) {
        parent::__construct(message: $mensagem, code: $codigo, previous: $previous);
        $this->retorno = $this->retorno();
    }

    public function retorno(): array
    {
        return [
            'sugestao' => [
                'titulo' => $this->titulo,
                'texto' => $this->texto,
                'sugestao' => $this->sugestao,
            ],
            'arquivo' => $this->arquivo
        ];
    }
}
