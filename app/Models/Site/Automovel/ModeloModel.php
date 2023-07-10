<?php

namespace App\Models\Site\Automovel;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class ModeloModel extends ApiHelper implements ListarInterface
{
    public function __construct(
        protected ?string $url = null
    ) {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {

        $apiHelper = new ApiHelper('automovel:listar');

        $dado = $apiHelper->json([
                    'pagina' => 1,
                    'url' => $this->url
                ])->get('/automovel')->object();

        if(!property_exists($dado, 'dado')) {
            mensagemStatus(404);
        }
        return $this->montarRetorno($dado->dado);
    }

    private function montarRetorno($dado): stdClass
    {
        $retorno = new stdClass();
        $retorno->tipo = 'veiculo';
        $retorno->dado = new stdClass();
        $retorno->lista = new stdClass();

        if (!empty($dado->lista) && count($dado->lista) > 0) {
            foreach ($dado->lista as $key => $valor) {
                $retorno->dado = $this->criarItem($valor);
            }
            foreach ($dado->lista[0]->versao as $key => $valor) {
                $retorno->lista->$key = $this->criarVersao($valor, $dado);
            }
        }

        return $retorno;
    }

    private function criarVersao($valor, $dado): stdClass
    {
        return (object) [
            'id' => $valor->id,
            'titulo' => $valor->titulo,
            'link' => '',
            'imagem' => $dado->lista[0]->imagem,
            'de' => $valor->valor->de,
            'por' => $valor->valor->por
        ];
    }

    private function criarItem($valor): stdClass
    {
        return (object) [
            'id' => $valor->id,
            'titulo' => $valor->titulo,
            'procedimento' => $valor->procedimento,
            'desconto' => $valor->desconto,
            'endereco' => $valor->endereco
        ];
    }
}
