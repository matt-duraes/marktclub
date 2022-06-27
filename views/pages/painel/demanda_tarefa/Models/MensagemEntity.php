<?php

namespace Painel\Demanda\Models;

use ORM\Entity;
use Erro\Excecao;
use Helpers\MarkdownHelper;

final class MensagemEntity extends Entity
{
    protected string $_tabela = TABELA_DEMANDA_MENSAGEM;

    protected array $_insert = ['id_demanda_tarefa', 'id_usuario_equipe', 'texto'];
    protected array $_buscar = ['texto'];

    public function __construct(
        private string $tarefa,
        public string $texto
    ) {
        parent::__construct();
    }

    protected function regraInsert()
    {
        try {
            $Tarefa = new TarefaEntity();
            $Tarefa->id($this->tarefa);
        } catch (\Throwable) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'A tarefa foi não foi encontrada.');
        }

        $this->texto = (new MarkdownHelper)->criarHtml($this->texto);
        $this->id_demanda_tarefa = $Tarefa->get('id');
        $this->id_usuario_equipe = sessao('USUARIO.id');
    }
}
