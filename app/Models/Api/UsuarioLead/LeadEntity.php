<?php

namespace App\Models\Api\UsuarioLead;

use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Classes\UsuarioLead\Status;
use App\Models\Api\UsuarioCliente\SalvarLeadModel;
use App\Models\Api\UsuarioLead\Trait\EmailTrait;
use Erro\Erro;
use Erro\Excecao;
use Modules\Cnpj;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\EnderecoCep;
use Modules\EnderecoEstado;
use Modules\Genero;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;
use SendGrid\Mail\TypeException;

final class LeadEntity extends Entity
{
    use EmailTrait;

    public Nome $nome;
    public Cpf $cpf;
    public Data $data_nascimento;
    public Data $trabalho_data_inicio;
    public Email $email_trabalho;
    public Email $email_pessoal;
    public Email $email_funcional;
    public Telefone $telefone_pessoal;
    public Telefone $telefone_trabalho;
    public Genero $genero;
    public EnderecoCep $endereco_cep;
    public EnderecoEstado $endereco_estado;
    public array $lista_dependente;
    public Status $status;
    public TrabalhoEmpresa $trabalho_empresa;
    public string|int $trabalho_cargo;
    public Origem $origem;
    public Cnpj $cnpj_trabalho;
    public string $contrato_siape;
    public string $siape;
    public string $rg;
    public string $endereco_logradouro;
    public string $endereco_complemento;
    public string $endereco_bairro;
    public string $endereco_cidade;
    public int|string $endereco_numero;
    public int $id_admin_empresa;
    public Data $termo_aceitar;
    public Data $termo_lgpd;
    protected string $ormTabela = TABELA_USUARIO_LEAD;
    protected array $ormBuscar = [
        'nome'   => 'nome_completo',
        'cpf'    => 'documento_cpf',
        'rg'     => 'documento_rg',
        'siape'  => 'documento_siape',
        'origem' => 'lead_origem',
        'email_trabalho', 'email_pessoal', 'email_funcional', 'telefone_pessoal', 'telefone_trabalho',
        'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro',
        'endereco_cidade', 'endereco_estado', 'genero', 'data_nascimento', 'lista_dependente', 'trabalho_empresa',
        'trabalho_cargo', 'trabalho_data_inicio', 'cnpj_trabalho', 'status'
    ];
    protected array $ormInsert = [
        'nome_completo'   => '->nome',
        'documento_cpf'   => '->cpf',
        'documento_rg'    => '->rg',
        'documento_siape' => '->siape',
        'lead_origem'     => '->origem',
        'email_trabalho', 'email_pessoal', 'email_funcional', 'telefone_pessoal', 'telefone_trabalho',
        'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro',
        'endereco_cidade', 'endereco_estado', 'genero', 'data_nascimento', 'lista_dependente', 'trabalho_empresa',
        'trabalho_cargo', 'trabalho_data_inicio', 'termo_aceitar', 'termo_lgpd', 'status', 'id_admin_empresa',
        'cnpj_trabalho'
    ];
    protected array $ormUpdate = ['status'];
    protected string $ormValidarInsert = '
        nome|Nome|vazio
        cpf|CPF|vazio|cpf
        email_pessoal|E-mail pessoal|email
        email_trabalho|E-mail de trabalho|email
        email_funcional|E-mail funcional|email
        telefone_pessoal|Telefone pessoal|telefone
        telefone_trabalho|Telefone de trabalho|telefone
        genero|Gênero|valido
        data_nascimento|Data de Nascimento|dataDate
        endereco_cep|CEP do endereço|valido
        endereco_estado|Estado do endereço|valido
        termo_aceitar|Termo|dataDate|hoje
        termo_lgpd|Termo da LGPD|dataDate|hoje
    ';
    private int $idEmpresa;
    private bool $usuarioAprovado = false;
    private bool $usuarioRecusado = false;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        parent::__construct();

        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no UsuarioLead\LeadEntity');
        }

        $this->idEmpresa = TOKEN['empresa']->id;
        $this->ormWherePadrao = ['id_admin_empresa', $this->idEmpresa];
    }

    protected function regraPosBuscar(): void
    {
        $this->contrato_siape = '';
        if (
            !$this->trabalho_empresa->vazio()
            && !empty($this->siape)
            && $this->idEmpresa == 19
            && in_array($this->status->indice(), ['novo', 'andamento', 'cadastro_realizado'])
        ) {
            $this->contrato_siape = $this->trabalho_empresa->numero() . $this->siape . '341201';
        }
        $this->montarDependente();
    }

    private function montarDependente(): void
    {
        if (empty($this->lista_dependente)) {
            return;
        }
        foreach (array_keys($this->lista_dependente) as $ind) {
            if (
                !array_key_exists($ind, $this->lista_dependente)
                || !array_key_exists('genero', $this->lista_dependente[$ind])
            ) {
                continue;
            }
            $this->lista_dependente[$ind]['genero'] = (new Genero($this->lista_dependente[$ind]['genero']))->valor();
        }
    }

    protected function regraInsert(): void
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = new Status(1);
    }

    /**
     * @throws Excecao|Erro
     */
    protected function regraUpdate(): void
    {
        if (!$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O campo status não está no formato correto.');
        }

        $statusAtual = $this->prop('status');
        $statusNovo = $this->status->numero();

        if ($statusAtual == $statusNovo) {
            return;
        } elseif ($statusAtual == 2 && $statusNovo == 3) {
            $this->usuarioAprovado = true;
        } elseif ($statusAtual == 2 && $statusNovo == 4) {
            $this->usuarioRecusado = true;
        } elseif (
            ($statusAtual == 4 && $statusNovo != 4)
            || ($statusAtual == 3 && $statusNovo != 3)
        ) {
            mensagemErro('Erro!', 'Você não pode mudar o status de um Lead finalizado.');
        } elseif ($statusAtual == 1 && !in_array($statusNovo, [1, 2])) {
            mensagemErro('Campo inválido!', 'Um Lead novo só pode mudar de status para "andamento".');
        } elseif ($statusAtual == 2 && !in_array($statusNovo, [2, 3, 4])) {
            mensagemErro(
                'Campo inválido!',
                'Um Lead em andamento só pode mudar de status para "sem-interesse" ou "cadastro-realizado".'
            );
        }
    }

    /**
     * @throws Excecao|TypeException
     */
    protected function regraPosUpdate(): void
    {
        if ($this->usuarioAprovado) {
            $this->enviarEmailAprovado();
            $this->salvarLeadComoUsuario();
        } elseif ($this->usuarioRecusado) {
            $this->enviarEmailRecusado();
        }
    }

    /**
     * @throws Excecao
     */
    private function salvarLeadComoUsuario(): void
    {
        $Cliente = new SalvarLeadModel();
        $Cliente->salvarLead([
            'nome'                 => $this->nome->nome(),
            'documento'            => (int)$this->cpf->numero(),
            'documento_rg'         => $this->rg,
            'siape'                => $this->siape,
            'email_trabalho'       => $this->email_trabalho->email(),
            'email_pessoal'        => $this->email_pessoal->email(),
            'email_funcional'      => $this->email_funcional->email(),
            'telefone_celular'     => $this->telefone_pessoal->telefone(),
            'telefone_fixo'        => $this->telefone_trabalho->telefone(),
            'endereco_cep'         => (int)soNumero($this->endereco_cep),
            'endereco_logradouro'  => $this->endereco_logradouro,
            'endereco_numero'      => $this->endereco_numero,
            'endereco_complemento' => $this->endereco_complemento,
            'endereco_bairro'      => $this->endereco_bairro,
            'cidade'               => $this->endereco_cidade,
            'uf'                   => $this->endereco_estado->uf(),
            'sexo'                 => $this->genero->numero(),
            'aniversario'          => $this->data_nascimento->date(),
            'trabalho_orgao'       => $this->trabalho_empresa->numero(),
            'trabalho_cargo'       => $this->trabalho_cargo,
            'trabalho_data_inicio' => $this->trabalho_data_inicio->date(),
            'lead_origem'          => $this->origem->numero()
        ]);
    }
}
