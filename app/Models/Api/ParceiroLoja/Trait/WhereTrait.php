<?php

namespace App\Models\Api\ParceiroLoja\Trait;

use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Api\ParceiroLoja\MaisAcessadoModel;
use Helpers\OrmHelper;
use Where\Where;

trait WhereTrait
{
    protected function pegarWhere(): Where
    {
        $where = $this->idEmpresa == 1 ? [] : [
            ['id_admin_empresa', 'json', $this->idEmpresa]
        ];

        $Where = new Where($this, $where);
        $Where
            ->seValido(propriedade: 'categoria', callback: function () use ($Where) {
                $Where->manual([
                    'OR',
                    ['categoria_principal', $this->categoria->numero()],
                    ['categoria_lista', 'json', $this->categoria->numero()]
                ]);
            })
            ->seInArray('convenio_direto', lista: ['sim', 'nao'], callback: function () use ($Where) {
                $direto = $this->convenio_direto;
                if ($direto == 'sim') {
                    $Where->manual(['convenio_direto', 1]);
                } elseif ($direto == 'nao') {
                    $Where->manual(['convenio_direto', 'null']);
                }
            })
            ->seVazio(propriedade: 'subcategoria', vazio: false, callback: function () use ($Where) {
                $tag = $this->pegarIdSubCategoria();
                $Where->linha(propriedade: 'subcategoria_lista', condicao: 'json', valor: $tag);
            })
            ->seVazio(propriedade: 'pesquisa', vazio: false, callback: function () use ($Where) {
                $pesquisa = '%' . $this->pesquisa . '%';
                $Where->manual([
                    'OR',
                    ['titulo', 'like', $pesquisa],
                    ['subcategoria_tag', 'like', $pesquisa]
                ]);
            })
            ->seVazio(propriedade: 'titulo', vazio: false, callback: function () use ($Where) {
                $titulo = '%' . $this->titulo . '%';
                $Where->manual([
                    'OR',
                    ['titulo', 'like', $titulo],
                    ['titulo_interno', 'like', $titulo]
                ]);
            })
            ->linha('equipe', campo: 'id_usuario_equipe', valor: $this->pegarIdEquipe())
            ->linha('tipo_estabelecimento')
            ->seBotao('mais_acessao', callback: function () use ($Where) {
                $this->idMaisAcessado = (new MaisAcessadoModel($this->idEmpresa, $this->pegarQuantidade()))->id;
                $Where->linha(propriedade: 'id', condicao: 'in', valor: $this->idMaisAcessado);
            })
            ->seIgual('convenio', 'sim', function () use ($Where) {
                if (!$this->pExiste('tipo_loja') || !$this->tipo_loja->valido()) {
                    $Where->manual(['tipo_loja', '!=', new TipoLoja(TipoLoja::CASHBACK)]);
                }
            })
            ->linha(propriedade: 'tipo_loja')
            ->linha('endereco_estado', 'json')
            ->seInArray('ordem', ['painel-asc', 'painel-desc'], function () use ($Where) {
                if (!$this->pExiste('status') || (!$this->status->valido() && $this->status->real() != 'todos')) {
                    $Where->manual(['status', 'in', [1, 2]]);
                }
            })
            ->dataDeAte('data_criacao')
            ->dataDeAte('data_publicacao')
            ->dataDeAte('data_prospeccao')
            ->dataDeAte('data_problema')
            ->dataDeAte('data_cancelado')
            ->dataDeAte('data_auditoria')
            ->linha('status');
        return $Where;
    }

    private function pegarIdEquipe()
    {
        if (!$this->pExiste('equipe') || empty($this->equipe)) {
            return null;
        }
        return (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid($this->equipe);
    }

    private function pegarIdSubCategoria()
    {
        $tag = $this->subcategoria;
        if (empty($tag)) {
            return '';
        }
        return (new OrmHelper(TABELA_PARCEIRO_SUBCATEGORIA))->pegarCampoPor('id', ['url', $tag]);
    }
}
