<?php

namespace App\Models\Site\Loja;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class RelacionadoModel extends ApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }

    public function favoritar(string $uuid = null, string $acao = null): stdClass
    {
        $retorno = $this->listarDados();

        if ($uuid !== null) {
            foreach ($retorno->lista as $item) {
                if ($item->id === $uuid) {
                    $item->favorito = $acao ?? '0';
                    return $item;
                }
            }
        }

    }

}
