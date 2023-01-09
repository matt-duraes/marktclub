<?php

namespace PainelModel\Upload;

use Http\Request;

final class Helper
{
    public function __construct(
        private Request $request
    ) {
    }

    public function moverArquivo()
    {
        $this->validarRequest();
        $this->criarDiretorio();
    }

    private function validarRequest()
    {
        $request = $this->request;
        if (empty($request->grupo_destino)) {
            mensagemErro('Erro!', 'Você deve escolher um diretório para mover ou criar uma nova pasta.');
        } else if ($request->grupo_destino == $request->grupo_atual && empty($request->nome)) {
            mensagemErro(
                'Erro!',
                '
                    Você não pode mover os arquivos para o mesmo diretório que eles estão no momento,
                    escolha um novo diretório ou crie uma nova pasta no diretório escolhido.
                '
            );
        }
    }

    private function criarDiretorio()
    {
        $request = $this->request;
        if (empty($request->nome)) {
            return;
        }

        // foreach ($request->id as $id) {
        //     ppe($this
        //         ->Api
        //         ->body([
        //             'grupo_atual' => $request->grupo_atual,
        //             'grupo_destino' => $request->grupo_destino,
        //             'arquivo' => $id,
        //             'diretorio' => $request->nome
        //         ])
        //         ->post('/upload-arquivo/mover')
        //         ->object());
        // }
    }
}
