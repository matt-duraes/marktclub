<?php

namespace Painel\DemandaGeral\Models;

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
        $this->criarDemanda($this->montarTitulo(), '', $request->tipo, Area::CONVENIO);
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
        $texto = $this->request->getPost('texto', html: false);
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <p><strong>Relatório do problema</strong></p>
                <p>
                    $dado->relatorio
                </p>

                <p><strong>Formas de contato</strong></p>
                <ul>
                    <li>Email: $dado->email</li>
                    <li>Telefone: $dado->telefone</li>
                </ul>
                $dadosLoja
                <p><strong>Outros dados:</strong></p>
                $texto
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
                <p><strong>Dados da loja</strong></p>
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
