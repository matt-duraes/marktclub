<?php

namespace Painel\Demanda\Models;

use stdClass;
use Http\Request;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaTarefa\Tipo;

final class CriarAuditoriaModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;
    private string $empresa;

    public function __construct(
        private Request $request
    ) {
        $this->empresa = $request->empresa;
        $this->criarDemanda($this->montarTitulo(), $request->texto, $request->tipo, Area::CONVENIO);
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
        $dadosLoja = $this->pegarDadosLoja();
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <h1>Relatório do problema</h1>
                <p>
                    $dado->relatorio
                </p>

                <h1>Formas de contato</h1>
                <ul>
                    <li>Email: $dado->email</li>
                    <li>Telefone: $dado->telefone</li>
                </ul>

                $dadosLoja

                <h1>Observações</h1>
                <ul>
                    <li>$dado->observacao</li>
                </ul>
            HTML,
            equipe: '',
            tempo: 20
        );
    }

    private function pegarDadosLoja()
    {
        $dado = $this->request;
        if ($dado->loja_fisica) {
            return <<<HTML
                <h1>Dados da loja</h1>
                <ul>
                    <li>Unidade: $dado->unidade</li>
                    <li>Atendente: $dado->atendente</li>
                    <li>Gerente: $dado->gerente</li>
                </ul>
            HTML;
        }
        return '';
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
