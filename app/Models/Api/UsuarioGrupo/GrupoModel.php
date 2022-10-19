<?php

namespace App\Models\Api\UsuarioGrupo;

use stdClass;
use Http\Request;
use App\Models\Api\GeralModel;
use App\Classes\StatusGeral\Status;

final class GrupoModel extends GeralModel
{
    protected string $_tabela = TABELA_USUARIO_GRUPO;

    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
        if (!$request) {
            return;
        }
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order([
                ['status', 'ASC'],
                ['titulo', 'ASC']
            ])
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);

        return $dado;
    }

    protected function pegarWhere(): array
    {
        $where = [['id_admin_empresa', $this->idEmpresa]];

        $status = new Status($this->request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        $pesquisa = $this->request->pesquisa;
        if (!empty($pesquisa)) {
            $where[] = [
                'OR',
                ['indice', 'like', '%' . $pesquisa . '%'],
                ['titulo', 'like', '%' . $pesquisa . '%']
            ];
        }
        return $where;
    }

    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'indice' => $r->indice,
                'titulo' => $r->titulo,
                'data_criacao' => $r->data_criacao,
                'data_atualizacao' => $r->data_atualizacao,
                'status' => (new Status($r->status))->indice()
            ];
        }
        return $retorno;
    }

    private function validarRequest()
    {
        $pagina = $this->request->pagina;
        $quantidade = $this->request->quantidade;
        $status = new Status($this->request->status);

        if (!validarPagina($pagina)) {
            mensagemErro('Dado inválido!', 'O campo página não é um valor válido.');
        } else if (!empty($quantidade) && !validarPagina($quantidade)) {
            mensagemErro('Dado inválido!', 'O campo quantidade não é um valor válido.');
        } else if (!empty($quantidade) && $quantidade > 50) {
            mensagemErro('Dado inválido!', 'O campo quantidade deve ser menor ou igual a 50.');
        } else if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Dado inválido!', 'O campo status não é um valor válido.');
        }
    }
}
