<?php

namespace App\Models\Api\SiliumConfig;

use Modules\Pagina;
use ORM\ORM;
use stdClass;
use System\Trait\Model\PaginaTrait;

class SiliumConfigModel extends ORM
{
    use PaginaTrait;

    protected string $ormTabela = TABELA_SILIUM_CONFIG;

    public function __construct(
        private readonly Pagina $pagina = new Pagina()
    ) {
        parent::__construct();
    }

    public function listarSelect(): stdClass
    {
        $configs = $this->campo([
            'uuid', 'regra_conversao', 'pontuacao_minima_resgate',
            'validade_pontuacao', 'data_criacao', 'data_atualizacao'
        ])
        ->pagina($this->pegarPagina())
        ->read();
        $configs->lista = $this->montarSelect($configs->lista);
        return $configs;
    }

    private function montarSelect(array $configs): array
    {
        if (empty($configs)) {
            return $configs;
        }

        $retorno = [];
        foreach ($configs as $config) {
            $retorno[] = [
                'id'                       => $config->uuid,
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
