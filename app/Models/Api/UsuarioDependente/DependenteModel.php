<?php

namespace App\Models\Api\UsuarioDependente;

use ORM\ORM;
use Http\Request;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class DependenteModel extends ORM
{
    protected string $_tabela = TABELA_USUARIO_CLIENTE;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
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

        $lista = $this->campo(['cod', 'nome', 'email_pessoal', 'email_trabalho', 'status'])->where([
            ['titular', $titular],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ])->order('id', 'ASC')->limit(0, 5)->read();

        return $this->montarRetorno($lista);
    }

    private function pegarTitular(): int|bool
    {
        $Cliente = new ClienteEntity(validarToken: false);
        $Cliente->buscar([
            ['cod', $this->request->usuario],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);
        return $Cliente->get('id');
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'nome' => $r->nome,
                'email' => !empty($r->email_pessoal) ? $r->email_pessoal : $r->email_trabalho,
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
