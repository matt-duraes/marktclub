<?php

namespace App\Models\Api\Parceiro\Externo;

use App\Classes\Parceiro\Externo\Ordem;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use App\Models\Api\Download\DownloadGeralModel;
use Erro\Excecao;
use Helpers\OrmHelper;
use Http\Request;
use Modules\Data;
use System\Trait\Model\OrdemTrait;

final class DownloadModel extends DownloadGeralModel
{
    use OrdemTrait;

    public string $id_dono_equipe;
    public string $categoria_principal;
    public string $titulo_interno;
    public string $cancelar_motivo;
    public string $tipo_indicador;
    public string $data_cancelado;
    public string $data_criacao;
    public string $data_publicacao;
    protected array $campoAceito = [
        'titulo_interno', 'data_criacao', 'data_publicacao', 'status', 'id_dono_equipe',
        'categoria_principal', 'data_cancelado', 'cancelar_motivo', 'tipo_indicador'
    ];
    protected ?Ordem $ordem = null;
    protected ?string $pesquisa = null;
    protected ?string $empresa = null;
    protected ?string $equipe = null;
    protected ?Indicador $indicador = null;
    protected ?Categoria $categoria = null;
    protected ?array $estado = null;
    protected ?Data $dataInicio = null;
    protected ?Data $dataFinal = null;
    protected ?Status $status = null;

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
    ) {
        parent::__construct($request, TABELA_PARCEIRO_LOJA, 'parceiro-externo');
        $this->validarRequest();
        $this->buscarRegistro();
        $this->validarBusca();
        $this->salvarLogDownload();
        $this->montarRetornoDownload();
        $this->salvarArquivo();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!empty($this->ordem) && !$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!empty($this->indicador) && !$this->indicador->vazio() && !$this->indicador->valido()) {
            mensagemErro('Campo inválido!', 'O Indicador informado não é válido.');
        }
        if (!empty($this->categoria) && !$this->categoria->vazio() && !$this->categoria->valido()) {
            mensagemErro('Campo inválido!', 'A Categoria informada não é válida.');
        }
        if (!empty($this->dataInicio) && !$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início não está no formato válido.');
        }
        if (!empty($this->dataFinal) && !$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data final não está no formato válido.');
        }
        if (!empty($this->status) && !$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function buscarRegistro(): void
    {
        $this->busca = $this
            ->campo($this->campo)
            ->where($this->pegarWhere(), false)
            ->order($this->pegarOrdem(new Ordem()))
            ->read();
    }

    /**
     * @return array
     */
    public function pegarWhere(): array
    {
        $where = [];
        $where[] = ['status', (new Status(Status::PROSPECCAO))->numero()];
        if (!empty($this->pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $this->pesquisa . '%'],
                ['subcategoria_tag', 'like', '%' . $this->pesquisa . '%'],
                ['titulo_interno', 'like', '%' . $this->pesquisa . '%']
            ];
        }
        if (!empty($this->equipe)) {
            $where[] = ['id_dono_equipe', (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($this->equipe)];
        } else {
            $where[] = ['id_dono_equipe', '!=', 'null'];
        }
        if (!empty($this->categoria) && $this->categoria->valido()) {
            $where[] = ['categoria_principal', $this->categoria->numero()];
        }
        if (!empty($this->indicador) && $this->indicador->valido()) {
            $where[] = ['tipo_indicador', $this->indicador->numero()];
        }
        if (!empty($this->estado)) {
            $where[] = ['endereco_estado', 'json', $this->estado];
        }
        if (
            !empty($this->dataInicio)
            && !empty($this->dataFinal)
            && $this->dataInicio->valido()
            && $this->dataFinal->valido()
        ) {
            $where[] = [
                'data_criacao', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
            ];
        } elseif (!empty($this->dataInicio) && $this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        } elseif (!empty($this->dataFinal) && $this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date()];
        }
        if (!empty($this->status) && $this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    protected function validarBusca(): void
    {
        if (!empty($this->busca)) {
            return;
        }
        $this->erroDownloadPadrao();
    }

    protected function montarRetornoDownload(): void
    {
        $i = 0;
        $retorno = [];
        foreach ($this->busca as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind === 'categoria_principal') {
                    $val = (new Categoria($val))->indice();
                }
                if ($ind === 'tipo_indicador') {
                    $val = (new Indicador($val))->indice();
                }
                if ($ind === 'status') {
                    $val = (new Status($val))->indice();
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        $this->busca = $retorno;
    }

    private function pegarUsuarioEquipe(): array
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_EQUIPE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['uuid', $this->usuario],
            ['id', 'permissao'],
            'object',
            'UUID não encontrado na base',
            'Usuário não encontrado'
        );
        return jsonDecode($usuario->permissao, true, true);
    }
}
