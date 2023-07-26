<?php

namespace App\Models\Api\Automovel\Modelo;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Http\Request;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use App\Classes\StatusGeral\Status;
use System\Trait\Model\PaginaTrait;
use App\Classes\Automovel\Modelo\Ordem;
use System\Trait\Model\QuantidadeTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\ParceiroLoja\Status as StatusParceiro;

final class ModeloModel extends ORM
{
    use ValidarEmpresaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use PaginaTrait;

    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;
    private int $idEmpresa;
    private stdClass $dadoParceiro;

    /**
     * @param  Request|null $request
     * @throws Excecao
     */
    public function __construct(
        private Pagina $pagina,
        private Quantidade $quantidade = new Quantidade(20),
        private ?string $parceiro = null,
        private Status $status = new Status(null),
        private Ordem $ordem = new Ordem(null)
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->validarCampos();
        $this->pegarParceiro();
    }

    private function validarCampos()
    {
        if ($this->pagina->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo pagina é obrigatório.');
        } elseif (!$this->pagina->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo pagina não é valido.');
        } elseif (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo quantidade não é valido.');
        }
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'imagem', 'url', 'status', 'data_criacao'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade());

        if (vazio($this->dadoParceiro)) {
            $dado
                ->tabela(TABELA_PARCEIRO_LOJA)
                ->campo(['titulo', 'status'], 'parceiro')
                ->join('id', 'id_parceiro_loja')
                ->order('titulo');
        }

        $dado = $dado->read();
        $dado->lista = $this->montarRetorno($dado->lista);

        return $dado;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = [];
        if (!vazio($this->dadoParceiro)) {
            $where[] = ['id_parceiro_loja', $this->dadoParceiro->id];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function pegarParceiro()
    {
        if (empty($this->parceiro)) {
            $this->dadoParceiro = (object)[];
            return;
        }
        $this->dadoParceiro = (new OrmHelper(TABELA_PARCEIRO_LOJA))
            ->pegarUltimoRegistro(
                campo: ['id', 'status'],
                where: [
                    ['id_admin_empresa', 'json', '"' . $this->idEmpresa . '"'],
                    [
                        'OR',
                        ['uuid', $this->parceiro],
                        ['url', $this->parceiro]
                    ]
                ],
                retorno: 'object',
                erroMensagem: 'Não foi encontrado um parceiro pelo código'
            );
    }

    /**
     * @param  array $dado
     * @return array
     */
    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        $parceiroAtivo = false;
        if (!vazio($this->dadoParceiro)) {
            $parceiroAtivo = (new StatusParceiro())->indice($this->dadoParceiro->status) == StatusParceiro::CONCLUIDO;
        }
        foreach ($dado as $r) {
            $dado = [
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'link_logo' => arquivoPrivado($r->imagem),
                'url'       => $r->url
            ];
            if (object_key_exists('parceiro_titulo', $r)) {
                $dado['parceiro'] = $r->parceiro_titulo;
                $dado['status'] = (new StatusParceiro())->indice($r->parceiro_status) == StatusParceiro::CONCLUIDO
                    ? $Status->indice($r->status) : Status::INATIVO;
            } else {
                $dado['status'] = $parceiroAtivo ? $Status->indice($r->status) : Status::INATIVO;
            }
            $retorno[] = (object)$dado;
        }
        return $retorno;
    }
}
