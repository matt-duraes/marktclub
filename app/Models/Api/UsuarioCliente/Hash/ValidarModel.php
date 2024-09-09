<?php

namespace App\Models\Api\UsuarioCliente\Hash;

use ORM\ORM;
use stdClass;

final class ValidarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private stdClass $dado;
    public array $retorno;

    public function __construct(
        private ?string $usuario = null,
        private ?string $hash = null,
        private ?string $tipo = null
    ) {
        parent::__construct();
        $this->validarClasse();
        $this->buscarUsuario();
        $this->validarHash();
        $this->atualizarUsuario();
        $this->retorno = [
            'id'         => uuid(),
            'autorizado' => 'sim'
        ];
    }

    private function validarClasse()
    {
        if (empty($this->usuario) || empty($this->hash) || empty($this->tipo)) {
            $this->naoAutorizado('Dados inválidos para validar hash.');
        }
    }

    private function buscarUsuario()
    {
        $usuario = $this->campo(['hash', 'hash_data', 'hash_tipo'])->where(['uuid', $this->usuario])->primeiro();
        if (vazio($usuario)) {
            $this->naoAutorizado('Usuário não encontrado pelo código enviado.');
        }
        $this->dado = $usuario;
    }

    private function validarHash()
    {
        $dado = $this->dado;
        $dataVencimento = dataRemover(data: agora(), numero: 5, tempo: 'minutos', formato: 'Y-m-d H:i:s');
        if ($dado->hash != $this->hash || $dado->hash_tipo != $this->tipo) {
            $this->naoAutorizado('Hash inválido.');
        } elseif ($dado->hash_data < $dataVencimento) {
            $this->naoAutorizado('Hash vencido.');
        }
    }

    private function atualizarUsuario()
    {
        try {
            $this
                ->dado([
                    'hash' => null,
                    'hash_data' => null,
                    'hash_tipo' => null
                ])
                ->where(['uuid', $this->usuario])
                ->update();
        } catch (\Throwable) {
        }
    }

    private function naoAutorizado(string $mensagem)
    {
        mensagemErro(titulo: 'Sem permissão!', mensagem: $mensagem, dado: ['autorizado' => 'nao'], status: 404);
    }
}
