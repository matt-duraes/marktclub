<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Classes\SolicitacaoLoja\Status;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Erro;
use Erro\Excecao;
use Helpers\EmailHelper;
use Helpers\OrmHelper;
use Modules\Cpf;
use Modules\Email;
use Modules\Telefone;
use ORM\Entity;
use SendGrid\Mail\TypeException;

final class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Cpf $cpf;
    public string $nome;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    public string $mensagem;
    public string $usuario;
    public array $quem_indicou;
    public array $origem_clube;
    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'nome', 'telefone', 'email', 'mensagem',
        'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario_cliente', 'nome', 'telefone', 'email', 'mensagem'
    ];
    protected array $ormSalvar = [
        'status'
    ];
    protected string $ormValidarSalvar = '
        nome|Nome|obrigatorio|vazio
        mensagem|Mensagem|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected string|int|null $id_admin_empresa;
    protected string|int|null $id_usuario_cliente;
    private int $idEmpresa;
    private ?int $idUsuario = null;

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
     * @throws Excecao|Erro
     */
    protected function regraPosBuscar(): void
    {
        $this->setarOrigemClube();
        $this->setarQuemIndicou();
    }

    private function setarOrigemClube(): void
    {
        $ormHelper = new OrmHelper(TABELA_CONSTRUTOR_CLUBE);
        $clube = $ormHelper->pegarUltimoRegistro(
            ['id_admin_empresa', $this->id_admin_empresa],
            ['id', 'titulo'],
            'object'
        );

        if (empty($clube->id)) {
            return;
        }

        $this->origem_clube = [
            'titulo' => $clube->titulo
        ];
    }

    /**
     * @throws Excecao|Erro
     */
    private function setarQuemIndicou(): void
    {
        $Usuario = new ClienteEntity(validarToken: false);
        $Usuario->buscar([
            ['id', $this->prop('id_usuario_cliente')],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);

        if (empty($Usuario->id)) {
            return;
        }

        $this->quem_indicou = [
            'id'    => $Usuario->id,
            'nome'  => $Usuario->nome->nome(),
            'cpf'   => $Usuario->cpf->cpf(),
            'email' => $Usuario->email->email()
        ];
    }

    protected function regraInsert(): void
    {
        $ormHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        if ($this->propriedadeExiste('usuario') && !empty($this->usuario)) {
            $this->id_usuario_cliente = $ormHelper->pegarIdPeloUuid($this->usuario);
        } elseif ($this->propriedadeExiste('cpf') && $this->cpf->valido()) {
            $this->id_usuario_cliente = $ormHelper->pegarCampoPor('id', ['documento', $this->cpf->numero()]);
        }
        $this->status = new Status(Status::NOVO);
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    protected function regraPosUpdate(): void
    {
        if ($this->status->indice() === Status::CONCLUIDO) {
            $this->enviarEmail();
        }
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    private function enviarEmail(): void
    {
        if (eLocalhost()) {
            return;
        }

        $ormHelper = new OrmHelper(TABELA_CONSTRUTOR_CLUBE);
        $clube = $ormHelper->pegarUltimoRegistro(
            ['id_admin_empresa', $this->id_admin_empresa],
            ['id', 'titulo', 'logo_principal', 'cor_principal', 'link_clube'],
            'object'
        );
        $youhuul = $ormHelper->pegarUltimoRegistro(
            ['id_admin_empresa', 1],
            ['id', 'contato_telefone'],
            'object'
        );
        $contato = !empty($youhuul->contato_telefone) ? (new Telefone($youhuul->contato_telefone))->telefone() : '';

        if (empty($clube->id)) {
            mensagemErro(
                'Não foi possível notificar o usuário',
                'Houve uma instabilidade ao notificar o usuário'
            );
        }

        $quemIndicouNome = $this->quem_indicou['nome'];
        $quemIndicouEmail = $this->quem_indicou['email'];
        $mensagem = <<<HTML
            Olá, <strong>$quemIndicouNome!</strong>
            Sua indicação de "$this->nome" foi concluída com sucesso!
            Agora, ela está disponível no <strong><a href="$clube->link_clube" target="_blank">$clube->titulo</a></strong>.
            Acesse sua conta e aproveite os benefícios dessa parceria.
        HTML;
        $posMensagem = <<<HTML
            Se tiver alguma dúvida, não hesite em entrar em contato com nosso atendimento atráves do telefone: $contato
        HTML;

        $Email = new EmailHelper();
        $Email->mensagem(
            'Indicação de Parceria',
            $mensagem,
            'Confirmação de Indicação de Parceria',
            posMensagem: $posMensagem,
            acao: 'Indicação de Parceria',
            logo: arquivoPrivado($clube->logo_principal),
            cor: $clube->cor_principal
        );
        $Email->sendGrid(
            'Indicação de Parceria',
            $quemIndicouNome,
            $quemIndicouEmail,
            deNome: $clube->titulo
        );
    }
}
