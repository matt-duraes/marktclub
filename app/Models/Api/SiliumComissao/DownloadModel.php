<?php

namespace App\Models\Api\SiliumComissao;

use App\Classes\SiliumComissao\Ordem;
use App\Classes\SiliumComissao\Status;
use App\Models\Api\Painel\LogDownloadEntity;
use Erro\Excecao;
use Modules\Data;
use Modules\Dinheiro;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use Throwable;

class DownloadModel extends ORM
{
    use OrdemTrait;

    protected string $ormTabela = TABELA_SILIUM_COMISSAO;

    /**
     * @param array|null  $campos
     * @param string|null $usuario
     * @param Ordem       $ordem
     * @param string|null $cliente
     * @param string|null $parceiro
     * @param Data        $dataInicio
     * @param Data        $dataFinal
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?array $campos = null,
        private readonly ?string $usuario = null,
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $cliente = null,
        private readonly ?string $parceiro = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
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
        $campos = $this->converterCamposParaDownload();
        $compras = $this->buscarCompras($campos);

        if (!array_key_exists('0', $compras)) {
            $this->erroDownloadPadrao();
        }

        $this->salvarLogDownload($compras);
        return $this->montarRetornoDownload($compras);
    }

    /**
     * @param array $campos
     *
     * @return array
     * @throws Excecao
     */
    public function buscarCompras(array $campos): array
    {
        $empresa = false;
        $usuario = false;
        if (in_array('empresa', $campos)) {
            $campos = array_diff($campos, ['empresa']);
            $empresa = true;
        }
        if (in_array('cliente', $campos)) {
            $campos = array_diff($campos, ['cliente']);
            $usuario = true;
        }
        $query = $this->campo($campos)->where($this->pegarWhere(), false)->order($this->pegarOrdem(new Ordem()));

        if ($empresa) {
            $query
                ->tabela(TABELA_COMERCIAL_EMPRESA)
                ->join('id', 'id_admin_empresa')
                ->campo(['cod', 'titulo', 'nome_fantasia'], 'empresa');
        }

        if ($usuario) {
            $query
                ->tabela(TABELA_USUARIO_CLIENTE)
                ->where($this->pegarWhereUsuario(), false)
                ->join('id', 'id_usuario_cliente')
                ->campo(['uuid', 'nome'], 'usuario');
        }

        return $query->read();
    }

    /**
     * @throws Excecao
     */
    private function validarCamposAceito(): void
    {
        $camposAceito = [
            'empresa', 'cliente', 'parceiro', 'valor_compra',
            'comissao_usuario', 'pontuacao', 'data_compra', 'status',
            'data_criacao', 'data_atualizacao'
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
     */
    private function converterCamposParaDownload(): array
    {
        $campos = array_flip(jsonDecode($this->campos, true, true));
        return array_keys($campos);
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->parceiro)) {
            $where[] = ['parceiro', 'LIKE', "%$this->parceiro%"];
        }
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_compra', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_compra', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_compra', '<=', $this->dataFinal->date()];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @return array
     */
    private function pegarWhereUsuario(): array
    {
        $where = [];
        if (!empty($this->cliente)) {
            $where[] = ['nome', 'LIKE', "%$this->cliente%"];
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
     * @param array $compras
     *
     * @throws Excecao
     */
    private function salvarLogDownload(array $compras): void
    {
        try {
            $LogDownloadEntity = new LogDownloadEntity(
                'silium_comissao',
                [],
                count($compras),
                $this->usuario
            );
            $LogDownloadEntity->salvar();
        } catch (Throwable) {
            $this->erroDownloadPadrao();
        }
    }

    /**
     * @param array $compras
     *
     * @return array
     */
    private function montarRetornoDownload(array $compras): array
    {
        $i = 0;
        $retorno = [];
        foreach ($compras as $item) {
            foreach ($item as $campo => $valor) {
                if ($campo == 'valor_compra') {
                    $valor = (new Dinheiro($valor))->decimal();
                } elseif ($campo == 'comissao_usuario') {
                    $valor = (new Dinheiro($valor))->decimal();
                } elseif ($campo == 'status') {
                    $valor = (new Status($valor))->indice();
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
