<?php

namespace App\Models\Site\Turismo;

use stdClass;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\ListaHelper;

final class SolicitaHotelModel
{
    protected string $cidade;
    protected string $checkin;
    protected string $checkout;
    protected string $quantidade_quarto;
    protected array|int $adulto;
    protected array|int $crianca;

    public function __construct(
        private Request $request
    ) {

        $this->cidade = $request->cidade;
        $this->checkin = $request->checkin;
        $this->checkout = $request->checkout;
        $this->quantidade_quarto = $request->quantidade_quarto;
        $this->adulto = !empty($request->adulto) ? $request->adulto : 0;
        $this->crianca = !empty($request->crianca) ? $request->crianca : 0;

    }
    public function postDado()
    {

        $Api = new ApiHelper('turismo:buscar');
        $dado = [
            'cidade' => $this->cidade,
            'checkin' => $this->checkin,
            'checkout' => $this->checkout,
            'quantidade_quarto' => $this->quantidade_quarto,
        ];
        if (!empty($this->adulto)) {
            foreach($this->adulto as $key => $valor):
                $dado['adulto'][$key] = $valor;
            endforeach;
        }
        if (!empty($this->crianca)) {
            foreach($this->crianca as $key => $valor):
                $dado['crianca'][$key] = $valor;
            endforeach;
        }

        $dado = $Api->body($dado)->post('/turismo/solicitar-hotel')->object();

        if (existeErro($dado, 'dado')) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao solicitar seu hotel',
            );
        }
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {

        return $dado->dado->link ?? '';
    }



}
