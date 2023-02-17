<?php

namespace App\Models\Api\UsuarioLead;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Models\Api\GeralModel;
use App\Classes\UsuarioLead\Ordem;
use System\Trait\Model\OrdemTrait;
use App\Classes\UsuarioLead\Status;
use System\Trait\Model\PaginaTrait;
use App\Classes\UsuarioCliente\Origem;
use System\Trait\Model\QuantidadeTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class LeadModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $_tabela = TABELA_USUARIO_LEAD;
    private int $idEmpresa;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->validarCampoDoRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'nome_completo', 'email_pessoal', 'email_trabalho', 'email_funcional',
                'documento_cpf', 'data_criacao', 'lead_origem', 'status'
            ])
            ->where($this->pegarWhere())
            ->order($this->pegarOrdem(new Ordem))
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();
        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $Status = new Status();
        $Origem = new Origem();
        $retorno = [];
        foreach ($dado as $r) {
            $email = '';
            if (!empty($r->email_pessoal)) {
                $email = $r->email_pessoal;
            } else if (!empty($r->email_trabalho)) {
                $email = $r->email_trabalho;
            } else if (!empty($r->email_funcional)) {
                $email = $r->email_funcional;
            }

            $retorno[] = object([
                'id' => $r->uuid,
                'nome' => strNull($r->nome_completo),
                'cpf' => strCpf($r->documento_cpf),
                'email' => strNull($email),
                'data_criacao' => $r->data_criacao,
                'origem' => $Origem->indice($r->lead_origem),
                'status' => $Status->indice($r->status)
            ]);
        }
        return $retorno;
    }

    protected function pegarWhere(): array
    {
        $where = [['id_admin_empresa', $this->idEmpresa]];

        $request = $this->request;
        $nome = $request->nome;
        if (!empty($nome)) {
            $where[] = ['nome_completo', $nome];
        }

        $email = $request->email;
        if (!empty($email)) {
            $where[] = [
                'OR',
                ['email_trabalho', $email],
                ['email_pessoal', $email],
                ['email_funcional', $email]
            ];
        }

        $cpf = $request->cpf;
        if (!empty($cpf)) {
            $where[] = ['documento_cpf', $cpf];
        }

        $siape = $request->siape;
        if (!empty($siape)) {
            $where[] = ['documento_siape', $siape];
        }

        $status = new Status($request->status);
        if (!empty($status) && $status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        $origem = new Origem($request->origem);
        if (!empty($origem) && $origem->valido()) {
            $where[] = ['lead_origem', $origem->numero()];
        }

        $pesquisa = $request->pesquisa;
        if (!empty($pesquisa)) {
            $wherePesquisa = [
                'OR',
                ['nome_completo', 'like', '%' . $pesquisa . '%'],
                ['email_trabalho', 'like', $pesquisa . '%'],
                ['email_pessoal', 'like', $pesquisa . '%'],
                ['email_funcional', 'like', $pesquisa . '%']
            ];

            $cpfPesquisa = preg_replace("/[^0-9]/", "", $pesquisa);
            if (!empty($cpfPesquisa)) {
                $wherePesquisa[] = ['documento_cpf', 'like', $cpfPesquisa . '%'];
            }
            $where[] = $wherePesquisa;
        }

        return $where;
    }

    private function validarCampoDoRequest()
    {
        $ordem = new Ordem($this->request->ordem);
        $status = new Status($this->request->status);
        $origem = new Origem($this->request->origem);

        if (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é um valor válido.');
        } else if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é um valor válido.');
        } else if (!$origem->vazio() && !$origem->valido()) {
            mensagemErro('Campo inválido!', 'O Origem informado não é um valor válido.');
        }
    }
}
