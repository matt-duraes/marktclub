<?php

namespace App\Models\Api\Saude\Simulacao;

use ORM\ORM;
use App\Classes\SaudeSimulacao\Status;
use App\Models\Api\Auth\Token\TokenHelper;
use App\Models\Api\Saude\Convenio\IdModel;

final class SalvarModel extends ORM
{
    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;

    public array $retorno = [];

    public function __construct(
        SimularModel $Simulacao,
        string $convenio,
        array $simulacao
    )
    {
        parent::__construct();

        $Token = new TokenHelper();
        $idConvenio = (new IdModel())->pegarIdPelaUrl($convenio);
        $salvar = $this->dado([
            'id_admin_empresa' => $Token->pegarEmpresa(),
            'id_usuario_cliente' => $Token->pegarUsuario(),
            'id_saude_convenio' => $idConvenio,
            'valor_titular' => $Simulacao->valorTitular,
            'valor_dependente' => $Simulacao->valorDependente,
            'valor_total' => $Simulacao->valorTotal,
            'simulacao' => $simulacao,
            'status' => new Status(Status::NOVO)
        ])->insert();

        if(!validarIndiceExiste($salvar, 'id')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar sua simulação, por favor, tente novamente.');
        }
        $this->retorno = [
            'id' => $salvar['uuid']
        ];
    }
}
