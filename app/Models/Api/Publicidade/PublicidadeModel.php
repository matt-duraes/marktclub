<?php

namespace App\Models\Api\Publicidade;

use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\Status as StatusLoja;
use App\Classes\Publicidade\Tipo;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class PublicidadeModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICIDADE;
    private ?int $idEmpresa;

    public function __construct(
        protected readonly Request $request
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return object
     * @throws Excecao
     */
    public function listarDados(): object
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'imagem', 'target',
                'link', 'tipo', 'status', 'data_criacao'
            ], 'publicidade')
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->campo([
                'imagem', 'url'
            ], 'parceiro')
            ->join('id', 'id_admin_empresa')
            ->where([
                //['empresa', 'LIKE', '%"' . $this->idEmpresa . '"%'],
                ['status', (new StatusLoja(StatusLoja::CONCLUIDO))->numero()]
            ])
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        $Tipo = new Tipo($this->request->tipo);
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $Status = new Status($this->request->status);
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $dataCriacaoDe = $this->request->data_criacao_de;
        $dataCriacaoAte = $this->request->data_criacao_ate;

        if (validarDataDate($dataCriacaoDe) && validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', 'between', [$dataCriacaoDe, $dataCriacaoAte]];
        } elseif (validarDataDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        } elseif (validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
        }

        return $where;
    }

    /**
     * @param array $publicidades
     *
     * @return array
     */
    protected function montarRetorno(array $publicidades): array
    {
        if (empty($publicidades)) {
            return $publicidades;
        }

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['empresa', $this->idEmpresa]
        ]);

        $Tipo = new Tipo();
        $Status = new Status();

        $retorno = [];
        foreach ($publicidades as $publicidade) {
            $retorno[] = [
                'uuid'         => $publicidade->publicidade_uuid,
                'titulo'       => $publicidade->publicidade_titulo,
                'target'       => ($publicidade->publicidade_target === '_self') ? 'interno' : 'externo',
                'imagem'       => $publicidade->publicidade_imagem,
                'parceiro'     => [
                    'imagem' => $publicidade->parceiro_imagem,
                    'url'    => $publicidade->parceiro_url
                ],
                'link'         => [
                    'link'   => str_replace(
                        'clube.marktclub.com.br',
                        $Construtor->link_clube,
                        $publicidade->publicidade_link
                    ),
                    'target' => $publicidade->publicidade_target
                ],
                'url'          => [
                    'link'   => str_replace(
                        'clube.marktclub.com.br',
                        $Construtor->link_clube,
                        $publicidade->publicidade_link
                    ),
                    'target' => $publicidade->publicidade_target
                ],
                'tipo'         => $Tipo->indice($publicidade->publicidade_tipo),
                'status'       => $Status->indice($publicidade->publicidade_status),
                'data_criacao' => dataHoraBr($publicidade->publicidade_data_criacao)
            ];
        }
        return $retorno;
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $dataCriacaoDe = new Data($this->request->data_criacao_de);
        if (!$dataCriacaoDe->vazio() && (!$dataCriacaoDe->valido() || !$dataCriacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        $dataCriacaoAte = new Data($this->request->data_criacao_ate);
        if (!$dataCriacaoAte->vazio() && (!$dataCriacaoAte->valido() || !$dataCriacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
        }
        $Tipo = new Tipo($this->request->tipo);
        if (!$Tipo->vazio() && !$Tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
        }
        $Status = new Status($this->request->status);
        if (!$Status->vazio() && !$Status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }
}
