<?php

namespace ApiModel\Log;

use ORM\Entity;
use System\Classes\LogErro\Status;

final class ErrorEntity extends Entity
{
    protected string $_tabela = TABELA_LOG_ERRO;

    protected array $_insert = ['hash', 'mensagem', 'codigo', 'status_http', 'arquivo', 'linha', 'trace'];
    protected array $_salvar = ['status'];
    protected array $_buscar = ['hash', 'mensagem', 'codigo', 'status_http', 'arquivo', 'linha', 'trace', 'status'];

    public string $hash;
    public Status $status;

    public function __construct(
        public ?string $mensagem = null,
        public ?string $codigo = null,
        public ?string $status_http = null,
        public ?string $arquivo = null,
        public ?string $linha = null,
        public ?string $trace = null,
    ) {
        parent::__construct();
    }

    protected function regraInsert()
    {
        $hash = md5($this->mensagem . $this->arquivo . $this->linha);
        $this->verificarSeJaExiste($hash);
        $this->hash = $hash;
        $this->status = new Status(Status::STATUS_NOVO);
    }

    private function verificarSeJaExiste(string $hash)
    {
        $erro = $this->campo(['id', 'quantidade'])->where([
            ['hash', $hash],
            ['status', 1]
        ])->primeiro();

        if ($erro) {
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
