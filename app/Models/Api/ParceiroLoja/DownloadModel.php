<?php

namespace App\Models\Api\ParceiroLoja;

use App\Classes\ParceiroLoja\CancelarMotivo;
use Http\Request;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\Download\DownloadGeralModel;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\ParceiroLoja\Trait\WhereTrait;
use App\Models\Api\ParceiroLoja\Trait\MontarRetornoTrait;
use App\Models\Api\ParceiroLoja\Trait\PropriedadeModelTrait;
use Modules\Data;

final class DownloadModel extends DownloadGeralModel
{
    use ValidarEmpresaTrait;
    use MontarRetornoTrait;
    use WhereTrait;
    use PropriedadeModelTrait;

    protected array $campoAceito = [
        'titulo', 'titulo_interno', 'razao_social', 'nome_fantasia', 'documento_cnpj', 'documento_cpf', 'responsavel_nome',
        'responsavel_cargo', 'responsavel_cpf', 'responsavel_telefone', 'responsavel_email', 'desconto',
        'texto_descricao', 'texto_desconto', 'texto_procedimento', 'texto_restricao', 'texto_outro',
        'texto_voucher', 'comissao_minima', 'comissao_maxima', 'data_contrato_inicio', 'data_contrato_vencimento',
        'tipo_loja', 'status', 'categoria_principal', 'equipe', 'endereco_estado', 'link_site', 'url',
        'pontuacao', 'data_auditoria', 'data_cancelado', 'cancelar_motivo', 'data_publicacao'
    ];

    public function __construct(
        protected Request $request
    ) {
        parent::__construct($request, TABELA_PARCEIRO_LOJA, 'parceiro-loja');
        $this->buscarRegistro();
        $this->validarBusca();
        $this->salvarLogDownload();
        $this->montarRetornoDownload();
        $this->salvarArquivo();
    }

    protected function buscarRegistro(): void
    {
        $empresaId = $this->pegarEmpresa();
        $novosCampos = $this->converterCampoParaDownload();

        $query = $this
            ->campo($novosCampos)
            ->where($this->pegarWhere($empresaId), false);

        $query = $this->pegarQueryEquipe($query);

        $this->busca = $query->read();
    }

    private function converterCampoParaDownload(): array
    {
        $campo = array_flip($this->campo);

        if (array_key_exists('empresa', $campo)) {
            unset($campo['empresa']);
        }
        if (array_key_exists('equipe', $campo)) {
            unset($campo['equipe']);
        }

        return array_keys($campo);
    }

    private function pegarEmpresa(): null|int
    {
        if (!$this->propriedadeExiste('empresa') || empty($this->empresa)) {
            return null;
        }

        return (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarIdPeloUuid($this->empresa);
    }

    private function pegarQueryEquipe($query)
    {
        $campoEquipe = [];
        if (in_array('equipe', $this->campo)) {
            $campoEquipe[] = 'nome_real';
        }
        if ($campoEquipe) {
            $query
                ->tabela(TABELA_USUARIO_EQUIPE)
                ->campo($campoEquipe, 'gestor')
                ->leftJoin('id', 'id_usuario_equipe');
        }
        return $query;
    }

    protected function montarRetornoDownload(): void
    {
        $i = 0;
        $retorno = [];
        foreach ($this->busca as $linha) {
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
                } elseif (
                    $ind === 'data_contrato_inicio' ||
                    $ind === 'data_contrato_vencimento' ||
                    $ind === 'data_auditoria' ||
                    $ind === 'data_cancelado' ||
                    $ind === 'data_publicacao'
                ) {
                    $val = (new Data($val))->date();
                } elseif ($ind === 'responsavel_telefone') {
                    $val = (new Telefone($val))->numero();
                } elseif ($ind === 'categoria_principal') {
                    $val = (new Categoria($val))->indice();
                } elseif ($ind === 'endereco_estado' && !empty($val)) {
                    $val = implode(', ', json_decode($val));
                } elseif ($ind === 'cancelar_motivo') {
                    $val = (new CancelarMotivo($val))->indice();
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        $this->busca = $retorno;
    }
}
