<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\DataHora;
use Modules\Email;
use Modules\Telefone;
use ORM\ORM;
use Throwable;

class DownloadModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;

    /**
     * @param array|null  $campos
     * @param string|null $usuario
     * @param string|null $empresa
     * @param string|null $parceiro
     * @param Data        $dataIndicacaoInicio
     * @param Data        $dataIndicacaoFinal
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?array $campos = null,
        private readonly ?string $usuario = null,
        private readonly ?string $empresa = null,
        private readonly ?string $parceiro = null,
        private readonly Data $dataIndicacaoInicio = new Data(),
        private readonly Data $dataIndicacaoFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarCamposAceito();
        parent::__construct();
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function download(): array
    {
        $campos = $this->converterCampoParaDownload();
        $indicacoes = $this->buscarIndicacoes($campos);

        if (!array_key_exists('0', $indicacoes)) {
            $this->erroDownloadPadrao();
        }

        $this->salvarLogDownload($indicacoes);
        return $this->montarRetornoDownload($indicacoes);
    }

    /**
     * @param array $campos
     *
     * @return mixed
     * @throws Excecao
     */
    private function buscarIndicacoes(array $campos): mixed
    {
        $query = $this
            ->campo($campos)
            ->where($this->pegarWhere(), false);

        /*$campoEmpresa = [];
        if (in_array('empresa_titulo', $this->campos)) {
            $campoEmpresa[] = 'titulo';
        }
        if (!empty($campoEmpresa)) {
            $query
                ->tabela(TABELA_COMERCIAL_EMPRESA)
                ->campo($campoEmpresa, 'empresa')
                ->leftJoin('id', 'id_admin_empresa');
        }*/

        return $query->read();
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        if (!empty($this->parceiro) && !validarUuid($this->parceiro, false)) {
            $where[] = ['nome', 'like', '%' . $this->parceiro . '%'];
        } elseif (!empty($this->parceiro) && validarUuid($this->parceiro, false)) {
            $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
            $where[] = ['id_parceiro_loja', $ormHelper->pegarIdPeloUuid($this->parceiro)];
        }

        if ($this->dataIndicacaoInicio->valido() && $this->dataIndicacaoFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataIndicacaoInicio->date(), $this->dataIndicacaoFinal->date()]
            ];
        } elseif ($this->dataIndicacaoInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataIndicacaoInicio->date()];
        } elseif ($this->dataIndicacaoFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataIndicacaoFinal->date()];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        return $where;
    }

    /**
     * @param array $indicacoes
     *
     * @return void
     * @throws Excecao
     */
    private function salvarLogDownload(array $indicacoes): void
    {
        try {
            $LogDownloadEntity = new LogDownloadEntity(
                app: 'solicitacao_loja',
                request: [
                    'campos'           => $this->campos,
                    'usuario'          => $this->usuario,
                    'empresa'          => $this->empresa,
                    'parceiro'         => $this->parceiro,
                    'indicacao_inicio' => $this->dataIndicacaoInicio->data(),
                    'indicacao_final'  => $this->dataIndicacaoFinal->data(),
                    'status'           => $this->status->indice()
                ],
                quantidade: count($indicacoes),
                usuario: $this->usuario
            );
            $LogDownloadEntity->salvar();
        } catch (Throwable) {
            $this->erroDownloadPadrao();
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function erroDownloadPadrao(): void
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
    }

    /**
     * @param array $indicacoes
     *
     * @return array
     */
    private function montarRetornoDownload(array $indicacoes): array
    {
        $i = 0;
        $retorno = [];
        foreach ($indicacoes as $indicacao) {
            foreach ($indicacao as $coluna => $valor) {
                if ($coluna == 'telefone') {
                    $valor = (new Telefone($valor))->numero();
                } elseif ($coluna == 'email') {
                    $valor = (new Email($valor))->email();
                } elseif ($coluna == 'data_criacao') {
                    $valor = (new DataHora($valor))->data();
                } elseif ($coluna == 'data_atualizacao') {
                    $valor = (new DataHora($valor))->data();
                } elseif ($coluna == 'status') {
                    $valor = (new Status($valor))->indice();
                } else {
                    $valor = strNull($valor);
                }
                $retorno[$i][$coluna] = $valor;
            }
            $i++;
        }
        return $retorno;
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarCamposAceito(): void
    {
        $camposAceito = [
            'empresa_titulo', 'nome', 'telefone', 'email', 'mensagem',
            'data_criacao', 'data_atualizacao', 'status'
        ];

        $listaCampos = jsonDecode($this->campos, true, true);
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

    /**
     * @return array
     */
    private function converterCampoParaDownload(): array
    {
        $campos = array_flip($this->campos);
        if (array_key_exists('empresa_titulo', $campos)) {
            unset($campos['empresa_titulo']);
        }
        return array_keys($campos);
    }
}
