<?php

namespace App\Models\Api\Parceiro\Externo;

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use App\Models\Api\Download\DownloadGeralModel;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use System\Classes\Contato\Tipo;

class DownloadModel extends DownloadGeralModel
{
    public string $id_dono_equipe;
    public string $categoria_principal;
    public string $titulo_interno;
    public string $cancelar_motivo;
    public string $tipo_indicador;
    public string $data_cancelado;
    public string $data_criacao;
    public string $data_publicacao;
    public ?string $pesquisa = null;
    public ?string $empresa = null;
    public ?string $equipe = null;
    public ?Indicador $indicador = null;
    public ?Categoria $categoria = null;
    public ?array $estado = null;
    public ?Data $dataInicio = null;
    public ?Data $dataFinal = null;
    public ?Status $status = null;
    protected array $campoAceito = [
        'titulo_interno', 'data_criacao', 'data_publicacao', 'status', 'id_dono_equipe',
        'categoria_principal', 'data_cancelado', 'cancelar_motivo', 'tipo_indicador'
    ];

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
    ) {
        parent::__construct($request, TABELA_PARCEIRO_LOJA, 'parceiro-externo');
        $this->setarPropriedades();
        $this->validarRequest();
        $this->buscarRegistro();
        $this->validarDados();
        $this->salvarLogDownload();
        $this->montarRetornoDownload();
        $this->salvarArquivo();
    }

    private function setarPropriedades(): void
    {
        $this->campo = $this->request->campo ?? [];
        $this->pesquisa = $this->request->pesquisa ?? '';
        $this->empresa = $this->request->empresa ?? '';
        $this->equipe = $this->request->equipe ?? '';
        $this->categoria = new Categoria($this->request->categoria);
        $this->indicador = new Indicador($this->request->indicador);
        $this->estado = $this->request->estado ?? [];
        $this->dataInicio = new Data($this->request->data_inicio);
        $this->dataFinal = new Data($this->request->data_final);
        $this->status = new Status($this->request->status);
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->indicador->vazio() && !$this->indicador->valido()) {
            mensagemErro('Campo inválido!', 'O Indicador informado não é válido.');
        }
        if (!$this->categoria->vazio() && !$this->categoria->valido()) {
            mensagemErro('Campo inválido!', 'A Categoria informada não é válida.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @throws Excecao
     */
    protected function buscarRegistro(): void
    {
        $this->busca = $this
            ->tabela($this->ormTabela)
            ->campo($this->campo)
            ->where($this->pegarWhere(), false)
            ->tabela(TABELA_SISTEMA_CONTATO)
            ->join('id_vinculo', 'uuid')
            ->campo([
                'nome', 'tipo', 'valor'
            ], 'contato')
            ->read();
    }

    /**
     * @return array
     */
    public function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $this->pesquisa . '%'],
                ['subcategoria_tag', 'like', '%' . $this->pesquisa . '%'],
                ['titulo_interno', 'like', '%' . $this->pesquisa . '%']
            ];
        }
        /*if (!empty($this->equipe)) {
            $id_dono_equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($this->equipe);
            $where[] = ['id_dono_equipe', $id_dono_equipe];
        } else {
            $where[] = ['id_dono_equipe', '!=', 'null'];
        }*/
        if ($this->categoria->valido()) {
            $where[] = ['categoria_principal', $this->categoria->numero()];
        }
        if ($this->indicador->valido()) {
            $where[] = ['tipo_indicador', $this->indicador->numero()];
        }
        if (!empty($this->estado)) {
            $where[] = ['endereco_estado', 'json', $this->estado];
        }
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date()];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (empty($this->busca)) {
            mensagemErro(
                'Não encontrado registros',
                'Não há registros com essa filtragem',
                400
            );
        }
    }

    protected function montarRetornoDownload(): void
    {
        $i = 0;
        $retorno = [];
        foreach ($this->busca as $linha) {
            foreach ($linha as $ind => $val) {
                if ($ind === 'contato_tipo') {
                    $val = (new Tipo($val))->indice();
                }
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
}
