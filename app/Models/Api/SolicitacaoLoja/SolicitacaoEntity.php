<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Status as StatusLoja;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Erro;
use Erro\Excecao;
use Helpers\EmailHelper;
use Helpers\OrmHelper;
use Modules\Cpf;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;
use SendGrid\Mail\TypeException;

class SolicitacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Cpf $cpf;
    public string $nome;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    public string $mensagem;
    public string $usuario;
    public string|array $parceiro;
    public string $parceiro_novo;
    public array $quemIndicou;
    public string $origemIndicacao;
    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja',
        'nome', 'telefone', 'email', 'mensagem', 'status',
        'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario',
        'nome', 'telefone', 'email', 'mensagem'
    ];
    protected array $ormSalvar = [
        'id_parceiro_loja', 'status'
    ];
    protected string $ormValidarInsert = '
        nome|Nome|obrigatorio|vazio
        mensagem|Mensagem|obrigatorio|vazio
    ';
    protected int $id_admin_empresa;
    protected int $id_usuario_cliente;
    protected ?int $id_parceiro_loja;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->setarIdEmpresa();
        $this->setarIdUsuario();
        parent::__construct();
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarOrigemIndicacao();
        $this->pegarQuemIndicou();
        $this->pegarParceiroVinculado();
    }

    private function pegarOrigemIndicacao(): void
    {
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $empresa = $ormHelper->pegarUltimoRegistro(
            ['id', $this->id_admin_empresa],
            ['id', 'titulo', 'nome_fantasia'],
            'object'
        );

        if (empty($empresa->id)) {
            return;
        }
        $this->origemIndicacao = empty($empresa->titulo) ? $empresa->nome_fantasia : $empresa->titulo;
    }

    private function pegarQuemIndicou(): void
    {
        $ormHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $ormHelper->pegarUltimoRegistro(
            ['id', $this->id_usuario_cliente],
            ['uuid', 'nome', 'documento', 'email_pessoal'],
            'object'
        );

        if (empty($usuario->uuid)) {
            return;
        }
        $nome = new Nome($usuario->nome);
        $cpf = new Cpf($usuario->documento);
        $email = new Email($usuario->email_pessoal);
        $this->quemIndicou = [
            'id'    => $usuario->uuid,
            'nome'  => $nome->valido() ? $nome->nome() : '',
            'cpf'   => $cpf->valido() ? $cpf->cpf() : '',
            'email' => $email->valido() ? $email->email() : ''
        ];
    }

    private function pegarParceiroVinculado(): void
    {
        if (empty($this->id_parceiro_loja)) {
            return;
        }
        $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
        $parceiro = $ormHelper->pegarUltimoRegistro(
            ['id', $this->id_parceiro_loja],
            ['uuid', 'titulo_interno', 'nome_fantasia', 'razao_social', 'status'],
            'object'
        );

        if (empty($parceiro->uuid)) {
            $this->parceiro = [];
            return;
        }
        $this->parceiro = [
            'id'             => $parceiro->uuid,
            'titulo_interno' => $parceiro->titulo_interno,
            'nome_fantasia'  => $parceiro->nome_fantasia,
            'razao_social'   => $parceiro->razao_social,
            'status'         => (new StatusLoja($parceiro->status))->nome()
        ];
    }

    protected function regraInsert(): void
    {
        $this->status = new Status(Status::SEM_VINCULO);
    }

    /**
     * @return void
     * @throws Erro
     * @throws Excecao
     */
    protected function regraUpdate(): void
    {
        $isSemVinculo = $this->status->indice() === Status::SEM_VINCULO;
        if ($isSemVinculo) {
            if ($this->propriedadeExiste('parceiro') && !empty($this->parceiro)) {
                $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
                $parceiro = $ormHelper->pegarUltimoRegistro(
                    ['uuid', $this->parceiro],
                    ['id', 'status'],
                    'object'
                );

                if (empty($parceiro->id)) {
                    return;
                }

                $this->id_parceiro_loja = $parceiro->id;
                $status = new StatusLoja($parceiro->status);
                if ($status->indice() === StatusLoja::CONCLUIDO) {
                    $this->status = new Status(Status::CONCLUIDO);
                } elseif (in_array($status->indice(), [StatusLoja::CANCELADO, StatusLoja::SEM_INTERESSE])) {
                    $this->status = new Status(Status::CANCELADO);
                } else {
                    $this->status = new Status(Status::ANDAMENTO);
                }
            } elseif ($this->propriedadeExiste('parceiro_novo') && !empty($this->parceiro_novo)) {
                $this->id_parceiro_loja = $this->gerarParceiroEmProspeccao($this->parceiro_novo);
                $this->status = new Status(Status::ANDAMENTO);
            }
        }
    }

    /**
     * @param string $nome
     *
     * @return int
     * @throws Excecao
     * @throws Erro
     */
    private function gerarParceiroEmProspeccao(string $nome): int
    {
        try {
            $parceiro = new LojaEntity();
            $parceiro->set('titulo_interno', $nome);
            $parceiro->set('tipo_loja', TipoLoja::LOJA);
            $parceiro->set('categoria_principal', Categoria::OUTROS);
            $parceiro->set('url', strSlug($nome));
            $parceiro->set('empresa', ['14afa776394ada4be23be6acf7e3259e']);
            $parceiro->salvar();

            return $parceiro->prop('id');
        } catch (Excecao) {
            mensagemErro(
                'Não foi possível vincular o parceiro',
                'Ocorreu um erro ao vincular o parceiro.'
            );
        }
    }

    /**
     * @return void
     * @throws Excecao
     * @throws TypeException
     */
    protected function regraPosUpdate(): void
    {
        $this->pegarQuemIndicou();
        match ($this->status->indice()) {
            Status::CONCLUIDO => $this->enviarEmailConcluido(
                $this->id_admin_empresa,
                $this->quemIndicou['nome'],
                $this->quemIndicou['email'],
                $this->parceiro['nome_fantasia']
            ),
            Status::CANCELADO => $this->enviarEmailCancelado(
                $this->id_admin_empresa,
                $this->quemIndicou['nome'],
                $this->quemIndicou['email'],
                $this->parceiro['nome_fantasia']
            )
        };
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    public function enviarEmailConcluido(
        string $idEmpresa,
        string $nomeIndicou,
        string $emailIndicou,
        string $nomeParceiro
    ): void {
        if (eLocalhost()) {
            return;
        }

        $ormHelper = new OrmHelper(TABELA_CONSTRUTOR_CLUBE);
        $clube = $ormHelper->pegarUltimoRegistro(
            ['id_admin_empresa', $idEmpresa],
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

        $mensagem = <<<HTML
            Olá, <strong>$nomeIndicou!</strong>
            Sua indicação de "$nomeParceiro" foi concluída com sucesso!
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
            $nomeIndicou,
            $emailIndicou,
            deNome: $clube->titulo
        );
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    public function enviarEmailCancelado(
        string $idEmpresa,
        string $nomeIndicou,
        string $emailIndicou,
        string $nomeParceiro
    ): void {
        if (eLocalhost()) {
            return;
        }

        $ormHelper = new OrmHelper(TABELA_CONSTRUTOR_CLUBE);
        $clube = $ormHelper->pegarUltimoRegistro(
            ['id_admin_empresa', $idEmpresa],
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

        $mensagem = <<<HTML
            Olá, <strong>$nomeIndicou!</strong>
            Agradecemos por indicar "$nomeParceiro" no <strong><a href="$clube->link_clube" target="_blank">$clube->titulo</a></strong>.
            No momento, não foi possível aprovar essa parceria, mas continuaremos monitorando novas oportunidades para o futuro.
        HTML;
        $posMensagem = <<<HTML
            Se tiver alguma dúvida, não hesite em entrar em contato com nosso atendimento atráves do telefone: $contato
        HTML;

        $Email = new EmailHelper();
        $Email->mensagem(
            'Indicação de Parceria',
            $mensagem,
            'Retorno de Indicação de Parceria',
            posMensagem: $posMensagem,
            acao: 'Indicação de Parceria',
            logo: arquivoPrivado($clube->logo_principal),
            cor: $clube->cor_principal
        );
        $Email->sendGrid(
            'Indicação de Parceria',
            $nomeIndicou,
            $emailIndicou,
            deNome: $clube->titulo
        );
    }
}
