<?php

namespace App\Models\Api\Pesquisa;

use App\Classes\Pesquisa\Experiencia;
use App\Classes\Pesquisa\Fidelidade;
use App\Classes\Pesquisa\Frequencia;
use App\Classes\Pesquisa\Gasto;
use App\Classes\Pesquisa\Importancia;
use App\Classes\Pesquisa\Padrao;
use App\Classes\Pesquisa\Produtos;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use ORM\Entity;

class PesquisaEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Fidelidade $fidelidade;
    public Produtos $produtos;
    public Gasto $gasto;
    public Importancia $importancia;
    public Padrao $cashback;
    public Frequencia $frequencia;
    public Padrao $resgate;
    public Padrao $desconto;
    public Experiencia $experiencia;
    public Padrao $indicaria;
    protected string $ormTabela = TABELA_PESQUISA;
    protected array $ormBuscar = [
        'fidelidade', 'produtos', 'gasto', 'importancia', 'cashback',
        'frequencia', 'resgate', 'desconto', 'experiencia', 'indicaria',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario',
        'fidelidade', 'produtos', 'gasto', 'importancia', 'cashback',
        'frequencia', 'resgate', 'desconto', 'experiencia', 'indicaria'
    ];
    protected string $ormValidarSalvar = '
        fidelidade|Pergunta 1|obrigatorio|vazio|valido
        produtos|Pergunta 2|obrigatorio|vazio|valido
        gasto|Pergunta 3|obrigatorio|vazio|valido
        importancia|Pergunta 4|obrigatorio|vazio|valido
        cashback|Pergunta 5|obrigatorio|vazio|valido
        frequencia|Pergunta 6|obrigatorio|vazio|valido
        resgate|Pergunta 7|obrigatorio|vazio|valido
        desconto|Pergunta 8|obrigatorio|vazio|valido
        experiencia|Pergunta 9|obrigatorio|vazio|valido
        indicaria|Pergunta 10|obrigatorio|vazio|valido
    ';
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->setarIdEmpresa();
        $this->setarIdUsuario();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        if (in_array($this->idEmpresa, ['223', '1982'])) {
            mensagemErro(
                'Desculpe a indisponibilidade desta pesquisa!',
                'Você não pode responder a esta pesquisa.'
            );
        }
        if ($this->existeResposta()) {
            mensagemErro(
                'Sua resposta não foi salva!',
                'Você já respondeu a esta pesquisa.'
            );
        }
    }

    /**
     * @return bool
     * @throws Excecao
     */
    private function existeResposta(): bool
    {
        return $this->existe(['id_usuario_cliente', $this->idUsuario]);
    }
}
