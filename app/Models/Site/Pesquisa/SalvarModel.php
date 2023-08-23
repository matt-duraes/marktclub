<?php

namespace App\Models\Site\Pesquisa;

use Erro\Excecao;
use Http\Request;
use App\Helpers\ClubeApiHelper;

final class SalvarModel extends ClubeApiHelper
{
    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->salvarPesquisa($this->request);
    }

    /**
     * @return object|array
     * @throws Excecao
     */
    public function salvarPesquisa($request): object
    {
        $sistema = implode(',', $request->sistema);
        $this
            ->body([
                'navegar'     => $request->navegar,
                'procura'     => $request->procura,
                'suporte'     => $request->suporte,
                'comentario'  => $request->comentario,
                'atendimento' => $request->atendimento,
                'sistemas'    => $sistema
            ])->post('/enquete/satisfacao')
            ->object();

        return mensagemSucesso([], 201);
    }
}
