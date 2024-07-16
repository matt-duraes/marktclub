<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaTarefa\Tipo;

final class CriarAssociacaoModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private string $empresaNome,
        private string $empresa,
        private string $texto
    ) {
        $this->criarDemanda($empresaNome . 'Novo site para associação', $texto, 'novo-associacao', Area::TECNOLOGIA);
        $this->verificarSeSalvouDemanda();
        $this->criarTarefaPadrao();
    }

    private function criarTarefaPadrao()
    {
        $this->salvarTarefa(
            tipo: Tipo::BACKEND,
            titulo: 'Criar arquivo padrão de filie-se',
            texto: <<<HTML
                <p>Criar arquivo padrão do filie-se</p>
            HTML,
            dificuldade: 1
        );
        $this->salvarTarefa(
            tipo: Tipo::BACKEND,
            titulo: 'Criar arquivo padrão de contato',
            texto: <<<HTML
                <p>Criar arquivo padrão de contato</p>
            HTML,
            dificuldade: 1
        );
        $this->salvarTarefa(
            tipo: Tipo::BACKEND,
            titulo: 'Criar arquivo padrão do menu',
            texto: <<<HTML
                <p>Criar arquivo padrão do menu do site</p>
            HTML,
            dificuldade: 1
        );
        $this->salvarTarefa(
            tipo: Tipo::BACKEND,
            titulo: 'Criar arquivo padrão do menu da área do associado',
            texto: <<<HTML
                <p>Criar arquivo padrão do menu da área do associado</p>
            HTML,
            dificuldade: 1
        );
        $this->salvarTarefa(
            tipo: Tipo::BANCO,
            titulo: 'Migrar banco de dados',
            texto: <<<HTML
                <p>Migrar banco de dados</p>
            HTML,
            dificuldade: 4
        );
        $this->salvarTarefa(
            tipo: Tipo::INFRA,
            titulo: 'Configurar ambiente de homologação',
            texto: <<<HTML
                <p>Configurar ambiente de homologação</p>
            HTML,
            dificuldade: 3
        );
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
