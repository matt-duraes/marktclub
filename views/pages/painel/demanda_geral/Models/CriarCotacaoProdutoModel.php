<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Http\Request;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaTarefa\Tipo;

final class CriarCotacaoProdutoModel
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
        $texto = $this->request->getPost('texto', html: false);
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <p><strong>Dados do solicitante</strong></p>
                <ul>
                    <li>Nome: $dado->nome</li>
                    <li>CPF: $dado->cpf</li>
                    <li>E-mail: $dado->email</li>
                    <li>Telefone: $dado->telefone</li>
                </ul>

                <p><strong>Dados do produto</strong></p>
                <ul>
                    <li>Tipo: $dado->produto_tipo</li>
                    <li>Marca: $dado->marca</li>
                    <li>Modelo: $dado->modelo</li>
                    <li>Extra: $dado->extra</li>
                </ul>
                <p><strong>Outros dados:</strong></p>
                $texto
            HTML
        );
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
