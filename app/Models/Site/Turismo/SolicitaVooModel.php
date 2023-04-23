<?php

namespace App\Models\Site\Turismo;

use stdClass;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\ListaHelper;

final class SolicitaVooModel
{
    protected string $tipo;
    protected string $destino;
    protected string $origem;
    protected string $data_ida;
    protected string $data_volta;
    protected array|int $adulto;
    protected array|int $crianca;
    protected array|int $bebe;

    public function __construct(
        private Request $request
    ) {

        $this->tipo = $request->tipo;
        $this->destino = $request->destino;
        $this->origem = $request->origem;
        $this->data_ida = $request->data_ida;
        $this->data_volta = $request->data_volta;
        $this->adulto = !empty($request->adulto) ? $request->adulto : 0;
        $this->crianca = !empty($request->crianca) ? $request->crianca : 0;
        $this->bebe = !empty($request->bebe) ? $request->bebe : 0;

    }
    public function postDado()
    {

        $Api = new ApiHelper('turismo:buscar');
        $dado = $Api->body([
            'tipo' => $this->tipo,
            'destino' => $this->destino,
            'origem' => $this->origem,
            'data_ida' => $this->data_ida,
            'data_volta' => $this->data_volta,
            'adulto' => $this->adulto,
            'crianca' => $this->crianca,
            'bebe' => $this->bebe
        ])->post('/turismo/solicitar-voo')->object();

        if (existeErro($dado, 'dado')) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao solicitar seu voo',
            );
        }
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {

        return $dado->dado->link ?? '';
    }



}
