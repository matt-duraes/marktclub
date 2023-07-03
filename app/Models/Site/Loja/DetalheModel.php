<?php

namespace App\Models\Site\Loja;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class DetalheModel extends ApiHelper implements ListarInterface
{
    use MontarRetornoDetalheTrait;

    private string $url;

    public function __construct($url)
    {
        parent::__construct(scope: '');
        $this->url = $url;
    }

    public function listarDados(): stdClass
    {
        if ($this->url == 'loja') {
            return $this->montarRetorno();
        } elseif ($this->url == 'sala-vip-do-aeroporto-internacional-de-brasilia-presidente-juscelino-kubitschek-1') {
            return $this->montarSalaVip();
        }
    }
}
