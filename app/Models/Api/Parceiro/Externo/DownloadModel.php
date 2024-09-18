<?php

namespace App\Models\Api\Parceiro\Externo;

use App\Classes\ParceiroLoja\CancelarMotivo;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\Trait\ValidarEmpresaDownloadTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Http\Request;
use Modules\Data;
use ORM\ORM;

class DownloadModel extends ORM
{
    use ValidarEmpresaDownloadTrait;

    public string|int $id;
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $campoAceito = [
        'titulo_interno', 'data_criacao', 'data_publicacao', 'status', 'id_dono_equipe',
        'categoria_principal', 'data_cancelado', 'cancelar_motivo', 'tipo_indicador'
    ];
    private mixed $dados;

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
    ) {
        $this->validarCampoAceito();
        parent::__construct();
        $this->validarEmpresa($this->request->usuario);
        $this->buscarRegistro();
        $this->validarDados();
        $this->salvarLogDownload();
        $this->montarRetornoDownload();
        $this->salvarArquivo();
    }

    /**
     * @throws Excecao
     */
    private function validarCampoAceito(): void
    {
        if (!$this->request->campo) {
            mensagemErro('Erro!', 'Você deve enviar pelo menos um campo.');
        }

        foreach ($this->request->campo as $item) {
            if (!in_array($item, $this->campoAceito)) {
                mensagemErro(
                    'Erro!',
                    'Um ou mais campos não tem permissão para serem buscados.',
                    status: 403,
                    localhost: 'O campo ' . $item . ' não está na lista de campos permitidos'
                );
            }
        }
    }

    /**
     * @throws Excecao
     */
    protected function buscarRegistro(): void
    {
        $this->dados = $this
            ->campo($this->request->campo)
            ->where($this->pegarWhere(), false)
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->where($this->pegarWhereEquipe(), false)
            ->join('id', 'id_dono_equipe')
            ->campo([
                'nome_real'
            ], 'equipe')
            /*->tabela(TABELA_SISTEMA_CONTATO)
            ->join('id_vinculo', 'uuid')
            ->campo([
                'nome', 'tipo', 'valor'
            ], 'contato')*/
            ->read();
    }

    /**
     * @return array
     */
    public function pegarWhere(): array
    {
        $where = [];

        $permissao = $this->pegarPermissoesUsuario($this->request->usuario);
        if (!in_array('parceiro_externo_empresa', $permissao)) {
            $where[] = ['id_dono_empresa', $this->idEmpresa];
        }
        if (!empty($this->request->pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $this->request->pesquisa . '%'],
                ['subcategoria_tag', 'like', '%' . $this->request->pesquisa . '%'],
                ['titulo_interno', 'like', '%' . $this->request->pesquisa . '%']
            ];
        }
        /*if (!empty($this->request->equipe)) {
            $id_dono_equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($this->request->equipe);
            $where[] = ['id_dono_equipe', $id_dono_equipe];
        } else {
            $where[] = ['id_dono_equipe', '!=', 'null'];
        }*/
        if ((new Categoria($this->request->categoria))->valido()) {
            $where[] = ['categoria_principal', (new Categoria($this->request->categoria))->numero()];
        }
        if ((new Indicador($this->request->indicador))->valido()) {
            $where[] = ['tipo_indicador', (new Indicador($this->request->indicador))->numero()];
        }
        if (!empty($this->request->estado)) {
            $where[] = ['endereco_estado', 'json', $this->request->estado];
        }
        if ((new Data($this->request->data_inicio))->valido() && (new Data($this->request->data_final))->valido()) {
            $where[] = [
                'data_criacao', 'between', [
                    (new Data($this->request->data_inicio))->date(), (new Data($this->request->data_final))->date()
                ]
            ];
        } elseif ((new Data($this->request->data_inicio))->valido()) {
            $where[] = ['data_criacao', '>=', (new Data($this->request->data_inicio))->date()];
        } elseif ((new Data($this->request->data_final))->valido()) {
            $where[] = ['data_criacao', '<=', (new Data($this->request->data_final))->date()];
        }
        if ((new Status($this->request->status))->valido()) {
            $where[] = ['status', (new Status($this->request->status))->numero()];
        }
        return $where;
    }

    /**
     * @param  string $usuario
     * @return array
     */
    private function pegarPermissoesUsuario(string $usuario): array
    {
        $ormHelper = new OrmHelper(TABELA_USUARIO_EQUIPE);
        $permissoes = $ormHelper->pegarCampoPor('permissao', ['uuid', $usuario], []);
        return jsonDecode($permissoes, true, true);
    }

    /**
     * @return array
     */
    protected function pegarWhereEquipe(): array
    {
        $where = [];
        if (!empty($this->request->equipe)) {
            $where[] = ['uuid', $this->request->equipe];
        }
        return $where;
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (empty($this->dados)) {
            mensagemErro(
                'Não encontrado registros',
                'Não há registros com essa filtragem',
                400
            );
        }
    }

    /**
     * @throws Excecao
     */
    private function salvarLogDownload(): void
    {
        $Log = new LogDownloadEntity(
            app: 'parceiro_externo',
            request: $this->request->dado(),
            quantidade: count($this->dados),
            usuario: $this->request->usuario
        );
        $Log->salvar();
    }

    /**
     */
    protected function montarRetornoDownload(): void
    {
        $i = 0;
        $retorno = [];
        foreach ($this->dados as $linha) {
            foreach ($linha as $ind => $val) {
                /*if ($ind == 'contato_tipo') {
                    $val = (new Tipo($val))->indice();
                }*/
                if ($ind == 'id_dono_equipe') {
                    continue;
                }
                if ($ind == 'cancelar_motivo') {
                    $val = (new CancelarMotivo($val))->nome();
                }
                if ($ind == 'categoria_principal') {
                    $val = (new Categoria($val))->nome();
                }
                if ($ind == 'tipo_indicador') {
                    $val = (new Indicador($val))->nome();
                }
                if ($ind == 'status') {
                    $val = (new Status($val))->nome();
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        $this->dados = $retorno;
    }

    /**
     * @throws Excecao
     */
    private function salvarArquivo(): void
    {
        $Download = new ArquivoEntity($this->dados, $this->request->usuario);
        $Download->salvar();
        $this->id = $Download->id;
    }
}
