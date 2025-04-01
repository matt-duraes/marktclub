<?php

namespace App\Models\Api\ComercialEmpresa;

use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use App\Classes\ComercialEmpresa\FinalidadeSecundaria;
use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Origem;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\Status;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\Trait\ValidarEmpresaDownloadTrait;
use App\Models\Api\UsuarioCliente\Trait\BuscarUsuarioTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\Cnpj;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\EnderecoEstado;
use Modules\Nome;
use Modules\Telefone;
use ORM\ORM;
use Throwable;

class DownloadModel extends ORM
{
    use ValidarEmpresaDownloadTrait;
    use BuscarUsuarioTrait;

    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    /**
     * @param array|null        $campos
     * @param string|null       $usuario
     * @param Ordem             $ordem
     * @param Cnpj              $cnpj
     * @param string|null       $pesquisa
     * @param string|null       $titulo
     * @param string|array|null $empresa
     * @param string|null       $subempresa
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
        private readonly ?array $campos = null,
        private readonly ?string $usuario = null,
        private readonly Ordem $ordem = new Ordem(),
        private readonly Cnpj $cnpj = new Cnpj(),
        private readonly ?string $pesquisa = null,
        private readonly ?string $titulo = null,
        private readonly string|array|null $empresa = null,
        private readonly ?string $subempresa = null,
        private readonly ?string $dono = null,
        private readonly Botao $semResponsavel = new Botao(),
        private readonly ProspeccaoStatus $prospeccaoStatus = new ProspeccaoStatus(),
        private readonly Status $status = new Status(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data()
    ) {
        $this->validarEmpresa($this->usuario);
        $this->validarCamposAceito();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarCamposAceito(): void
    {
        $camposAceito = [
            'titulo', 'nome_fantasia', 'razao_social', 'cnpj', 'responsavel_nome',
            'responsavel_cargo', 'responsavel_cpf', 'responsavel_telefone',
            'responsavel_email', 'finalidade_principal', 'finalidade_secundaria',
            'estado_principal', 'parceiro_proprio', 'concorrente_status',
            'concorrente_nome', 'origem', 'usuario_possivel', 'contato_preferencial',
            'data_apresentacao', 'formato_reuniao', 'indicado', 'equipe_nome',
            'motivo_standby', 'previsao_retorno', 'motivo_perdido'
        ];

        $listaCampos = jsonDecode($this->campos, true, true);
        if (empty($listaCampos)) {
            mensagemErro('Erro!', 'Você deve enviar pelo menos um campo.');
        }

        foreach ($listaCampos as $campo) {
            if (!in_array($campo, $camposAceito)) {
                mensagemErro(
                    'Erro!',
                    'Um ou mais campos não tem permissão para serem buscados.',
                    403,
                    localhost: 'O campo ' . $campo . ' não está na lista de campos permitidos'
                );
            }
        }
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function download(): array
    {
        $campos = $this->converterCamposParaDownload();
        $empresas = $this->buscarEmpresas($campos);

        if (!array_key_exists('0', $empresas)) {
            $this->erroDownloadPadrao();
        }

        $this->salvarLogDownload($empresas);
        return $this->montarRetornoDownload($empresas);
    }

    /**
     * @return array
     */
    private function converterCamposParaDownload(): array
    {
        $campos = array_flip(jsonDecode($this->campos, true, true));
        if (array_key_exists('finalidade_principal', $campos)) {
            unset($campos['finalidade_principal']);
            $campos['finalidade_empresa'] = true;
        }
        return array_keys($campos);
    }

    /**
     * @param array $campos
     *
     * @return array
     * @throws Excecao
     */
    public function buscarEmpresas(array $campos): array
    {
        return $this->campo($campos)->where($this->pegarWhere(), false)->read();
    }

    private function pegarWhere(): array
    {
        $where = [
            ['id_admin_empresa', 'null'],
            ['status', (new Status(Status::PROSPECCAO))->numero()]
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
        if ($this->semResponsavel->valor() === Botao::SIM) {
            $where[] = ['id_usuario_equipe', 'null'];
        }
        if (!empty($this->dono)) {
            $where[] = ['id_usuario_dono', $ormHelper->pegarIdPeloUuid($this->dono)];
        }
        return $where;
    }

    /**
     * @throws Excecao
     */
    private function erroDownloadPadrao(): void
    {
        mensagemErro(
            'Erro!',
            'Ocorreu um erro ao fazer o download, por favor, tente novamente.'
        );
    }

    /**
     * @param array $empresas
     *
     * @throws Excecao
     */
    private function salvarLogDownload(array $empresas): void
    {
        try {
            $LogDownloadEntity = new LogDownloadEntity(
                'comercial_contrato',
                [],
                count($empresas),
                $this->usuario
            );
            $LogDownloadEntity->salvar();
        } catch (Throwable) {
            $this->erroDownloadPadrao();
        }
    }

    /**
     * @param array $empresas
     *
     * @return array
     */
    private function montarRetornoDownload(array $empresas): array
    {
        $i = 0;
        $retorno = [];
        foreach ($empresas as $item) {
            foreach ($item as $campo => $valor) {
                if ($campo == 'cnpj') {
                    $valor = (new Cnpj($valor))->numero();
                } elseif ($campo == 'responsavel_nome') {
                    $valor = (new Nome($valor))->nome();
                } elseif ($campo == 'responsavel_cpf') {
                    $valor = (new Cpf($valor))->numero();
                } elseif ($campo == 'responsavel_telefone') {
                    $valor = (new Telefone($valor))->telefone();
                } elseif ($campo == 'responsavel_email') {
                    $valor = (new Email($valor))->email();
                } elseif ($campo == 'finalidade_empresa') {
                    $campo = 'finalidade_principal';
                    $valor = (new FinalidadePrincipal($valor))->nome();
                } elseif ($campo == 'finalidade_secundaria') {
                    $valor = (new FinalidadeSecundaria($valor))->nome();
                } elseif ($campo == 'estado_principal') {
                    $valor = (new EnderecoEstado($valor))->estado();
                } elseif ($campo == 'origem') {
                    $valor = (new Origem($valor))->nome();
                } else {
                    $valor = strNull($valor);
                }
                $retorno[$i][$campo] = $valor;
            }
            $i++;
        }
        return $retorno;
    }
}
