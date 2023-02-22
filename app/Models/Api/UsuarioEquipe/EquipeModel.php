<?php

namespace App\Models\Api\UsuarioEquipe;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Trait\Model\PaginaTrait;
use App\Classes\UsuarioEquipe\Ordem;
use App\Classes\UsuarioEquipe\Status;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class EquipeModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $_tabela = TABELA_USUARIO_EQUIPE;

    private int $idEmpresa;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }
    public function listarDados(): stdClass
    {
        $request = $this->request;
        $where = [['id_admin_empresa', $this->idEmpresa]];

        $pesquisa = $request->pesquisa;
        if (!empty($pesquisa)) {
            $wherePesquisa = [
                'OR',
                ['nome_real', 'like', '%' . $pesquisa . '%'],
                ['email_pessoal', 'like', $pesquisa . '%'],
                ['email_trabalho', 'like', $pesquisa . '%']
            ];
            $documento = soNumero($pesquisa);
            if (!empty($documento)) {
                $wherePesquisa[] = ['documento_cpf', 'like', $documento . '%'];
            }
            $where[] = $wherePesquisa;
        }
        $nome = $request->nome;
        if (!empty($nome)) {
            $where[] = ['nome_real', 'like', '%' . $nome . '%'];
        }
        $email = $request->email;
        if (!empty($nome)) {
            $where[] = [
                'OR',
                ['email_pessoal', 'like', $email . '%'],
                ['email_trabalho', 'like', $email . '%'],
            ];
        }
        $cpf = soNumero($request->cpf);
        if (!empty($cpf)) {
            $where[] = ['documento_cpf', 'like', $cpf . '%'];
        }
        $status = new Status($request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        } else {
            $where[] = ['status', 'in', [1, 2]];
        }

        $dado = $this
            ->campo([
                'uuid', 'nome_perfil', 'nome_real', 'documento_cpf', 'email_trabalho',
                'email_pessoal', 'status', 'data_criacao', 'imagem_tipo', 'imagem_arquivo',
                'imagem_facebook', 'imagem_google'
            ])
            ->where($where)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order(new Ordem($request->ordem))
            ->tabela(TABELA_EMPRESA_NOVO)
            ->join('id', 'id_admin_empresa')
            ->campo(['cod', 'nome_fantasia'], 'empresa')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno($dado)
    {
        if (!$dado) {
            return [];
        }

        $Status = new Status();
        $lista = [];
        foreach ($dado as $r) {
            $email = null;
            if (!empty($r->email_pessoal)) {
                $email = $r->email_pessoal;
            } else if (!empty($r->email_trabalho)) {
                $email = $r->email_trabalho;
            }
            $lista[] = [
                'id' => $r->uuid,
                'empresa' => [
                    'id' => $r->empresa_cod,
                    'nome_fantasia' => $r->empresa_nome_fantasia,
                ],
                'nome' => $r->nome_real,
                'perfil' => $r->nome_perfil,
                'cpf' => $r->documento_cpf,
                'email' => strEmail($email),
                'imagem' => imagemUsuario($r->imagem_tipo, $r->imagem_arquivo, $r->imagem_facebook, $r->imagem_google),
                'data_criacao' => $r->data_criacao,
                'status' => $Status->indice($r->status),
            ];
        }
        return $lista;
    }
}
