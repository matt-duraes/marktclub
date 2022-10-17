<?php

namespace Painel\Demanda\Models;

use ORM\ORM;

final class ArquivoModel extends ORM
{
    protected string $_tabela = TABELA_DEMANDA_ARQUIVO;

    /**
     * Pega a lista de arquivos da tarefa pelo ID
     *
     * @param   int     $idTarefa  ID da tarefa
     * @return  array   Array com a lista de arquivos
     */
    public function pegarArquivosDaTarefa(int $idTarefa): array
    {
        $dado = $this
            ->campo(['uuid', 'nome', 'arquivo', 'tipo', 'extensao'])
            ->where(['id_demanda_tarefa', $idTarefa])
            ->order('id', 'DESC')
            ->read();
        return $this->montarArquivo($dado);
    }

    private function montarArquivo($dado)
    {
        if (!$dado) {
            return [];
        }
        $lista = [];
        foreach ($dado as $r) {
            $lista[] = (object) [
                'id' => $r->uuid,
                'nome' => $r->nome,
                'extensao' => strCaixaAlta($r->extensao),
                'tipo' => $r->tipo,
                'link' => LINK_PRIVADO . '/demanda/' . $r->arquivo,
            ];
        }
        return $lista;
    }
}
