<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\Entity;
use Modules\Cpf;
use Modules\Cnpj;
use Modules\Email;
use Modules\Telefone;
use App\Classes\ComercialEmpresa\Status;

final class EmpresaEntity extends Entity
{
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;
    protected array $ormBuscar = [
        'imagem' => 'imagem_arquivo',
        'titulo', 'cnpj', 'razao_social', 'nome_fantasia', 'slug', 'responsavel_nome', 'responsavel_cpf',
        'responsavel_email', 'responsavel_telefone', 'status'
    ];
    protected array $ormRetornoPadrao = ['id', 'nome_fantasia', 'imagem', 'slug', 'status'];

    public string $titulo;
    public Cnpj $cnpj;
    public string $razao_social;
    public string $nome_fantasia;
    public string $imagem;
    public string $slug;
    public Status $status;
    public string $responsavel_nome;
    public Cpf $responsavel_cpf;
    public Email $responsavel_email;
    public Telefone $responsavel_telefone;


    protected function regraPosBuscar()
    {
        if (empty($this->imagem)) {
            $this->imagem = arquivoPublico('empresa', 'padrao.png');
            return;
        }
        return $this->imagem;
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
