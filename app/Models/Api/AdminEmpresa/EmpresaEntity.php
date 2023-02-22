<?php

namespace App\Models\Api\AdminEmpresa;

use ORM\Entity;
use Modules\Cnpj;
use App\Classes\AdminEmpresa\Status;

final class EmpresaEntity extends Entity
{
    protected string $_tabela = TABELA_EMPRESA_NOVO;
    protected array $_buscar = [
        'imagem' => 'imagem_arquivo',
        'cnpj', 'razao_social', 'nome_fantasia', 'slug', 'status'
    ];
    protected array $_retornoPadrao = ['id', 'nome_fantasia', 'imagem', 'slug', 'status'];

    public Cnpj $cnpj;
    public string $razao_social;
    public string $nome_fantasia;
    public string $imagem;
    public string $slug;
    public Status $status;

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
