<?php

namespace ApiModel\PainelNotificacao;

use Erro\Excecao;
use ORM\ORM;
use Throwable;

final class VisualizarTodasModel extends ORM
{
    protected string $ormTabela = TABELA_PAINEL_NOTIFICACAO;
    private int $idUsuario;
    private array $lista;

    public function __construct()
    {
        parent::__construct();
        try {
            $this->idUsuario = TOKEN['usuario']->id;
        } catch (Throwable) {
            mensagemStatus(404);
        }
    }

    public function visualizarTodas()
    {
        $this->pegarLista();
        $this->atualizarStatus();
    }

    /**
     * @throws Excecao
     */
    private function pegarLista()
    {
        $this->lista = $this
            ->campo(['id'])
            ->where([
                ['id_usuario_equipe', $this->idUsuario],
                ['status', 'in', [1, 2]]
            ])->read();
    }

    /**
     * @throws Excecao
     */
    private function atualizarStatus()
    {
        foreach ($this->lista as $r) {
            $update = $this->dado(['status' => 3])->where(['id', $r->id])->update();
            if (existeErro($update, 'id')) {
                mensagemErro('Erro!', 'Ocorreu um erro para atualizar uma ou mais notificações');
            }
        }
    }
}
