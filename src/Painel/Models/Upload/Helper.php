<?php

namespace PainelModel\Upload;

use stdClass;
use Http\Request;
use Helpers\ApiHelper;

final class Helper
{
    private string $grupo;
    public array $retornoMover = [];

    public function __construct(
        private ?Request $request = null
    ) {
    }

    public function moverArquivo()
    {
        $request = $this->request;
        $this->grupo = $request->grupo_destino;

        $this->validarRequest();
        $grupo = $this->criarDiretorio($request->grupo_destino, $request->nome, false);
        if (is_object($grupo)) {
            $this->retornoMover = [
                'id'   => $grupo->dado->id,
                'nome' => $grupo->dado->nome
            ];
        }
        $this->moverArquivoParaNovoDiretorio();
    }

    private function validarRequest()
    {
        $request = $this->request;
        if (empty($request->grupo_destino)) {
            mensagemErro('Erro!', 'Você deve escolher um diretório para mover ou criar uma nova pasta.');
        } elseif ($request->grupo_destino == $request->grupo_atual && empty($request->nome)) {
            mensagemErro(
                'Erro!',
                '
                    Você não pode mover os arquivos para o mesmo diretório que eles estão no momento,
                    escolha um novo diretório ou crie uma nova pasta no diretório escolhido.
                '
            );
        }
    }

    public function criarDiretorio($grupo, $nome, bool $erro = true): bool|stdClass
    {
        if (empty($nome) && $erro) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um nome para o diretório.');
        } elseif (empty($nome)) {
            return false;
        }

        $grupo = (new ApiHelper(token: true))
            ->validar('Erro ao criar diretório')
            ->body([
                'grupo' => $grupo,
                'nome'  => $nome
            ])
            ->post('/upload-grupo')
            ->object();

        $this->grupo = $grupo->dado->id;
        return $grupo;
    }

    public function moverArquivoParaNovoDiretorio()
    {
        $request = $this->request;
        $Api = new ApiHelper(token: true);
        foreach ($request->id as $id) {
            $Api
                // ->validar('Não foi possível migrar um ou mais arquivos, recarregue a página e tente novamente.')
                ->body([
                    'grupo' => $this->grupo
                ])
                ->put('/upload-arquivo/' . $id);
        }
    }
}
