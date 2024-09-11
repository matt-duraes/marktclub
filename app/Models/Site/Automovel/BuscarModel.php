<?php

namespace App\Models\Site\Automovel;

use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\Status as ParceiroLojaStatus;
use App\Classes\ParceiroLoja\TipoProcedimento;
use App\Helpers\ClubeApiHelper;
use Erro\Excecao;
use Helpers\MarkdownHelper;
use Modules\Botao;
use Modules\Dinheiro;
use stdClass;

final class BuscarModel extends ClubeApiHelper
{
    private array $retorno = [];

    /**
     * @param string $parceiro
     * @param string $modelo
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly string $parceiro,
        private readonly string $modelo
    ) {
        parent::__construct();
        $this->buscarParceiro();
        $this->buscarModelo();
        $this->buscarVersoes();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function buscarParceiro(): void
    {
        $parceiro = $this
            ->validar(
                'Ops! Não conseguimos encontrar o parceiro',
                'Página não encontrada',
                404
            )
            ->get('/parceiro-loja/' . $this->parceiro)
            ->object();

        if ($parceiro->dado->status !== ParceiroLojaStatus::CONCLUIDO) {
            mensagemStatus(404);
        }

        $this->retorno['parceiro']['id'] = $parceiro->dado->id;
        $this->retorno['texto_procedimento'] = $parceiro->dado->texto_procedimento;
        $this->retorno['procedimento'] = $parceiro->dado->tipo_procedimento;
        $this->retorno['procedimento_cheque_bonus'] = $parceiro->dado->tipo_procedimento === TipoProcedimento::CHEQUE_BONUS;
        $this->retorno['procedimento_declaracao'] = $parceiro->dado->tipo_procedimento === TipoProcedimento::DECLARACAO;
        $this->retorno['procedimento_voucher'] = $parceiro->dado->tipo_procedimento === TipoProcedimento::VOUCHER;
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function buscarModelo(): void
    {
        $modelo = $this
            ->validar(
                'Ops! Não conseguimos encontrar o modelo',
                'Página não encontrada',
                404
            )
            ->json([
                'pagina'    => 1,
                'parceiro'  => $this->parceiro,
                'modelo'    => $this->modelo,
                'publicado' => Botao::SIM
            ])
            ->get('/automovel-modelo')
            ->object();

        if (empty($modelo->dado->lista)) {
            mensagemErro(
                'Página não encontrada',
                'Ops! Não conseguimos encontrar as versões',
                404
            );
        }

        $this->retorno['id'] = $modelo->dado->lista[0]->id;
        $this->retorno['titulo'] = $modelo->dado->lista[0]->titulo;
        $this->retorno['imagem'] = $modelo->dado->lista[0]->imagem;
    }

    /**
     * @return void
     * @throws Excecao
     */
    public function buscarVersoes(): void
    {
        $versoes = $this
            ->validar(
                'Ops! Não conseguimos encontrar as versões',
                'Página não encontrada',
                404
            )
            ->json([
                'pagina'   => 1,
                'parceiro' => $this->parceiro,
                'modelo'   => $this->modelo,
                'status'   => Status::ATIVO
            ])
            ->get('/automovel-versao')
            ->object();

        if (empty($versoes)) {
            mensagemErro(
                'Versões não encontradas!!',
                'Ops! Não conseguimos encontrar nenhuma versão deste modelo',
                404
            );
        }

        $this->retorno['versao'] = $this->montarVersao(
            $versoes->dado->lista,
            $this->retorno['imagem']
        );
    }

    /**
     * @param $versoes
     * @param $imagem
     *
     * @return array
     */
    private function montarVersao($versoes, $imagem): array
    {
        if (empty($versoes)) {
            return $versoes;
        }

        $retorno = [];
        foreach ($versoes as $versao) {
            $retorno[] = (object)[
                'id'         => $versao->id,
                'titulo'     => $versao->titulo,
                'valor_de'   => (new Dinheiro($versao->valor_de))->dinheiro(),
                'valor_por'  => (new Dinheiro($versao->valor_por))->dinheiro(),
                'cor'        => $versao->cor,
                'imagem'     => $imagem,
                'imagem_url' => $versao->imagem ?? $imagem,
                'tipo'       => 'automovel-versao'
            ];
        }
        return $retorno;
    }

    /**
     * @return stdClass
     */
    public function buscarDados(): stdClass
    {
        $Texto = new MarkdownHelper();
        $this->retorno['texto_procedimento'] = $Texto->texto($this->retorno['texto_procedimento']);
        return object($this->retorno);
    }
}
