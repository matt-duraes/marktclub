<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Http\Request;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaTarefa\Tipo;

final class CriarAutoindicacaoModel
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
        $endereco = $this->pegarEndereco();
        $texto = $this->request->getPost('texto', html: false);
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <p><strong>Dados da empresa indicada</strong></p>
                <ul>
                    <li>Nome: $dado->empresa_indicada_nome</li>
                    <li>Ramo: $dado->ramo</li>
                </ul>

                <p><strong>Formas de contato</strong></p>
                <ul>
                    <li>Email: $dado->email</li>
                    <li>Telefone: $dado->telefone</li>
                </ul>

                $endereco
                <p><strong>Outros dados:</strong></p>
                $texto
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
                <p><strong>Endereço da empresa</strong></p>
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
