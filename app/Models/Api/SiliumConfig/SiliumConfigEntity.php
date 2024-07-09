<?php

namespace App\Models\Api\SiliumConfig;

use App\Classes\SiliumDeposito\TipoResgate;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\Entity;

class SiliumConfigEntity extends Entity
{
    public string $empresa;
    public string $desconto;
    public array $regra_conversao;
    public array $pontuacao_minima_resgate;
    public string|int $validade_pontuacao;
    public string|int $pontuacao_dinheiro;
    public string|int $pontuacao_mensalidade;
    protected string $ormTabela = TABELA_SILIUM_CONFIG;
    protected array $ormBuscar = [
        'id_admin_empresa', 'desconto', 'regra_conversao',
        'pontuacao_minima_resgate', 'validade_pontuacao', 'data_criacao',
        'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa' => '->idEmpresa',
        'desconto', 'regra_conversao', 'pontuacao_minima_resgate',
        'validade_pontuacao'
    ];
    protected int $id_admin_empresa;
    protected int $idEmpresa;

    public string $empresa;
    public Botao $desconto;
    public array $regra_conversao;
    public array $pontuacao_minima_resgate;
    public string|int $validade_pontuacao;
    public string|int $pontuacao_dinheiro;
    public string|int $pontuacao_mensalidade;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraPosBuscar(): void
    {
        $this->pegarEmpresa();
        $this->pontuacao_dinheiro = $this->pontuacao_minima_resgate[TipoResgate::DINHEIRO];
        $this->pontuacao_mensalidade = $this->pontuacao_minima_resgate[TipoResgate::MENSALIDADE];
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function pegarEmpresa(): void
    {
        $OrmHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $uuidEmpresa = $OrmHelper->pegarUuidPeloId($this->id_admin_empresa);

        if (empty($uuidEmpresa)) {
            mensagemErro(
                'Campo inválido!',
                'Não foi possível vincular a empresa selecionada.',
                localhost: 'Não achou há empresa no banco'
            );
        }
        $this->empresa = $uuidEmpresa;
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        $this->validarDados();
        $this->setarEmpresa();
        $this->pontuacao_minima_resgate = [
            TipoResgate::DINHEIRO    => $this->pontuacao_dinheiro,
            TipoResgate::MENSALIDADE => $this->pontuacao_mensalidade
        ];
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!filter_var($this->pontuacao_dinheiro, FILTER_VALIDATE_INT)) {
            mensagemErro(
                'Campo inválido!',
                'A Pontuação mínima de Dinheiro informada não é válida.',
                localhost: 'Pontuação não é do tipo Integer'
            );
        }
        if (!filter_var($this->pontuacao_mensalidade, FILTER_VALIDATE_INT)) {
            mensagemErro(
                'Campo inválido!',
                'A Pontuação mínima de Mensalidade informada não é válida.',
                localhost: 'Pontuação não é do tipo Integer'
            );
        }
        if (!filter_var($this->validade_pontuacao, FILTER_VALIDATE_INT)) {
            mensagemErro(
                'Campo inválido!',
                'O Prazo de validade informado não é válido.',
                localhost: 'Pontuação não é do tipo Integer'
            );
        }
        if (($this->pontuacao_dinheiro < 0) || ($this->pontuacao_mensalidade < 0)) {
            mensagemErro(
                'Campo inválido!',
                'A Pontuação não pode ser negativa.',
                localhost: 'Número menor que 0'
            );
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function setarEmpresa(): void
    {
        $OrmHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $idEmpresa = $OrmHelper->pegarIdPeloUuid($this->empresa);

        if (empty($idEmpresa)) {
            mensagemErro(
                'Campo inválido!',
                'Não foi possível vincular a empresa selecionada.',
                localhost: 'Não achou há empresa no banco'
            );
        }
        $this->idEmpresa = $idEmpresa;
    }
}
