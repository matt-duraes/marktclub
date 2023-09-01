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

    private function salvarPesquisa($request): void
    {
        $this
            ->validar('Erro ao salvar a pesquisa, por favor, tente novamente.', login: true)
            ->body([
                'navegar'           => $request->navegar,
                'procura'           => $request->procura,
                'suporte'           => $request->suporte,
                'comentario'        => $request->comentario,
                'atendimento'       => $request->atendimento,
                'sistemas_clube'    => jsonEncode($request->sistema)
            ])->post('/enquete-satisfacao');
    }
}
