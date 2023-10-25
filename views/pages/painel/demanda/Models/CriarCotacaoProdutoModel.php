<?php

namespace Painel\Demanda\Models;

use App\Classes\DemandaTarefa\Tipo;
use Http\Request;
use stdClass;
use App\Classes\DemandaDado\Area;

final class CriarCotacaoProdutoModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private Request $request
    ) {
        $this->empresa = $request->empresa;

        $this->criarDemanda($this->montarTitulo(), $request->tipo, Area::CONVENIO);
        $this->verificarSeSalvouDemanda();
        $this->adicionarTarefa();
    }

    private function montarTitulo()
    {
        return $this->request->empresa_nome . $this->request->titulo;
    }

    private function adicionarTarefa()
    {
        $dado = $this->request;
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <h1>Dados do solicitante</h1>
                <ul>
                    <li>CPF: $dado->cpf</li>
                    <li>E-mail: $dado->email</li>
                    <li>Telefone: $dado->telefone</li>
                </ul>

                <h1>Dados do produto</h1>
                <ul>
                    <li>Tipo: $dado->tipo</li>
                    <li>Marca: $dado->marca</li>
                    <li>Modelo: $dado->modelo</li>
                    <li>Extra: $dado->extra</li>
                </ul>

                <h1>Observações</h1>
                <ul>
                    <li>$dado->observacao</li>
                </ul>
            HTML,
            equipe: '',
            tempo: 20
        );
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
