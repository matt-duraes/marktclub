<?php

namespace Painel\Historico\Models;

use Erro\Excecao;
use App\Models\Painel\AppGeral\AppGeralEntity;

final class Entity extends AppGeralEntity
{
    protected string $ormTabela = TABELA_PAINEL_HISTORICO;
    protected array $ormBuscar = ['id'];
    protected array $ormInsert = [
        'id_usuario_equipe',
        'app',
        'relacionamento',
        'acao',
        'dado',
        'texto',
        'status',
    ];
    protected array $ormUpdate = [
        'texto',
        'status' => 2
    ];

    /**
     * @param null|string $app            Nome do APP que foi usado
     * @param null|string $relacionamento O uuid do item que está sendo relacionado
     * @param null|string $acao           A ação que está sendo tomada. Ex.: insert, update, delete, email_enviado...
     * @param null|string $texto          Texto para o histórico
     * @param null|array  $dado           Dado que foram salvos
     */
    public function __construct(
        protected ?string $app = null,
        protected ?string $relacionamento = null,
        protected ?string $acao = null,
        protected ?string $texto = null,
        protected $dado = []
    ) {
        parent::__construct();
    }

    protected function regraInsert()
    {
        $this->id_usuario_equipe = sessao('USUARIO.id');
        $this->status = 1;
        if (!empty($this->texto)) {
            $this->status = 2;
        }
        $this->dado = base64Encode($this->dado);
    }

    protected function regraUpdate()
    {
        if (empty(trim($this->texto))) {
            throw new Excecao(titulo: 'Campo obrigatório!', mensagem: 'Você deve passar uma mensagem para o histórico.');
        }
    }
}
