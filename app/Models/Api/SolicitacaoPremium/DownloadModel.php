<?php

namespace App\Models\Api\SolicitacaoPremium;

use ORM\ORM;
use Http\Request;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\Trait\ValidarEmpresaDownloadTrait;
use App\Models\Api\SolicitacaoPremium\Trait\WhereTrait;
use App\Models\Api\SolicitacaoPremium\Trait\SetarDataTrait;
use App\Models\Api\SolicitacaoPremium\Trait\ValidarRequestTrait;

final class DownloadModel extends ORM
{
    use SetarDataTrait;
    use WhereTrait;
    use ValidarRequestTrait;
    use ValidarEmpresaDownloadTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;

    private array $campoInicial;
    private int $idEmpresa;

    private string $de;
    private string $ate;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa($request->usuario, 'empresa');
        $this->validarCamposAceito();
        $this->validarRequest();
        $this->setarDadoDaData();
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
    public function download()
    {
        $campo = $this->converterCampoParaDownload();
        $dado = $this
            ->campo($campo)
            ->where($this->pegarWhere())
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('cod', 'vinculo')
            ->where(['status', 5])
            ->read();

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado);
    }
    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'solicitacao_premium',
            request: $this->request->dado(),
            quantidade: count($dado),
            usuario: $this->request->usuario
        );
        try {
            $Log->salvar();
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
        }
    }
    private function montarRetornoDownload(array $dado): array
    {
        $i = 0;
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind == 'status') {
                    $val = $Status->indice($val);
                } else {
                    $val = strNull($val);
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
            'codigo', 'data_criacao', 'data_validacao', 'data_vencimento', 'status', 'parceiro'
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
    }
    private function converterCampoParaDownload()
    {
        $this->campoInicial = jsonDecode($this->request->campo, true, true);
        $campo = array_flip($this->campoInicial);
        if (array_key_exists('parceiro', $campo)) {
            unset($campo['parceiro']);
            $campo['titulo'] = true;
        }
        return array_keys($campo);
    }
}
