<?php

namespace App\Models\Api\EnqueteMercado;

use App\Classes\EnqueteMercado\Experiencia;
use App\Classes\EnqueteMercado\Fidelidade;
use App\Classes\EnqueteMercado\Frequencia;
use App\Classes\EnqueteMercado\Gasto;
use App\Classes\EnqueteMercado\Importancia;
use App\Classes\EnqueteMercado\Padrao;
use App\Classes\EnqueteMercado\Produtos;
use ORM\ORM;

class DownloadModel extends ORM
{
    protected string $ormTabela = TABELA_ENQUETE_MERCADO;

    /**
     * @param array|null $campos
     */
    public function __construct(
        private readonly ?array $campos = null
    ) {
        parent::__construct();
    }

    /**
     * @return array
     * @throws \Erro\Excecao
     */
    public function download(): array
    {
        $enquetes = $this->buscarEnquete($this->campos);
        if (!array_key_exists('0', $enquetes)) {
            $this->erroDownloadPadrao();
        }
        return $this->montarRetornoDownload($enquetes);
    }

    /**
     * @param array $campos
     *
     * @return mixed
     * @throws \Erro\Excecao
     */
    private function buscarEnquete(array $campos): mixed
    {
        return $this
            ->campo($campos)
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->campo(['titulo'], 'empresa')
            ->leftJoin('id', 'id_admin_empresa')
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->campo(['nome'], 'usuario')
            ->leftJoin('id', 'id_usuario_cliente')
            ->read();
    }

    /**
     * @return void
     * @throws \Erro\Excecao
     */
    private function erroDownloadPadrao(): void
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
    }

    /**
     * @param array $indicacoes
     *
     * @return array
     */
    private function montarRetornoDownload(array $indicacoes): array
    {
        $i = 0;
        $retorno = [];
        foreach ($indicacoes as $indicacao) {
            foreach ($indicacao as $coluna => $valor) {
                if ($coluna == 'fidelidade') {
                    $valor = (new Fidelidade())->nome($valor);
                } elseif ($coluna == 'produtos') {
                    $valor = (new Produtos())->nome($valor);
                } elseif ($coluna == 'gasto') {
                    $valor = (new Gasto())->nome($valor);
                } elseif ($coluna == 'importancia') {
                    $valor = (new Importancia())->nome($valor);
                } elseif ($coluna == 'cashback') {
                    $valor = (new Padrao())->nome($valor);
                } elseif ($coluna == 'frequencia') {
                    $valor = (new Frequencia())->nome($valor);
                } elseif ($coluna == 'resgate') {
                    $valor = (new Padrao())->nome($valor);
                } elseif ($coluna == 'experiencia') {
                    $valor = (new Experiencia())->nome($valor);
                } elseif ($coluna == 'indicaria') {
                    $valor = (new Padrao())->nome($valor);
                } else {
                    $valor = strNull($valor);
                }
                $retorno[$i][$coluna] = $valor;
            }
            $i++;
        }
        return $retorno;
    }
}
