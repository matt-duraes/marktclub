<?php

namespace App\Models\Api\Contato;

use App\Classes\Contato\Status;
use Erro\Erro;
use Erro\Excecao;
use Http\Request;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

class ContatoEntity extends Entity
{
    protected string $ormTabela = TABELA_MENSAGEM_CONTATO_NOVO;
    protected array $ormInsert = [
        'status'
    ];
    protected array $ormSalvar = [
        'nome', 'email', 'telefone', 'mensagem', 'url', 'descoberta_site'
    ];
    protected string $ormValidarInsert = '
        nome|Nome|obrigatorio|valido
        mensagem|Mensagem|obrigatorio|valido
        email|E-mail|obrigatorio|valido
        telefone|Telefone|obrigatorio|valido
    ';
    protected Nome $nome;
    protected Email $email;
    protected Telefone $telefone;
    protected string $mensagem;
    protected string $url;
    protected string $descoberta_site;
    protected string $id_admin_empresa;
    protected Status $status;

    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_site', $this->request->url],
            ['status', 1]
        ]);

        $this->id_admin_empresa = $Construtor->id_admin_empresa;

        $this->status = new Status(Status::CRIADA);
    }

}
