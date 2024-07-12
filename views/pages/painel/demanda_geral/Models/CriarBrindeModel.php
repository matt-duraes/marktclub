<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Http\Request;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaTarefa\Tipo;

final class CriarBrindeModel
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
        $canaisDivulgacao = $this->pegarCanaisDivulgacao();
        $texto = $this->request->getPost('texto', html: false);
        $this->salvarTarefa(
            Tipo::CONVENIO,
            $dado->titulo,
            texto: <<<HTML
                <p><strong>Dados da divulgação</strong></p>
                <ul>
                    <li>Data de início: $dado->inicio_divulgacao</li>
                    <li>Data final: $dado->fim_divulgacao</li>
                    <li>Tema: $dado->tema</li>
                    <li>Segmento: $dado->segmento</li>
                    <li>Quantidade de Participantes: $dado->participantes</li>
                </ul>

                $canaisDivulgacao
                <p><strong>Outros dados:</strong></p>
                $texto
            HTML,
            equipe: '',
            tempo: 20
        );
    }

    private function pegarCanaisDivulgacao()
    {
        $itens = [];

        if ($this->request->instagram) {
            $itens[] = 'Instagram';
        }
        if ($this->request->facebook) {
            $itens[] = 'Facebook';
        }
        if ($this->request->email) {
            $itens[] = 'Email';
        }
        if ($this->request->flyer) {
            $itens[] = 'Flyer';
        }
        if ($this->request->outros) {
            $itens[] = $this->request->outros;
        }

        if (!empty($itens)) {
            $estrutura = '<p><strong>Canais de divulgação</strong></p><ul><li>' . implode('</li><li>', $itens) . '</li></ul>';
            return $estrutura;
        }

        return '';
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
