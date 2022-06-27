<?php

namespace App\Models\Api\SolicitacaoSalavip;

use stdClass;
use Http\Request;
use Modules\Data;
use App\Models\Api\GeralModel;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoSalavip\Empresa;
use App\Models\Api\Painel\LogDownloadEntity;

final class SalavipModel extends GeralModel
{
    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
    public function download()
    {
        $this->validarRequest();
        $this->validarCamposAceito();
        $campo = $this->converterCampoParaDownload();

        $dado = $this
            ->campo($campo)
            ->where($this->pegarWhere())
            ->order(new Ordem($this->request->ordem))
            ->read();

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado);
    }
    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'solicitacao_salavip',
            request: $this->request->dado(),
            quantidade: count($dado)
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
                } else if ($ind == 'empresa') {
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


    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function listarDados(): stdClass
    {
        $this->validarRequest();

        $dado = $this
            ->campo(['cod', 'empresa', 'codigo', 'data_validacao'])
            ->pagina($this->pegarPagina(), 50)
            ->where($this->pegarWhere())
            ->order(new Ordem($this->request->ordem))
            ->tabela('parceiro_novo')->join('cod', 'vinculo')->campo(['titulo'])
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'empresa' => (new Empresa($r->empresa))->nome(),
                'codigo' => $r->codigo,
                'data' => $r->data_validacao
            ];
        }
        return $retorno;
    }

    protected function pegarWhere(): array
    {
        $where = [
            ['status', 2],
            ['data_validacao', '>=', '2018-06-01'],
            ['vinculo', 'in', ['2', '8074', '890713a200a9e45aa85e2ae67aa41e74', '9792e058562303f9e7e0604c5117c569']],
        ];

        $Empresa = new Empresa($this->request->empresa);
        if (!$Empresa->vazio() && $Empresa->valido()) {
            $where[] = ['empresa', $Empresa->numero()];
        } else {
            $where[] = ['empresa', 'in', [2, 66]];
        }

        $dataDe = $this->request->data_de;
        if (validarDataDate($dataDe)) {
            $where[] = ['data_validacao', '>=', dataBanco($dataDe)];
        }
        $dataAte = $this->request->data_ate;
        if (validarDataDate($dataAte)) {
            $where[] = ['data_validacao', '<=', dataBanco($dataAte) . ' 23:59:59'];
        }
        return $where;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function validarRequest()
    {
        $Empresa = new Empresa($this->request->empresa);
        if (!$Empresa->vazio() && !$Empresa->valido()) {
            mensagemErro('Campo inválido!', 'A empresa informada não é válida.');
        }
        $DataDe = new Data($this->request->data_de);
        if (!$DataDe->vazio() && (!$DataDe->valido() || !$DataDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de início da busca não é válida.');
        }
        $DataAte = new Data($this->request->data_ate);
        if (!$DataAte->vazio() && (!$DataAte->valido() || !$DataAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de final da busca não é válida.');
        }
        $Ordem = new Ordem($this->request->ordem);
        if (!$Ordem->vazio() && !$Ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é válida.');
        }
    }
}
