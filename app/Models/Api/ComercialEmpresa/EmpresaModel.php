<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\ORM;
use stdClass;
use Http\Request;
use Modules\Cnpj;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\ComercialEmpresa\Ordem;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\ComercialEmpresa\Helper;
use App\Classes\ComercialEmpresa\Status;
use System\Interface\ModelListarInterface;
use App\Models\Api\UsuarioEquipe\HelperModel;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;

final class EmpresaModel extends ORM implements ModelListarInterface
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

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'titulo', 'razao_social', 'cnpj', 'data_criacao', 'prospeccao_status', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->leftJoin('id', 'id_usuario_equipe')
            ->campo(
                [
                    'uuid', 'nome_real', 'nome_perfil', 'imagem_tipo', 'imagem_arquivo',
                    'imagem_facebook', 'imagem_google'
                ],
                'usuario'
            )
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista ?? []);
        return $dado;
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        $Status = new Status();
        $ProspeccaoStatus = new ProspeccaoStatus();

        foreach ($lista as $r) {
            $titulo = !empty($r->titulo) ? $r->titulo : $r->razao_social;
            $retorno[] = [
                'id'                => $r->cod,
                'usuario'           => $this->montarPerfil($r),
                'titulo'            => $titulo,
                'cnpj'              => $r->cnpj,
                'data_criacao'      => $r->data_criacao,
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

    private function montarPerfil($r)
    {
        if (empty($r->usuario_uuid)) {
            return [
                'nome'   => 'Sem usuário',
                'imagem' => imagemUsuario()
            ];
        }
        return [
            'id'     => $r->usuario_uuid,
            'nome'   => $r->usuario_nome_real,
            'perfil' => $r->usuario_nome_perfil,
            'imagem' => imagemUsuario(
                $r->usuario_imagem_tipo,
                $r->usuario_imagem_arquivo,
                $r->usuario_imagem_facebook,
                $r->usuario_imagem_google
            )
        ];
    }

    private function pegarWhere()
    {
        $where = [
            ['id_admin_empresa', 'null']
        ];
        // CNPJ
        $cnpj = new Cnpj($this->request->cnpj);
        if ($cnpj->valido()) {
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
            $Equipe = new HelperModel();
            $idEquipe = $Equipe->pegarIdPeloUuid($usuario);
        }
        if (!empty($idEquipe)) {
            $where[] = ['id_usuario_equipe', $idEquipe];
        }

        return $where;
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
}
