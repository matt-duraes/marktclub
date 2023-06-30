<?php

namespace App\Models\Api\IndicacaoAutomovel;

use Erro\Erro;
use ORM\Entity;
use Erro\Excecao;
use Http\Request;
use Helpers\ValidarHelper;
use App\Classes\IndicacaoAutomovel\Status;

class IndicacaoAutomovelEntity extends Entity
{
    protected string $ormTabela = TABELA_MENSAGEM_CARRO_NOVO;

    protected array $ormSalvar = [
        'id_admin_empresa',  'id_usuario_cliente', 'produto', 'modelo', 'versao', 'cor', 'cidade', 'mensagem'
    ];

    public int $idUsuario;
    public int $idEmpresa;
    public string $produto;
    public string $modelo;
    public string $versao;
    public string $cor;
    public string $cidade;
    public string $mensagem;

    public function __construct(
        private readonly ?Request $request = null
    ) {

        parent::__construct();
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        $this->idUsuario =  1;

    }

    /**
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $this->validarRequest();
        $this->id_admin_empresa = $this->idEmpresa;
        $this->id_usuario_cliente = $this->idUsuario;
        $this->status = new Status(Status::CRIADA);
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $validarHelper = new ValidarHelper();

        $this->produto = $this->produto ?? '';
        $this->modelo = $this->modelo ?? '';
        $this->versao = $this->versao ?? '';
        $this->cor = $this->cor ?? '';
        $this->cidade = $this->cidade ?? '';
        $this->mensagem = $this->mensagem ?? '';

        $validarHelper
            ->valor($this->produto, 'Veículo', 'Digite o nome do veículo que deseja indicar.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->modelo, 'Modelo', 'Digite o modelo do veículo que deseja indicar.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->versao, 'Versão', 'Digite o versão do veículo que deseja indicar.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->cor, 'Cor', 'Digite o cor do veículo que deseja indicar.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->cidade, 'Cidade', 'Digite a cidade do veículo que deseja indicar.')
            ->obrigatorio()
            ->vazio()
            ->valor($this->mensagem, 'Mensagem', 'Digite uma mensagem.')
            ->obrigatorio()
            ->vazio();

    }


}
