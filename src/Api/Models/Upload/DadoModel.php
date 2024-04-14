<?php

namespace ApiModel\Upload;

use ORM\ORM;

final class DadoModel extends ORM
{
    protected string $ormTabela = TABELA_UPLOAD_ARQUIVO;
    public array $arquivo = [];
    private array $busca = [];

    public function __construct(
        private array $id = []
    ) {
        parent::__construct();
        if (empty($id)) {
            return;
        }
        $this->converterId();
        $this->buscarArquivo();
        $this->montarArquivo();
    }

    private function converterId()
    {
        $retorno = [];
        foreach ($this->id as $id) {
            $retorno[] = arquivoPrivadoId($id);
        }
        $this->id = $retorno;
    }

    private function buscarArquivo()
    {
        $this->busca = $this
            ->campo([
                'uuid', 'nome', 'arquivo'
            ])
            ->where(['uuid', 'in', $this->id])
            ->order('nome', 'ASC')
            ->read();
    }

    private function montarArquivo()
    {
        $retorno = [];
        foreach ($this->busca as $r) {
            $retorno[] = (object)[
                'id'       => $r->uuid,
                'nome'     => $r->nome,
                'arquivo'  => arquivoPrivado($r->uuid)
            ];
        }
        $this->arquivo = $retorno;
    }
}
