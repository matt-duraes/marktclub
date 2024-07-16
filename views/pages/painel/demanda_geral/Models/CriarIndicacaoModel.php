<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Http\Request;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaTarefa\Tipo;

final class CriarIndicacaoModel
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
                <p><strong>Dados de quem indicou</strong></p>
                <ul>
                    <li>Nome: $dado->usuario_nome</li>
                    <li>Email: $dado->usuario_email</li>
                    <li>CPF: $dado->usuario_cpf</li>
                    <li>Telefone: $dado->usuario_telefone</li>
                </ul>

                <p><strong>Dados da empresa indicada</strong></p>
                <ul>
                    <li>Nome: $dado->empresa_indicada_nome</li>
                    <li>Email: $dado->empresa_email</li>
                    <li>Telefone: $dado->empresa_telefone</li>
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
        if ($dado->empresa_loja_fisica) {
            return <<<HTML
                <p><strong>Endereço da empresa</strong></p>
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
