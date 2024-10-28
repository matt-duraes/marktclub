<?php

namespace App\Models\Api\View\Html;

use ORM\ORM;
use App\Models\Api\View\Pagina\HelperModel;

final class HtmlModel extends ORM
{
    protected string $ormTabela = TABELA_VIEW_HTML;
    private int $idPagina;
    private array $dado = [];
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
        if (existeErro($dado, '0')) {
            return;
        }
        $this->dado = $dado;
    }

    private function montarRetorno()
    {
        $retorno = [];
        foreach ($this->dado as $r) {
            $retorno[] = $r;
        }
        return $retorno;
    }
}
