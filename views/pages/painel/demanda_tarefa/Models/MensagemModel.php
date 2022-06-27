<?php

namespace Painel\Demanda\Models;

use ORM\ORM;
use Helpers\DataHelper;
use Painel\UsuarioEquipe\Models\Helper;

final class MensagemModel extends ORM
{
    protected string $_tabela = TABELA_DEMANDA_MENSAGEM;

    /**
     * Pega a lista de mensagem da tarefa pelo ID
     *
     * @param   int     $idTarefa  ID da tarefa
     * @return  array   Array com a lista de mensagens
     */
    public function pegarMensagensDaTarefa(int $idTarefa): array
    {
        $dado = $this
            ->campo(['uuid', 'id_usuario_equipe', 'texto', 'data_criacao'])
            ->where([
                ['id_demanda_tarefa', $idTarefa]
            ])->order('data_criacao', 'DESC')
            ->tabela(TABELA_USUARIO_EQUIPE)->join('id', 'id_usuario_equipe')
            ->campo(['nome_real', 'imagem_arquivo', 'imagem_facebook', 'imagem_google', 'imagem_tipo'])
            ->read();

        return $this->montarMensagem($dado);
    }

    private function montarMensagem(array $dado)
    {
        if (!$dado) {
            return [];
        }

        $Data = new DataHelper();
        $ImagemHelper = new Imagem;

        $lista = [];
        $equipeUltimo = $dado[0]->id_usuario_equipe;
        $equipeLogado = sessao('USUARIO.id');
        $item = 0;
        $dataLista = [];
        foreach ($dado as $r) {
            $data = $Data->valor($r->data_criacao)->quantosDias();

            if (!in_array($data, $dataLista)) {
                $item++;
                $lista[$item] = (object)[
                    'tipo' => 'data',
                    'data' => $data
                ];
                $item++;
                $dataLista[] = $data;
                if ($equipeUltimo != $r->id_usuario_equipe) {
                    $equipeUltimo = $r->id_usuario_equipe;
                }
            } elseif ($equipeUltimo != $r->id_usuario_equipe) {
                $item++;
                $equipeUltimo = $r->id_usuario_equipe;
            }
            if (!array_key_exists($item, $lista)) {
                $lista[$item] = (object)[
                    'tipo' => $equipeLogado == $r->id_usuario_equipe ? 'minha_mensagem' : 'mensagem',
                    'nome' => $r->nome_real,
                    'imagem' => $ImagemHelper->pegarImagem(
                        $r->imagem_tipo,
                        $r->imagem_arquivo,
                        $r->imagem_google,
                        $r->imagem_facebook
                    ),
                    'lista' => []
                ];
            }

            $lista[$item]->lista[] = (object)[
                'texto' => $r->texto,
                'hora' => $Data->valor($r->data_criacao)->formato('H:i:s')
            ];
        }
        return $lista;
    }
}
