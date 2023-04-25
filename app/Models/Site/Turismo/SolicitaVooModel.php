<?php

namespace App\Models\Site\Turismo;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;

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
        $this->tipo = $this->request->tipo;
        $this->destino = $this->request->destino;
        $this->origem = $this->request->origem;
        $this->data_ida = $this->request->data_ida;
        $this->data_volta = $this->request->data_volta;
        $this->adulto = !empty($this->request->adulto) ? $this->request->adulto : 0;
        $this->crianca = !empty($this->request->crianca) ? $this->request->crianca : 0;
        $this->bebe = !empty($this->request->bebe) ? $this->request->bebe : 0;
    }

    /**
     * @return string
     * @throws Excecao
     */
    public function postDado(): string
    {
        $Api = new ApiHelper('turismo:buscar');
        $dado = $Api->body([
            'tipo'       => $this->tipo,
            'destino'    => $this->destino,
            'origem'     => $this->origem,
            'data_ida'   => $this->data_ida,
            'data_volta' => $this->data_volta,
            'adulto'     => $this->adulto,
            'crianca'    => $this->crianca,
            'bebe'       => $this->bebe
        ])->post('/turismo/solicitar-voo')->object();

        if (existeErro($dado, 'dado')) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao solicitar seu voo',
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
