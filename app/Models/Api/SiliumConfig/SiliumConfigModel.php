<?php

namespace App\Models\Api\SiliumConfig;

use App\Classes\SiliumConfig\Ordem;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SiliumConfigModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SILIUM_CONFIG;

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem()
    ) {
        $this->validarRequest();
        $this->validarEmpresa();
        parent::__construct();
    }

    private function validarRequest(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
    }

    public function listarDados(): stdClass
    {
        $configuracoes = $this->campo([
            'uuid', 'desconto', 'regra_conversao', 'pontuacao_minima_resgate',
            'validade_pontuacao', 'data_criacao', 'data_atualizacao'
        ])
        ->pagina($this->pegarPagina(), $this->pegarQuantidade())
        ->order($this->pegarOrdem(new Ordem()))
        ->tabela(TABELA_COMERCIAL_EMPRESA)
        ->join('id', 'id_admin_empresa')
        ->campo([
            'uuid', 'nome_fantasia'
        ], 'empresa')
        ->read();
        $configuracoes->lista = $this->montarRetorno($configuracoes->lista);
        return $configuracoes;
    }

    private function montarRetorno(array $configuracoes): array
    {
        if (empty($configuracoes)) {
            return $configuracoes;
        }

        $retorno = [];
        foreach ($configuracoes as $config) {
            $retorno[] = [
                'id'                       => $config->uuid,
                'empresa'                  => [
                    'id'   => $config->empresa_uuid,
                    'nome' => $config->empresa_nome_fantasia
                ],
                'desconto'                 => (new Botao($config->desconto))->valor(),
                'regra_conversao'          => jsonDecode($config->regra_conversao, true, true),
                'pontuacao_minima_resgate' => jsonDecode($config->pontuacao_minima_resgate, true, true),
                'validade_pontuacao'       => $config->validade_pontuacao,
                'data_criacao'             => $config->data_criacao,
                'data_atualizacao'         => $config->data_atualizacao
            ];
        }
        return $retorno;
    }
}
