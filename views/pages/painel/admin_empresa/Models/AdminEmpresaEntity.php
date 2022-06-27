<?php

namespace Painel\AdminEmpresa\Models;

use stdClass;
use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Cnpj;
use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use App\Models\Painel\AppGeral\AppGeralEntity;

final class AdminEmpresaEntity extends AppGeralEntity
{
    protected string $_tabela = TABELA_ADMIN_EMPRESA;

    protected array $_salvar = [
        'razao_social',
        'nome_fantasia',
        'documento_cnpj',
        'responsavel_nome',
        'responsavel_cpf',
        'responsavel_email',
        'responsavel_telefone',
        'endereco_estado',
        'endereco_cidade',
        'imagem_arquivo',
        'item_contratado' => '->item',
        'status',
        'tag'
    ];
    protected array $_buscar = [
        'razao_social',
        'nome_fantasia',
        'documento_cnpj',
        'responsavel_nome',
        'responsavel_cpf',
        'responsavel_email',
        'responsavel_telefone',
        'imagem_arquivo',
        'endereco_estado',
        'endereco_cidade',
        'item' => 'item_contratado',
        'status',
        'tag'
    ];

    public Cnpj $documento_cnpj;
    public Nome $responsavel_nome;
    public Cpf $responsavel_cpf;
    public Email $responsavel_email;
    public Telefone $responsavel_telefone;

    public function dadoEditar(): stdClass
    {
        $tag = $this->prop('tag');
        return (object) [
            'id' => $this->id,
            'razao_social' => $this->prop('razao_social'),
            'nome_fantasia' => $this->prop('nome_fantasia'),
            'documento_cnpj' => $this->get('documento_cnpj')->cnpj(),
            'responsavel_nome' => $this->prop('responsavel_nome'),
            'responsavel_cpf' => $this->get('responsavel_cpf')->cpf(),
            'responsavel_email' => $this->prop('responsavel_email'),
            'responsavel_telefone' => $this->get('responsavel_telefone')->telefone(),
            'endereco_estado' => $this->prop('endereco_estado'),
            'endereco_cidade' => $this->prop('endereco_cidade'),
            'imagem_arquivo' => $this->prop('imagem_arquivo'),
            'status' => $this->prop('status'),
            'item' => jsonDecode($this->prop('item_contratado'), true),
            'tag' => jsonDecode($tag, true)
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | REGRA DE NEGÓCIO
    |--------------------------------------------------------------------------
    */
    protected function regraSalvar()
    {
        $this->CnpjJaExiste();
        $this->cpfJaExiste();
        $this->emailjaExiste();
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÃO
    |--------------------------------------------------------------------------
    */
    private function CnpjJaExiste(): void
    {
        $where = [['documento_cnpj', preg_replace('/[^0-9]/', '', $this->documento_cnpj->cnpj())]];
        if ($this->id) {
            $where[] = ['uuid', '!=', $this->id];
        }
        if ($this->existe($where)) {
            throw new Excecao('CNPJ duplicado', 'O Cnpj informado já está em uso por outro cliente.');
        }
    }

    private function cpfJaExiste()
    {
        $where = [['responsavel_cpf', preg_replace('/[^0-9]/', '', $this->responsavel_cpf->cpf())]];
        if ($this->id) {
            $where[] = ['uuid', '!=', $this->id];
        }
        if ($this->existe($where)) {
            throw new Excecao('CPF duplicado', 'O CPF informado já está em uso por outro cliente.');
        }
    }

    private function emailjaExiste()
    {
        $where = [['responsavel_email', $this->responsavel_email]];
        if ($this->id) {
            $where[] = ['uuid', '!=', $this->id];
        }
        if ($this->existe($where)) {
            throw new Excecao('E-mail duplicado', 'O e-mail informado já está em uso por outro cliente.');
        }
    }
}
