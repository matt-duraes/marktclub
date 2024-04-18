<?php

namespace App\Models\Api\ParceiroLoja\Auditoria;

use Modules\Data;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Auditoria;
use System\Classes\PainelHistorico\Acao;
use App\Models\Api\Trait\SistemaDataTrait;
use App\Models\Api\ParceiroLoja\LojaEntity;
use ApiModel\PainelHistorico\HistoricoEntity;

final class SalvarModel
{
    use SistemaDataTrait;

    public LojaEntity $Parceiro;
    public HistoricoEntity $Historico;

    public function __construct(
        private OptionModel $Option
    ) {
        $this->Parceiro = $this->Option->parceiro;
        $this->salvarAuditoria();
        $this->salvarHistorico();
        $this->sistemaData('Auditoria realizada', 'auditoria', $this->Parceiro->id, TABELA_PARCEIRO_LOJA);
    }

    private function salvarAuditoria()
    {
        $this->Parceiro->data_auditoria = new Data(hoje());
        if ($this->Option->auditoria->indice() != Auditoria::SEM_PROBLEMA) {
            $this->Option->parceiro->status = new Status(Status::PROBLEMA);
        }
        $this->Parceiro->salvar();
    }

    private function salvarHistorico()
    {
        $Historico = new HistoricoEntity();
        $Historico->acao = new Acao(Acao::OUTRO);
        $Historico->mensagem = $this->setarMensagem();
        $Historico->app = ['parceiro_loja'];
        $Historico->relacionado = [$this->Parceiro->id];
        $Historico->salvar();
        $this->Historico = $Historico;
    }

    private function setarMensagem()
    {
        return 'Auditoria realizada: ' . $this->Option->auditoria->nome() . PHP_EOL . $this->Option->mensagem;
    }
}
