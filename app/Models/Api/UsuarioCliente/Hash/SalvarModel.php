<?php

namespace App\Models\Api\UsuarioCliente\Hash;

use ORM\ORM;

final class SalvarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private string $hash;
    public array $retorno;

    public function __construct(
        private ?string $usuario = null,
        private ?string $tipo = null
    ) {
        parent::__construct();
        $this->validarClasse();
        $this->criarHash();

        $this->retorno = [
            'id'   => uuid(),
            'hash' => $this->hash
        ];
    }

    private function validarClasse()
    {
        if (empty($this->usuario) || empty($this->tipo)) {
            mensagemErro('Erro!', 'Não foi possível criar o hash.');
        }
    }

    private function criarHash()
    {
        $hash = uuid();
        try {
            $this->dado([
                'hash'      => $hash,
                'hash_data' => agora(),
                'hash_tipo' => $this->tipo
            ])
            ->where(['uuid', $this->usuario])
            ->update();
        } catch (\Throwable $th) {
            mensagemErro('Erro!', 'Usuário não encontrado ou erro ao salvar hash.');
        }
        $this->hash = $hash;
    }
}
