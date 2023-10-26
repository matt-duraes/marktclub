<?php

namespace Painel\Demanda\Models;

use App\Classes\DemandaTarefa\Tipo;
use Http\Request;
use stdClass;
use App\Classes\DemandaDado\Area;

final class CriarCampanhaModel
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
                <h1>Dados da divulgação</h1>
                <ul>
                    <li>Data de início: $dado->inicio_divulgacao</li>
                    <li>Data final: $dado->fim_divulgacao</li>
                    <li>Tema: $dado->tema</li>
                    <li>Segmento: $dado->segmento</li>
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
