<?php

namespace App\Models\Api\LoginPainel;

use stdClass;
use App\Models\Api\ApiApp\Trait\AppParaTokenTrait;
use App\Models\Api\LoginPainel\Trait\MensagemTrait;

final class AppPainelModel
{
    use MensagemTrait;
    use AppParaTokenTrait;

    private string $idApp;
    public stdClass $App;

    public function __construct()
    {
        $this->pegarIdAppPainel();
        $this->validarAppPainel();
    }

    private function pegarIdAppPainel()
    {
        $id = env('API_PAINEL_ID', '');
        if (empty($id)) {
            $this->erroLogin('Não foi setado o ID do APP do painel.');
        }
        $this->idApp = $id;
    }

    private function validarAppPainel()
    {
        $App = $this->pegarApp([
            ['uuid', $this->idApp],
            ['status', 1]
        ]);
        if (existeErro($App, 'id')) {
            $this->erroLogin('Não foi encontrado um APP pelo ID: ' . $this->idApp);
        }
        $this->App = $App;
    }
}
