<?php

namespace App\Models\Site\Automovel;

use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;
use Erro\Excecao;
use Modules\Botao;
use stdClass;

final class ListarModel extends ClubeApiHelper implements
    ListarInterface
{
    public string $tipo = 'modelo';

    public function __construct(
        protected ?string $url = null
    ) {
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $modelos = $this
            ->validar(
                'Ops! Não conseguimos encontrar os modelos',
                'Página não encontrada',
                404
            )
            ->json([
                'pagina'    => 1,
                'parceiro'  => $this->url,
                'publicado' => Botao::SIM
            ])
            ->get('/automovel-modelo')
            ->object()->dado;

        return (object)[
            'tipo'      => 'automovel-modelo',
            'lista'     => $this->montarModelo($modelos->lista),
            'paginacao' => $modelos->pagina ?? 1
        ];
    }

    /**
     * @param array $modelos Array de Modelos
     *
     * @return array
     */
    private function montarModelo(array $modelos): array
    {
        if (empty($modelos)) {
            return $modelos;
        }

        $retorno = [];
        foreach ($modelos as $modelo) {
            $retorno[] = (object)[
                'id'     => $modelo->id,
                'titulo' => $modelo->titulo,
                'texto'  => '',
                'link'   => route('automovel.versao') . '/' . $this->url . '/' . $modelo->url,
                'imagem' => $modelo->imagem,
                'tipo'   => 'automovel-modelo'
            ];
        }
        return $retorno;
    }
}
