<?php

namespace App\Models\Api\SolicitacaoSalavip;

use ORM\ORM;
use Http\Request;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoSalavip\Empresa;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\SolicitacaoSalavip\Trait\WhereTrait;
use App\Models\Api\SolicitacaoSalavip\Trait\ValidarRequestTrait;

final class DownloadModel extends ORM
{
    use PaginaTrait;
    use OrdemTrait;
    use WhereTrait;
    use ValidarRequestTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_VOUCHER;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
        $this->validarCamposAceito();
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
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado);
    }

    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'solicitacao_salavip',
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
        foreach ($dado as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind == 'data_validacao') {
                    $ind = 'data';
                    $val = dataBr($val);
                } elseif ($ind == 'empresa') {
                    $val = (new Empresa($val))->Nome();
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
        $camposAceito = ['empresa', 'codigo', 'data'];

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

    private function converterCampoParaDownload()
    {
        $campo = array_flip(jsonDecode($this->request->campo, true, true));
        if (array_key_exists('data', $campo)) {
            unset($campo['data']);
            $campo['data_validacao'] = true;
        }
        return array_keys($campo);
    }
}
