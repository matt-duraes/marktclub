<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;
use Helpers\OrmHelper;

final class UsuarioModel extends ORM
{
    public array $lista = [];
    public array $todos = [];

    /**
     * Pega os dados do usuário
     *
     * @param integer $id ID da votação
     */
    public function __construct(
        private int $id
    ) {
        $usuario = $this->pegarUsuario();
        $this->montarUsuario($usuario);
    }

    private function pegarUsuario()
    {
        return (new OrmHelper(TABELA_VOTACAO_USUARIO))->listar(
            campo: ['id_usuario_cliente', 'nome', 'cpf'],
            where: ['id_votacao_dado', $this->id],
            ordem: 'RAND()'
        );
    }

    private function montarUsuario($lista)
    {
        foreach ($lista as $r) {
            $this->lista[$r->id_usuario_cliente] = (object)[
                'nome' => $r->nome,
                'cpf'  => strCpf($r->cpf)
            ];
            $this->todos[] = [
                'nome' => $r->nome,
                'cpf'  => strCpf($r->cpf)
            ];
        }
    }
}
