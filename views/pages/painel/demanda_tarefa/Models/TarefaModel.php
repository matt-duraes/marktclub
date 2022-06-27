<?php

namespace Painel\Demanda\Models;

use ORM\ORM;
use Helpers\TextoHelper;

final class TarefaModel extends ORM
{
    protected string $_tabela = TABELA_DEMANDA_TAREFA;

    public function backlog()
    {
        return $this->buscarLista(1);
    }

    public function toDo()
    {
        return $this->buscarLista(2);
    }

    public function progresso()
    {
        return $this->buscarLista(3);
    }

    public function review()
    {
        return $this->buscarLista(4);
    }

    public function teste()
    {
        return $this->buscarLista(5);
    }

    public function deploy()
    {
        return $this->buscarLista(6);
    }

    private function buscarLista(int $status)
    {
        $lista = $this
            ->campo([
                'uuid', 'titulo', 'texto', 'id_usuario_dono', 'id_usuario_dev', 'tipo', 'prioridade', 'url'
            ])->where(['status', $status])
            ->tabela(TABELA_ADMIN_EMPRESA)->join('id', 'id_admin_empresa')->campo(['imagem_arquivo'])
            ->read();

        return $this->montarLista($lista);
    }

    private function montarLista($dado): array
    {
        if (!$dado) {
            return [];
        }

        $tipoLista = [
            1 => 'bug'
        ];
        $prioridadeLista = [
            1 => 'alta',
            2 => 'media',
            3 => 'baixa',
        ];

        $Texto = new TextoHelper();
        $lista = [];
        foreach ($dado as $r) {
            $lista[] = (object)[
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'texto' => $Texto->valor($r->texto)->removerHtml()->cortar(80)->r(),
                'url' => $r->url,
                'prioridade' => $prioridadeLista[$r->prioridade] ?? '',
                'tipo' => $tipoLista[$r->tipo] ?? ''
            ];
        }
        return $lista;
    }
}
