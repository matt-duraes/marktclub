<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Http\Request;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaTarefa\Tipo;

final class CriarEventoModel
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
        $estutura = $this->pegarEstruturaOferecida();
        $texto = $this->request->getPost('texto', html: false);
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <p><strong>Data do evento</strong></p>
                <ul>
                    <li>Data de início: $dado->data_inicio</li>
                    <li>Data de fim: $dado->data_fim</li>
                </ul>

                <p><strong>Dados do evento</strong></p>
                <ul>
                    <li>Metragem: $dado->metragem</li>
                    <li>Quantidade de participantes: $dado->participantes_quantidade</li>
                    <li>Público esperado: $dado->publico_esperado</li>
                    <li>Quantiade de parceiros: $dado->parceiros_quantidade</li>
                    <li>O que é esperado da empresa: $dado->esperado_empresa</li>
                    <li>Quais materiais levar: $dado->materiais</li>
                </ul>

                <p><strong>Dados do responsável</strong></p>
                <ul>
                    <li>Nome: $dado->resposavel_nome</li>
                    <li>Email: $dado->responsavel_email</li>
                    <li>Telefone: $dado->resposavel_telefone</li>
                </ul>

                $estutura

                <p><strong>Outros dados:</strong></p>
                $texto
            HTML,
            equipe: '',
            tempo: 20
        );
    }

    private function pegarEstruturaOferecida()
    {
        $itens = [];

        if ($this->request->cobertura) {
            $itens[] = 'Cobertura';
        }
        if ($this->request->wifi) {
            $itens[] = 'Wifi';
        }
        if ($this->request->energia) {
            $itens[] = 'Energia';
        }
        if ($this->request->agua) {
            $itens[] = 'Água';
        }
        if ($this->request->alimentacao) {
            $itens[] = 'Alimentação';
        }
        if ($this->request->mesaCadeira) {
            $itens[] = 'Mesa/Cadeira';
        }
        if ($this->request->outros) {
            $itens[] = $this->request->outros;
        }

        if (!empty($itens)) {
            $estrutura = '<p><strong>Estrutura oferecida</strong></p><ul><li>' . implode('</li><li>', $itens) . '</li></ul>';
            return $estrutura;
        }

        return '';
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
