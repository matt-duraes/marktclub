<?php

namespace App\Models\Api\ChatbotCategoria;

use App\Classes\Geral\Status;
use App\Classes\ChatbotPerguntas\Ordem;
use App\Models\Site\ListarInterface;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CategoriaModel extends ORM implements ListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_CHATBOT_CATEGORIA;

    public function __construct(
        private readonly Pagina $pagina,
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'categoria', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista) ?? [];

        return $dado;
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        $status = new Status();

        foreach ($lista as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'categoria' => $r->categoria,
                'status' => $status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
