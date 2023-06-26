<?php

namespace App\Models\Site\Pesquisa;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;
use Http\Response;

final class SalvarModel
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
        $api = new ApiHelper('enquete_satisfacao:salvar');

        $arraySistema = explode(',', $this->sistema);

        $sistema = [];
        if ($arraySistema ?? false) {
            foreach ($arraySistema as $r) {
                array_push($sistema, $r);
            }
        }

        $api->body([
                'navegar' => $this->navegar,
                'procura' => $this->procura,
                'suporte' => $this->suporte,
                'comentario' => $this->comentario,
                'atendimento' => $this->atendimento,
                'sistemas' => json_encode($sistema)
            ])->post('/enquete/satisfacao')
            ->object();

        return mensagemSucesso([], 201);
    }



}
