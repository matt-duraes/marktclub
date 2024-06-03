<?php

namespace App\Models\Api\SolicitacaoSalavip;

use ORM\ORM;
use Throwable;
use Erro\Excecao;
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

    /**
     * @param Request $request
     */
    public function __construct(
        protected Request $request
    ) {
        $this->validarRequest();
        $this->validarCamposAceito();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarCamposAceito(): void
    {
        $camposAceito = ['empresa', 'data_de', 'data_ate'];

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

    /**
     * @return array
     * @throws Excecao
     */
    public function download(): array
    {
        $campo = $this->converterCampoParaDownload();
        $dado = $this
            ->campo($campo)
            ->where($this->pegarWhere(), false)
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado);
    }

    /**
     * @return array
     */
    private function converterCampoParaDownload(): array
    {
        $campo = array_flip(jsonDecode($this->request->campo, true, true));
        if (array_key_exists('data', $campo)) {
            unset($campo['data']);
            $campo['data_validacao'] = true;
        }
        return array_keys($campo);
    }

    /**
     * @param array $dado
     *
     * @throws Excecao
     */
    private function salvarLogDownload(array $dado): void
    {
        $Log = new LogDownloadEntity(
            app: 'solicitacao_salavip',
            request: $this->request->dado(),
            quantidade: count($dado),
            usuario: $this->request->usuario
        );
        try {
            $Log->salvar();
        } catch (Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
        }
    }

    /**
     * @param array $dado
     *
     * @return array
     */
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
}
