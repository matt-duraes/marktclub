<?php

namespace Painel\Demanda\Models;

use App\Classes\DemandaTarefa\Tipo;
use Http\Request;
use stdClass;
use App\Classes\DemandaDado\Area;

final class CriarIndicacaoModel
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
        $endereco = $this->pegarEndereco();
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <h1>Dados de quem indicou</h1>
                <ul>
                    <li>Nome: $dado->usuario_nome</li>
                    <li>Email: $dado->usuario_email</li>
                    <li>CPF: $dado->usuario_cpf</li>
                    <li>Telefone: $dado->usuario_telefone</li>
                </ul>

                <h1>Dados da empresa indicada</h1>
                <ul>
                    <li>Nome: $dado->empresa_indicada_nome</li>
                    <li>Email: $dado->empresa_email</li>
                    <li>Telefone: $dado->empresa_telefone</li>
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
        if ($dado->empresa_loja_fisica) {
            return <<<HTML
                <h1>Endereço da empresa</h1>
                <ul>
                    <li>CEP: $dado->empresa_cep</li>
                    <li>Logradouro: $dado->empresa_logradouro</li>
                    <li>Número: $dado->empresa_numero</li>
                    <li>Complemento: $dado->empresa_complemento</li>
                    <li>Bairro: $dado->empresa_bairro</li>
                    <li>Cidade: $dado->empresa_cidade</li>
                    <li>Estado: $dado->empresa_estado</li>
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
