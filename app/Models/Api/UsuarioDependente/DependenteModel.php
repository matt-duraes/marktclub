<?php

namespace App\Models\Api\UsuarioDependente;

use ORM\ORM;
use Http\Request;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class DependenteModel extends ORM
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    private int $idEmpresa;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioDependente\DependenteModel');
        }
        $this->idEmpresa = TOKEN['empresa']->get('id');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA USUÁRIOS
    |--------------------------------------------------------------------------
    */
    public function listar()
    {
        $this->validarUsuario();
        $titular = $this->pegarTitular();

        $lista = $this->campo(['cod', 'nome', 'email_pessoal', 'email_trabalho'])->where([
            ['empresa', $this->idEmpresa],
            ['titular', $titular],
            ['status', 'in', [1, 2, 3, 5]]
        ])->order('id', 'ASC')->limit(0, 5)->read();

        return $this->montarRetorno($lista);
    }

    private function validarUsuario()
    {
        $tamanho = strlen($this->request->usuario);
        if (!in_array($tamanho, [32, 36])) {
            mensagemStatus(404);
        }
    }
    private function pegarTitular(): int|bool
    {
        $Cliente = new ClienteEntity();
        $Cliente->buscar([
            ['cod', $this->request->usuario],
            ['status', 'in', [1, 2, 3, 5]]
        ]);
        return $Cliente->get('id');
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'nome' => $r->nome,
                'email' => !empty($r->email_pessoal) ? $r->email_pessoal : $r->email_trabalho
            ];
        }
        return $retorno;
    }
}
