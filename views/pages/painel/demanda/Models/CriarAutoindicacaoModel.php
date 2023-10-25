<?php

namespace Painel\Demanda\Models;

use App\Classes\DemandaTarefa\Tipo;
use Http\Request;
use stdClass;
use App\Classes\DemandaDado\Area;

final class CriarAutoindicacaoModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private Request $request
    ) {
        if (empty($request->empresa)) {
            $this->empresa = '14afa776394ada4be23be6acf7e3259e';
        }

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
        $endereco = $this->pegarEndereco();
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <h1>Dados da empresa indicada</h1>
                <ul>
                    <li>Nome: $dado->empresa_indicada_nome</li>
                    <li>Ramo: $dado->ramo</li>
                </ul>

                <h1>Formas de contato</h1>
                <ul>
                    <li>Email: $dado->email</li>
                    <li>Telefone: $dado->telefone</li>
                </ul>

                $endereco

                <h1>Observações</h1>
                <ul>
                    <li>$dado->observacao</li>
                </ul>
            HTML,
            equipe: '',
            tempo: 20
        );
    }

    private function pegarEndereco()
    {
        $dado = $this->request;
        if ($dado->loja_fisica) {
            return <<<HTML
                <h1>Endereço da empresa</h1>
                <ul>
                    <li>CEP: $dado->cep</li>
                    <li>Logradouro: $dado->logradouro</li>
                    <li>Número: $dado->numero</li>
                    <li>Complemento: $dado->complemento</li>
                    <li>Bairro: $dado->bairro</li>
                    <li>Cidade: $dado->cidade</li>
                    <li>Estado: $dado->estado</li>
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
