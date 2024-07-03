<?php

namespace App\Models\Api\SiliumConfig;

use App\Classes\SiliumDeposito\TipoResgate;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\Botao;
use ORM\Entity;

class SiliumConfigEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SILIUM_CONFIG;
    protected array $ormBuscar = [
        'desconto', 'regra_conversao', 'pontuacao_minima_resgate',
        'validade_pontuacao', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa' => '->idEmpresa',
        'desconto', 'regra_conversao', 'pontuacao_minima_resgate',
        'validade_pontuacao'
    ];
    protected string $ormValidarSalvar = '
        validade_pontuacao|Prazo de Validade|int|obrigatorio|vazio
    ';

    public Botao $desconto;
    public array $regra_conversao;
    public array $pontuacao_minima_resgate;
    public string|int $validade_pontuacao;
    public string|int $pontuacao_dinheiro;
    public string|int $pontuacao_mensalidade;

    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    protected function regraSalvar(): void
    {
        $this->validarDados();
        $this->pontuacao_minima_resgate = [
            TipoResgate::DINHEIRO    => $this->pontuacao_dinheiro,
            TipoResgate::MENSALIDADE => $this->pontuacao_mensalidade
        ];
    }

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
}
