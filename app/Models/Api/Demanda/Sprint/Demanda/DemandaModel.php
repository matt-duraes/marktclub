<?php

namespace App\Models\Api\Demanda\Sprint\Demanda;

use ORM\ORM;
use Helpers\OrmHelper;
use App\Classes\Demanda\Sprint\Status;

abstract class DemandaModel extends ORM
{
    protected string $ormTabela = TABELA_DEMANDA_SPRINT;
    protected string $sprint;
    protected string $demanda;
    protected string $texto;
    protected array $demandaId = [];
    protected array $demandaRetirada = [];
    protected array $demandaAdicionada = [];
    protected string $demandaTitulo = '';
    protected string $demandaStatus = '';
    protected int $id;

    public function __construct()
    {
        parent::__construct();
        $this->pegarSprint();
        $this->pegarDemanda();
    }

    protected function pegarSprint()
    {
        $sprint = $this
            ->campo([
                'id', 'id_demanda', 'id_demanda_retirada', 'id_demanda_adicionada', 'status'
            ])
            ->where([
                ['uuid', $this->sprint],
                ['status', 'in', Status::PUBLICADO]
            ])
            ->primeiro();

        if (!chaveExiste('id', $sprint)) {
            mensagemErro('Erro!', 'Não existe sprint ativa com o id enviado.');
        }

        $this->id = $sprint->id;
        $this->demandaId = jsonDecode($sprint->id_demanda, true, true);
        $this->demandaRetirada = jsonDecode($sprint->id_demanda_retirada, true, true);
        $this->demandaAdicionada = jsonDecode($sprint->id_demanda_adicionada, true, true);
        $this->demandaStatus = (new Status($sprint->status))->indice();
    }

    protected function pegarDemanda()
    {
        $demanda = (new OrmHelper(TABELA_DEMANDA_DADO))
            ->campo(['id', 'titulo', 'status'])
            ->where(['uuid', $this->demanda])
            ->primeiro();

        if (!chaveExiste('id', $demanda)) {
            mensagemErro('Erro!', 'Não existe sprint ativa com o id enviado.');
        }
        $this->demandaTitulo = $demanda->titulo;
    }

    protected function adicionarMensagem(array $lista)
    {
        if (!array_key_exists($this->demanda, $lista)) {
            $lista[$this->demanda] = [
                'id'       => $this->demanda,
                'titulo'   => $this->demandaTitulo,
                'mensagem' => []
            ];
        }
        $lista[$this->demanda]['titulo'] = $this->demandaTitulo;
        if (!empty($this->texto)) {
            $lista[$this->demanda]['mensagem'][] = $this->texto;
        }
        return $lista;
    }
}
