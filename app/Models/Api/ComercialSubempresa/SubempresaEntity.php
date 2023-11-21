<?php

namespace App\Models\Api\ComercialSubempresa;

use App\Classes\ComercialSubempresa\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Cnpj;
use ORM\Entity;

final class SubempresaEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string|array $empresa;
    public string $nome;
    public Cnpj $documento_cnpj;
    public Status $status;
    protected string $ormTabela = TABELA_COMERCIAL_SUBEMPRESA;
    protected array $ormBuscar = [
        'id_admin_empresa', 'nome', 'documento_cnpj', 'status'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'nome', 'documento_cnpj', 'status'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio|valido
        documento_cnpj|CNPJ|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected ?int $id_admin_empresa;
    //protected ?int $idEmpresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        //$this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->validarDados();
        $this->obterEmpresa();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (is_string($this->empresa) && empty($this->empresa)) {
            mensagemErro(
                'Campo obrigatório!',
                'O campo empresa é obrigatório.'
            );
        } elseif (!validarUuid($this->empresa, false)) {
            mensagemErro(
                'Campo inválido!',
                'O campo empresa precisa ser valida.'
            );
        }
    }

    private function obterEmpresa(): void
    {
        $this->id_admin_empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarIdPeloUuid(
                $this->empresa,
                'Empresa não encontrada ou inexistente',
                'Não encontrado!'
            );
    }

    protected function regraPosBuscar(): void
    {
        $this->setarEmpresa();
    }

    private function setarEmpresa(): void
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarPrimeiroRegistro(
                ['id', $this->id_admin_empresa],
                ['cod', 'titulo'],
                'object'
            );

        if (empty($empresa->cod)) {
            $this->empresa = [
                'cod'  => '',
                'nome' => 'Sem empresa'
            ];
            return;
        }

        $this->empresa = [
            'cod'  => $empresa->cod,
            'nome' => $empresa->titulo
        ];
    }
}
