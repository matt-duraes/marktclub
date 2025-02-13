<?php

namespace App\Models\Api\ComercialEmpresa;

use App\Classes\ComercialEmpresa\Helper;
use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\Status;
use Helpers\OrmHelper;
use Http\Request;
use Modules\Cnpj;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class EmpresaModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        if (is_null($request)) {
            return;
        }
        $this->validarRequest();
    }

    private function validarRequest()
    {
        $status = new Status($this->request->status);
        if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Campo inválido!', 'O status enviado não é válido.');
        }
        $prospeccaoStatus = new ProspeccaoStatus($this->request->prospeccao_status);
        if (!$prospeccaoStatus->vazio() && !$prospeccaoStatus->valido()) {
            mensagemErro('Campo inválido!', 'O status da prospecção enviada não é válida.');
        }
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(
                [
                    'cod', 'titulo', 'razao_social', 'cnpj', 'data_criacao', 'data_atualizacao', 'prospeccao_status',
                    'status', 'id_usuario_dono'
                ]
            )
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista ?? []);
        return $dado;
    }

    private function pegarWhere()
    {
        $where = [
            ['id_admin_empresa', 'null']
        ];
        if (!empty($this->request->empresa)) {
            $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $where = ['id_admin_empresa', $ormHelper->pegarIdPeloUuid($this->request->empresa)];
        }
        // CNPJ
        $cnpj = new Cnpj($this->request->cnpj);
        if (!$cnpj->vazio()) {
            $where[] = ['cnpj', $cnpj->numero()];
        }
        // STATUS
        $status = new Status($this->request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }
        // PROSPECCAO
        $prospeccaoStatus = new ProspeccaoStatus($this->request->prospeccao_status);
        if ($prospeccaoStatus->valido()) {
            $where[] = ['prospeccao_status', $prospeccaoStatus->numero()];
        }
        // PESQUISA
        $pesquisa = $this->request->pesquisa;
        $cnpj = soNumero($pesquisa);
        $titulo = $this->request->titulo;
        if (!empty($titulo)) {
            $pesquisa = $titulo;
            $cnpj = '';
        }
        if (!empty($pesquisa) && !empty($cnpj)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $pesquisa . '%'],
                ['razao_social', 'like', '%' . $pesquisa . '%'],
                ['nome_fantasia', 'like', '%' . $pesquisa . '%'],
                ['cnpj', 'like', '%' . $cnpj . '%'],
            ];
        } elseif (!empty($pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $pesquisa . '%'],
                ['razao_social', 'like', '%' . $pesquisa . '%'],
                ['nome_fantasia', 'like', '%' . $pesquisa . '%'],
            ];
        }
        // USUARIO
        $usuario = $this->request->usuario;
        $idEquipe = '';
        if (!empty($usuario)) {
            $idEquipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($usuario);
        }
        if (!empty($idEquipe)) {
            $where[] = ['id_usuario_equipe', $idEquipe];
        }
        // DONO
        $dono = $this->request->dono;
        $idDono = '';
        if (!empty($dono)) {
            $idDono = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($dono);
        }
        if (!empty($idDono)) {
            $where[] = ['id_usuario_dono', $idDono];
        }

        return $where;
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        $Status = new Status();
        $ProspeccaoStatus = new ProspeccaoStatus();

        foreach ($lista as $r) {
            $titulo = !empty($r->titulo) ? $r->titulo : $r->razao_social;

            $empresa = '';
            if (!empty($r->id_usuario_dono) && $r->id_usuario_dono != '') {
                $usuario = (new OrmHelper(TABELA_USUARIO_EQUIPE))
                    ->pegarCampoPor('id_admin_empresa', ['id', $r->id_usuario_dono]);
                $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
                    ->pegarCampoPor('nome_fantasia', ['id', $usuario]);
            }

            $retorno[] = [
                'id'                => $r->cod,
                'empresa_indicacao' => $empresa,
                'titulo'            => $titulo,
                'cnpj'              => $r->cnpj,
                'data_criacao'      => $r->data_criacao,
                'data_atualizacao'  => $r->data_atualizacao,
                'prospeccao_status' => $ProspeccaoStatus->indice($r->prospeccao_status),
                'status'            => $Status->indice($r->status)
            ];
        }

        return criptografarDado(
            dado: $retorno,
            criptografia: Helper::CRIPTOGRAFAR,
            lista: true
        );
    }
}
