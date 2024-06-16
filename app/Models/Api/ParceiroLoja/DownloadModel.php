<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use Http\Request;
use Modules\Data;
use Modules\DataHora;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\ParceiroLoja\TipoEstabelecimento;
use App\Models\Api\ParceiroLoja\Trait\WhereTrait;
use App\Models\Api\ParceiroLoja\Trait\MontarRetornoTrait;

final class DownloadModel extends ORM
{
    use ValidarEmpresaTrait;
    use MontarRetornoTrait;
    use WhereTrait;

    public array $campo;
    public string $equipe;
    public string $usuario;
    public string $empresa;
    private int $idEmpresa;
    public Categoria $categoria;
    public string $subcategoria;
    public TipoEstabelecimento $tipo_estabelecimento;
    public string $titulo;
    public string $pesquisa;
    public TipoLoja $tipo_loja;
    public Status $status;
    public string $convenio_direto;
    public string $painel;
    public Data $data_criacao_de;
    public Data $data_criacao_ate;
    public Data $data_publicacao_de;
    public Data $data_publicacao_ate;
    public Data $data_prospeccao_de;
    public Data $data_prospeccao_ate;
    public Data $data_problema_de;
    public Data $data_problema_ate;
    public Data $data_cancelado_de;
    public Data $data_cancelado_ate;
    public Data $data_auditoria_de;
    public Data $data_auditoria_ate;
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->validarCamposAceito();
    }

    public function download()
    {
        $campo = $this->campo;
        $dado = $this->buscarLojas($campo);

        if (!array_key_exists('0', $dado)) {
            $this->erroDownloadPadrao();
        }

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado, $campo);
    }

    private function buscarLojas(array $campo)
    {
        $empresaId = $this->pegarEmpresa();

        return $this
            ->campo($campo)
            ->where($this->pegarWhere($empresaId), false)
            ->read();
    }

    private function pegarEmpresa(): null|int
    {
        if (!$this->propriedadeExiste('empresa') || empty($this->empresa)) {
            return null;
        }

        return (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarIdPeloUuid($this->empresa);
    }

    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'parceiro_loja',
            request: $this->request->dado(),
            quantidade: count($dado),
            usuario: $this->usuario
        );
        try {
            $Log->salvar();
        } catch (\Throwable) {
            $this->erroDownloadPadrao();
        }
    }

    private function erroDownloadPadrao()
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
    }

    private function montarRetornoDownload(array $dado, array $campo): array
    {
        $i = 0;
        $retorno = [];
        foreach ($dado as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind === 'status') {
                    $val = (new Status($val))->indice();
                } elseif ($ind === 'tipo_loja') {
                    $val = (new TipoLoja($val))->indice();
                } elseif ($ind === 'documento_cpf') {
                    $ind = 'cpf';
                    $val = strCpf($val);
                } elseif ($ind === 'documento_cnpj') {
                    $ind = 'cnpj';
                    $val = strCnpj($val);
                } elseif ($ind === 'responsavel_cpf') {
                    $val = strCpf($val);
                } elseif ($ind === 'data_contrato_inicio' || $ind === 'data_contrato_vencimento') {
                    $val = (new DataHora($val))->date();
                } elseif ($ind === 'responsavel_telefone') {
                    $val = (new Telefone($val))->numero();
                } elseif ($ind === 'categoria_principal') {
                    $val = (new Categoria($val))->indice();
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        return $retorno;
    }

    private function validarCamposAceito(): void
    {
        $camposAceito = [
            'titulo', 'titulo_interno', 'razao_social', 'nome_fantasia', 'documento_cnpj', 'documento_cpf', 'responsavel_nome',
            'responsavel_cargo', 'responsavel_cpf', 'responsavel_telefone', 'responsavel_email', 'desconto',
            'texto_descricao', 'texto_desconto', 'texto_procedimento', 'texto_restricao', 'texto_outro',
            'texto_voucher', 'comissao_minima', 'comissao_maxima', 'data_contrato_inicio', 'data_contrato_vencimento',
            'tipo_loja', 'status', 'categoria_principal'
        ];

        $listaCampos = jsonDecode($this->request->campo, true, true);
        if (!$listaCampos) {
            mensagemErro('Erro!', 'Você deve enviar pelo menos um campo.');
        }

        foreach ($listaCampos as $campo) {
            if (!in_array($campo, $camposAceito)) {
                mensagemErro(
                    'Erro!',
                    'Um ou mais campos não tem permissão para serem buscados.',
                    status: 403,
                    localhost: 'O campo ' . $campo . ' não está na lista de campos permitidos'
                );
            }
        }
        return;
    }
}
