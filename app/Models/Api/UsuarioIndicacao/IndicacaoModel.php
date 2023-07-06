<?php

namespace App\Models\Api\UsuarioIndicacao;

use ORM\ORM;
use Http\Request;
use App\Classes\UsuarioIndicacao\Ordem;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class IndicacaoModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_USUARIO_INDICACAO;
    private int $idEmpresa;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
    }

    public function listar()
    {
        $where = $this->pegarWhere();
        $pagina = $this->request->chave('pagina', 1);
        $pagina = preg_match('/^[1-9]{1}[0-9]{0,}$/', $pagina) ? $pagina : 1;

        $dado = $this
            ->pagina($pagina)
            ->campo(['cod', 'nome', 'email', 'data_criacao', 'status'])
            ->where($where)
            ->order(new Ordem($this->request->ordem))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno($dado)
    {
        if (empty($dado)) {
            return [];
        }
        $Status = new Status();
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'           => $r->cod,
                'nome'         => strNull($r->nome),
                'email'        => strEmail($r->email),
                'data_criacao' => $r->data_criacao,
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWhere()
    {
        $where = [['id_admin_empresa', $this->idEmpresa]];

        $request = $this->request;

        $pesquisa = $request->pesquisa;
        if (!empty($pesquisa)) {
            $where[] = [
                'OR',
                ['nome', 'like', '%' . $pesquisa . '%'],
                ['email', 'like', $pesquisa . '%']
            ];
        }

        $nome = $request->nome;
        if (!empty($nome)) {
            $where[] = ['nome', 'like', '%' . $nome . '%'];
        }
        $email = $request->email;
        if (!empty($nome)) {
            $where[] = ['email', 'like', $email . '%'];
        }
        $status = new Status($request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        return $where;
    }
}
