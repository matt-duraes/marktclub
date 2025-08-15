<?php

namespace App\Models\Api\ComercialSubempresa;

use App\Classes\ComercialEmpresa\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Cnpj;
use Modules\Nome;
use ORM\Entity;

class SubempresaEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string|array $empresa;
    public string $titulo;
    public string $razao_social;
    public string $nome_fantasia;
    public Nome $responsavel_nome;
    public Cnpj $cnpj;
    public Status $status;
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;
    protected array $ormBuscar = [
        'id_admin_empresa', 'titulo', 'razao_social',
        'nome_fantasia', 'cnpj', 'responsavel_nome', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'titulo', 'razao_social',
        'nome_fantasia', 'cnpj', 'responsavel_nome', 'status'
    ];
    protected string $ormValidarSalvar = '
        titulo|Titulo|obrigatorio|vazio
        razao_social|Razão Social|obrigatorio|vazio
        nome_fantasia|Nome Fantasia|obrigatorio|vazio
        responsavel_nome|Nome do Responsável|obrigatorio|vazio|valido
        cnpj|CNPJ|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected ?int $id_admin_empresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        $this->id_admin_empresa = $this->idEmpresa;
        if (!empty($this->empresa) && is_string($this->empresa)) {
            if (!validarUuid($this->empresa, false)) {
                mensagemErro(
                    'Campo inválido!',
                    'O campo empresa precisa ser valida.'
                );
            }
            $this->obterEmpresa();
        }
    }

    private function obterEmpresa(): void
    {
        $empresa = (new OrmHelper($this->ormTabela))
            ->pegarPrimeiroRegistro(
                ['cod', $this->empresa],
                ['id', 'responsavel_nome'],
                'object',
                'Empresa não encontrada ou inexistente',
                'Não encontrada!'
            );
        $this->id_admin_empresa = $empresa->id;
    }

    protected function regraPosBuscar(): void
    {
        $this->setarEmpresa();
    }

    private function setarEmpresa(): void
    {
        $empresa = (new OrmHelper($this->ormTabela))
            ->pegarPrimeiroRegistro(
                ['id', $this->id_admin_empresa],
                ['cod', 'nome_fantasia'],
                'object'
            );

        if (empty($empresa->cod)) {
            $this->empresa = [
                'id'            => '',
                'nome_fantasia' => ''
            ];
            return;
        }

        $this->empresa = [
            'id'            => $empresa->cod,
            'nome_fantasia' => $empresa->nome_fantasia
        ];
    }
}
