<?php

namespace ApiModel\Log;

use ORM\Entity;
use System\Classes\LogErro\Status;

final class ErrorEntity extends Entity
{
    protected string $ormTabela = TABELA_LOG_ERRO;

    protected array $ormInsert = [
        'hash', 'mensagem', 'codigo', 'status_http', 'arquivo', 'linha', 'trace', 'quantidade'
    ];
    protected array $ormSalvar = ['status'];
    protected array $ormBuscar = [
        'hash', 'mensagem', 'codigo', 'status_http', 'arquivo', 'linha', 'trace',
        'quantidade', 'data_criacao', 'status'
    ];

    public string $hash;
    public Status $status;
    public int $quantidade;

    public function __construct(
        public ?string $mensagem = null,
        public ?string $codigo = null,
        public ?string $status_http = null,
        public ?string $arquivo = null,
        public ?string $linha = null,
        public null|string|array $trace = null,
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->trace = jsonDecode($this->trace, true, true);
    }

    protected function regraInsert()
    {
        $this->quantidade = 1;
        $hash = md5($this->mensagem . $this->arquivo . $this->linha);
        $this->hash = $hash;
        $this->verificarSeJaExiste($hash);
        $this->status = new Status(Status::NOVO);
    }

    private function verificarSeJaExiste(string $hash)
    {
        $erro = $this->campo(['id', 'uuid', 'quantidade'])->where([
            ['hash', $hash],
            ['status', 1]
        ])->primeiro();

        if ($erro) {
            $this->id = $erro->uuid;
            $this->atualizarLogErro($erro->id, $erro->quantidade);
            mensagemErro('erro_duplicado', 'erro_duplicado');
        }
    }
    private function atualizarLogErro($id, $quantidade)
    {
        $this
            ->dado([
                'data_atualizacao' => agora(),
                'quantidade' => $quantidade + 1
            ])->where(['id', $id])
            ->update();
    }
}
