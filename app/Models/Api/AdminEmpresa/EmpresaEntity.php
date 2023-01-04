<?php

namespace App\Models\Api\AdminEmpresa;

use ORM\Entity;
use Modules\Cnpj;

final class EmpresaEntity extends Entity
{
    protected string $_tabela = TABELA_EMPRESA_NOVO;
    protected array $_buscar = [
        'razao_social',
        'nome_fantasia',
        'imagem' => 'imagem_arquivo',
        '!cnpj' => 'cnpj',
    ];

    public Cnpj $cnpj;
    public string $razao_social;
    public string $nome_fantasia;
    public string $imagem;

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
