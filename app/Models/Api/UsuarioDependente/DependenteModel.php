<?php

namespace App\Models\Api\UsuarioDependente;

use ORM\ORM;
use Http\Request;
use App\Classes\UsuarioCliente\Helper;
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
        validarUuid($this->request->usuario);
        $titular = $this->pegarTitular();

        $lista = $this->campo(['cod', 'nome', 'email_pessoal', 'email_trabalho'])->where([
            ['empresa', $this->idEmpresa],
            ['titular', $titular],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ])->order('id', 'ASC')->limit(0, 5)->read();

        return $this->montarRetorno($lista);
    }

    private function pegarTitular(): int|bool
    {
        $Cliente = new ClienteEntity();
        $Cliente->buscar([
            ['cod', $this->request->usuario],
            ['status', 'in', Helper::STATUS_LIBERADO]
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
