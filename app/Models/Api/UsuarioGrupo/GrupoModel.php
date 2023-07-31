<?php

namespace App\Models\Api\UsuarioGrupo;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class GrupoModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_USUARIO_GRUPO;
    private int $idEmpresa;

    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
        if (!$request) {
            return;
        }
        $this->validarEmpresa();
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
                'id'               => $r->uuid,
                'indice'           => $r->indice,
                'titulo'           => $r->titulo,
                'data_criacao'     => $r->data_criacao,
                'data_atualizacao' => $r->data_atualizacao,
                'status'           => (new Status($r->status))->indice()
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
        } elseif (!empty($quantidade) && !validarPagina($quantidade)) {
            mensagemErro('Dado inválido!', 'O campo quantidade não é um valor válido.');
        } elseif (!empty($quantidade) && $quantidade > 50) {
            mensagemErro('Dado inválido!', 'O campo quantidade deve ser menor ou igual a 50.');
        } elseif (!$status->vazio() && !$status->valido()) {
            mensagemErro('Dado inválido!', 'O campo status não é um valor válido.');
        }
    }
}
