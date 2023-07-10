<?php

namespace App\Models\Site\Pesquisa;

use Erro\Excecao;
use Http\Request;
use App\Helpers\ClubeApiHelper;

final class SalvarModel extends ClubeApiHelper
{
    protected string $navegar;
    protected string $procura;
    protected string $suporte;
    protected string $comentario;
    protected string $atendimento;
    protected string $sistema;

    /**
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        $this->navegar = $request->navegar;
        $this->procura = $request->procura;
        $this->suporte = $request->suporte;
        $this->comentario = $request->comentario;
        $this->atendimento = $request->atendimento;
        $this->sistema = $request->sistema;
    }

    /**
     * @return object|array
     * @throws Excecao
     */
    public function postSalvar(): object
    {
        $arraySistema = explode(',', $this->sistema);

        $sistema = [];
        foreach ($arraySistema as $r) {
            array_push($sistema, $r);
        }

        $this
            ->body([
                'navegar'     => $this->navegar,
                'procura'     => $this->procura,
                'suporte'     => $this->suporte,
                'comentario'  => $this->comentario,
                'atendimento' => $this->atendimento,
                'sistemas'    => json_encode($sistema)
            ])->post('/enquete/satisfacao')
            ->object();

        return mensagemSucesso([], 201);
    }
}
