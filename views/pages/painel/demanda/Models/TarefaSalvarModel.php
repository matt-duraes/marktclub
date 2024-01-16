<?php

namespace Painel\Demanda\Models;

use stdClass;
use Helpers\ApiHelper;

final class TarefaSalvarModel
{
    use TarefaTrait;

    private ApiHelper $Api;
    public stdClass $tarefa;

    public function __construct(
        private string $titulo,
        private string $texto,
        private string $tipo,
        private array $tarefa_tipo,
        private ?string $demanda = null,
        private ?string $equipe = null,
        private ?string $id = null,
    ) {
        $this->validar();
        $this->Api = new ApiHelper(token: true);
        if (empty($id)) {
            $this->salvarTarefa();
            return;
        }
        $this->atualizarTarefa();
    }

    private function validar()
    {
        if (empty($this->titulo)) {
            mensagemErro('Campo obrigatório!', 'Digite o texto da tarefa para continuar.');
        } elseif (empty($this->texto)) {
            mensagemErro('Campo obrigatório!', 'Digite o texto da tarefa para continuar.');
        } elseif (empty($this->tipo)) {
            mensagemErro('Campo obrigatório!', 'Escolha o tipo da tarefa para continuar.');
        }
    }

    private function salvarTarefa()
    {
        $dado = $this->Api
            ->validar('Erro ao salvar nova tarefa, por favor, tente novamente.', login: true)
            ->body([
                'demanda' => $this->demanda,
                'titulo'  => $this->titulo,
                'texto'   => $this->texto,
                'tipo'    => $this->tipo,
            ])
            ->post('/demanda-tarefa')
            ->object()->dado ?? [];

        $this->atualizarTarefaDemanda();

        $this->tarefa = $this->montarTarefa([$dado])[0] ?? (object)[];
    }

    private function atualizarTarefa()
    {
        $this->Api
            ->validar('Erro ao atualizar tarefa, por favor, tente novamente.', login: true)
            ->body([
                'titulo'  => $this->titulo,
                'texto'   => $this->texto,
                'tipo'    => $this->tipo
            ])
            ->put('/demanda-tarefa/' . $this->id);
        $this->atualizarTarefaDemanda();
    }

    private function atualizarTarefaDemanda()
    {
        $this->Api
            ->body([
                'tarefa_tipo' => $this->tarefa_tipo
            ])
            ->put('/demanda-dado/' . $this->demanda);
    }
}
