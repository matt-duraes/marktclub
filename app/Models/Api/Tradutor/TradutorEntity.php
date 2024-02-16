<?php

namespace App\Models\Api\Tradutor;

use App\Classes\Geral\Status;
use ORM\Entity;

class TradutorEntity extends Entity
{
    public string $termo;
    public string $traducao_en;
    public string $traducao_es;
    public array|string $traducao;
    public Status $status;
    protected string $ormTabela = TABELA_PAINEL_TRADUTOR;
    protected array $ormBuscar = [
        'termo', 'traducao', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'termo', 'traducao', 'status'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $traducao = jsonDecode($this->traducao, true, true);
        $this->traducao_en = $traducao['en'];
        $this->traducao_es = $traducao['es'];
    }

    protected function regraSalvar(): void
    {
        $traducoes['en'] = $this->traducao_en;
        $traducoes['es'] = $this->traducao_es;
        $this->traducao = jsonEncode($traducoes);
        $this->status = new Status(Status::ATIVO);
    }
}
