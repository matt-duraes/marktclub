<?php

namespace Painel\Demanda\Models;

use App\Classes\DemandaTarefa\Tipo;
use Http\Request;
use stdClass;
use App\Classes\DemandaDado\Area;

final class CriarEventoModel
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
        $estutura = $this->pegarEstruturaOferecida();
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <h1>Data do evento</h1>
                <ul>
                    <li>Data de início: $dado->data_inicio</li>
                    <li>Data de fim: $dado->data_fim</li>
                </ul>

                <h1>Dados do evento</h1>
                <ul>
                    <li>Metragem: $dado->metragem</li>
                    <li>Quantidade de participantes: $dado->participantes_quantidade</li>
                    <li>Público esperado: $dado->publico_esperado</li>
                    <li>Quantiade de parceiros: $dado->parceiros_quantidade</li>
                    <li>O que é esperado da empresa: $dado->esperado_empresa</li>
                    <li>Quais materiais levar: $dado->materiais</li>
                </ul>

                <h1>Dados do responsável</h1>
                <ul>
                    <li>Nome: $dado->resposavel_nome</li>
                    <li>Email: $dado->responsavel_email</li>
                    <li>Telefone: $dado->resposavel_telefone</li>
                </ul>

                $estutura

                <h1>Observações</h1>
                <ul>
                    <li>$dado->observacao</li>
                </ul>
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
            $estrutura = '<h1>Estrutura oferecida</h1><ul><li>' . implode('</li><li>', $itens) . '</li></ul>';
            return $estrutura;
        }

        return '';
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
