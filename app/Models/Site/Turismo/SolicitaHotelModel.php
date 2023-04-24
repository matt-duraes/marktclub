<?php

namespace App\Models\Site\Turismo;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;

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
        $this->cidade = $this->request->cidade;
        $this->checkin = $this->request->checkin;
        $this->checkout = $this->request->checkout;
        $this->quantidade_quarto = $this->request->quantidade_quarto;
        $this->adulto = !empty($this->request->adulto) ? $this->request->adulto : 0;
        $this->crianca = !empty($this->request->crianca) ? $this->request->crianca : 0;
    }

    /**
     * @return string
     * @throws Excecao
     */
    public function postDado(): string
    {
        $Api = new ApiHelper('turismo:buscar');
        $dado = [
            'cidade'            => $this->cidade,
            'checkin'           => $this->checkin,
            'checkout'          => $this->checkout,
            'quantidade_quarto' => $this->quantidade_quarto,
        ];
        if (!empty($this->adulto)) {
            foreach ($this->adulto as $key => $valor) {
                $dado['adulto'][$key] = $valor;
            }
        }
        if (!empty($this->crianca)) {
            foreach ($this->crianca as $key => $valor) {
                $dado['crianca'][$key] = $valor;
            }
        }

        $dado = $Api->body($dado)
            ->post('/turismo/solicitar-hotel')
            ->object();

        if (existeErro($dado, 'dado')) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao solicitar seu hotel',
            );
        }
        return $this->montarRetorno($dado);
    }

    /**
     * @param  $dado
     *
     * @return string
     */
    private function montarRetorno($dado): string
    {
        return $dado->dado->link ?? '';
    }
}
