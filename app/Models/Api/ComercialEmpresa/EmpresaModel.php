<?php

namespace App\Models\Api\ComercialEmpresa;

use App\Classes\ComercialEmpresa\Helper;
use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\Status;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\Cnpj;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class EmpresaModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    /**
     * @param Pagina            $pagina
     * @param Quantidade        $quantidade
     * @param Ordem             $ordem
     * @param Cnpj              $cnpj
     * @param string|null       $pesquisa
     * @param string|null       $titulo
     * @param string|array|null $empresa
     * @param string|null       $subempresa
     * @param string|null       $usuario
     * @param string|null       $dono
     * @param Botao             $semResponsavel
     * @param ProspeccaoStatus  $prospeccaoStatus
     * @param Status            $status
     * @param Data              $dataInicio
     * @param Data              $dataFinal
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly Cnpj $cnpj = new Cnpj(),
        private readonly ?string $pesquisa = null,
        private readonly ?string $titulo = null,
        private readonly string|array|null $empresa = null,
        private readonly ?string $subempresa = null,
        private readonly ?string $usuario = null,
        private readonly ?string $dono = null,
        private readonly Botao $semResponsavel = new Botao(),
        private readonly ProspeccaoStatus $prospeccaoStatus = new ProspeccaoStatus(),
        private readonly Status $status = new Status(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->cnpj->vazio() && !$this->cnpj->valido()) {
            mensagemErro('Campo inválido!', 'O CNPJ informado não é válido.');
        }
        if (!$this->semResponsavel->vazio() && !$this->semResponsavel->valido()) {
            mensagemErro('Campo inválido!', 'O Sem responsável informado não é válido.');
        }
        if (!$this->prospeccaoStatus->vazio() && !$this->prospeccaoStatus->valido()) {
            mensagemErro('Campo inválido!', 'O Status da prospecção informado não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não está no formato válido.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início informada não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data final informada não está no formato válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $empresas = $this
            ->campo([
                'cod', 'id_usuario_equipe', 'id_usuario_dono', 'titulo',
                'razao_social', 'cnpj', 'data_criacao', 'data_atualizacao',
                'prospeccao_status', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();
        $empresas->lista = $this->montarRetorno($empresas->lista);
        return $empresas;
    }

    private function pegarWhere(): array
    {
        $where = [
            ['id_admin_empresa', 'null']
        ];
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        if (!empty($this->empresa)) {
            $where = is_string($this->empresa)
                ? ['id_admin_empresa', $ormHelper->pegarIdPeloUuid($this->empresa)]
                : ['id_admin_empresa', 'in', $ormHelper->mudarListaUuidParaId($this->empresa)];
        }
        if ($this->cnpj->valido()) {
            $where[] = ['cnpj', $this->cnpj->numero()];
        }
        if ($this->prospeccaoStatus->valido()) {
            $where[] = ['prospeccao_status', $this->prospeccaoStatus->numero()];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return array_merge($where, $this->pegarWherePesquisa(), $this->pegarWhereUsuario());
    }

    /**
     * @return array
     */
    private function pegarWherePesquisa(): array
    {
        $where = [];
        $pesquisa = $this->pesquisa;
        $cnpj = soNumero($pesquisa);
        if (!empty($this->titulo)) {
            $pesquisa = $this->titulo;
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
        return $where;
    }

    /**
     * @return array
     */
    private function pegarWhereUsuario(): array
    {
        $where = [];
        $ormHelper = new OrmHelper(TABELA_USUARIO_EQUIPE);
        if (!empty($this->usuario)) {
            $where[] = ['id_usuario_equipe', $ormHelper->pegarIdPeloUuid($this->usuario)];
        }
        if ($this->semResponsavel->valor() === Botao::SIM) {
            $where[] = ['id_usuario_equipe', 'null'];
        }
        if (!empty($this->dono)) {
            $where[] = ['id_usuario_dono', $ormHelper->pegarIdPeloUuid($this->dono)];
        }
        return $where;
    }

    /**
     * @param array $empresas
     *
     * @return array
     */
    private function montarRetorno(array $empresas): array
    {
        if (empty($empresas)) {
            return $empresas;
        }

        $retorno = [];
        $Status = new Status();
        $ProspeccaoStatus = new ProspeccaoStatus();
        $ormHelperEquipe = new OrmHelper(TABELA_USUARIO_EQUIPE);
        $ormHelperEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        foreach ($empresas as $empresa) {
            $titulo = !empty($empresa->titulo) ? $empresa->titulo : $empresa->razao_social;

            $empresaCaptador = '';
            if (!empty($empresa->id_usuario_dono)) {
                $idEmpresa = $ormHelperEquipe->pegarCampoPor('id_admin_empresa', ['id', $empresa->id_usuario_dono]);
                $empresaCaptador = $ormHelperEmpresa->pegarPrimeiroRegistro(
                    ['id', $idEmpresa],
                    ['cod', 'titulo'],
                    'object'
                );
            }

            $equipeResponsavel = '';
            if (!empty($empresa->id_usuario_equipe)) {
                $equipeResponsavel = $ormHelperEquipe->pegarPrimeiroRegistro(
                    ['id', $empresa->id_usuario_equipe],
                    ['uuid', 'nome_real'],
                    'object'
                );
            }

            $retorno[] = [
                'id'                => $empresa->cod,
                'empresa_id'        => !empty($empresaCaptador->cod) ? $empresaCaptador->cod : '',
                'empresa_nome'      => !empty($empresaCaptador->titulo) ? $empresaCaptador->titulo : '',
                'equipe_id'         => !empty($equipeResponsavel->uuid) ? $equipeResponsavel->uuid : '',
                'equipe_nome'       => !empty($equipeResponsavel->nome_real) ? $equipeResponsavel->nome_real : '',
                'titulo'            => $titulo,
                'cnpj'              => $empresa->cnpj,
                'prospeccao_status' => $ProspeccaoStatus->indice($empresa->prospeccao_status),
                'status'            => $Status->indice($empresa->status),
                'data_criacao'      => $empresa->data_criacao,
                'data_atualizacao'  => $empresa->data_atualizacao
            ];
        }
        return criptografarDado($retorno, Helper::CRIPTOGRAFAR, lista: true);
    }
}
