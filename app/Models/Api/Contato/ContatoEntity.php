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
use App\Models\Api\Contato\Trait\ConstrutorTrait;

class ContatoEntity extends Entity
{
    use ConstrutorTrait;

    protected string $ormTabela = TABELA_MENSAGEM_CONTATO_NOVO;


    public Nome $nome;
    public Email $email;
    public Telefone $telefone;
    public string $mensagem;
    public ?int $descoberta_site = null;
    public string $url;
    public int $idEmpresa;
    public Status $status;

    protected array $ormBuscar = [
        'id_admin_empresa', 'nome', 'email', 'telefone', 'mensagem',  'descoberta_site'
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'nome', 'email', 'telefone', 'mensagem', 'url', 'descoberta_site'
    ];

    public function __construct(
        protected readonly ?Request $request = null
    ) {
        parent::__construct();

    }

    /**
     * @throws Excecao
     */
    public function regraInsert(): void
    {
        $this->buscarIdEmpresa();

        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(Status::CRIADA);
        unset($this->url);
    }

}
