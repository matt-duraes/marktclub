<?php

namespace App\Models\Api\View\Html;

use App\Models\Api\View\Pagina\HelperModel;

final class HtmlModel extends RetornoModel
{
    protected string $ormTabela = TABELA_VIEW_HTML;
    private int $idPagina;
    protected array $dado = [];
    public array $retorno = [];

    public function __construct(
        private string $pagina
    ) {
        parent::__construct();
        $this->pegarIdPagina();
        $this->buscarLista();
        $this->montarRetorno();
    }

    private function pegarIdPagina()
    {
        $Orm = new HelperModel();
        $id = $Orm->pegarIdPeloUuid($this->pagina);
        if (empty($id)) {
            mensagemErro('Erro!', 'Não foi encontrado página pelo ID informado.');
        }
        $this->idPagina = $id;
    }

    private function buscarLista()
    {
        $dado = $this
            ->where([
                ['id_view_pagina', $this->idPagina]
            ])
            ->order('ordem', 'ASC')
            ->read();

        if (!array_key_exists(0, $dado)) {
            return;
        }
        $this->dado = $dado;
    }
}
