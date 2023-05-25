<?php

namespace App\Models\Api\LoginApi;

use App\Models\Api\LoginApi\Trait\ConstrutorTrait;
use App\Models\Api\LoginApi\Trait\LinkTrait;
use App\Models\Api\LoginApi\Trait\TermoLgpdTrait;
use App\Models\Api\LoginApi\Trait\UsuarioTrait;
use Erro\Excecao;
use Helpers\ListaHelper;
use Modules\Cpf;
use Modules\Data;
use Modules\Email;
use Modules\EnderecoEstado;
use Modules\EstadoCivil;
use Modules\Genero;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

final class LoginModel extends Entity
{
    use ConstrutorTrait;
    use UsuarioTrait;
    use LinkTrait;
    use TermoLgpdTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    private string $linkClube;
    private ?string $idUsuario = null;
    private ?int $statusUsuario = null;
    private ?string $hash;
    private bool $lgpd = false;
    private array $dadoUsuario;

    /**
     * @param  array     $request
     * @param  int|null  $idEmpresa
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly array $request,
        private ?int $idEmpresa = null
    ) {
        if (!defined('TOKEN') && empty($idEmpresa)) {
            mensagemStatus(401);
        }

        parent::__construct();

        $this->idEmpresa = !empty($idEmpresa) ? $idEmpresa : TOKEN['empresa']->get('id');
        $this->hash = uuid();

        $this->buscarLinkClube();
        $this->verificarCamposObrigatorio();
        $this->validarRequest();

        $this->verificarSeUsuarioJaExiste();
        if (!empty($this->idUsuario)) {
            $this->atualizarUsuarioJaExistente();
            return;
        } elseif (array_key_exists('termo_lgpd', $request) && $request['termo_lgpd'] == 'nao') {
            $this->lgpd = true;
            return;
        }
        $this->salvarNovoUsuario();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function verificarCamposObrigatorio(): void
    {
        $request = $this->request;
        $nome = $request['nome'] ?? '';
        $cpf = $request['cpf'] ?? '';
        $emailPessoal = $request['email_pessoal'] ?? '';
        $emailTrabalho = $request['email_trabalho'] ?? '';
        if (empty($nome)) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif (empty($cpf)) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (empty($emailTrabalho) && empty($emailPessoal)) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail.');
        }
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $dado = $this->request;
        $nome = new Nome($dado['nome'] ?? '');
        $cpf = new Cpf($dado['cpf'] ?? '');
        $matricula = $dado['matricula'] ?? '';
        $siape = $dado['siape'] ?? '';
        $genero = new Genero($dado['genero'] ?? '');
        $estadoCivil = new EstadoCivil($dado['estado_civil'] ?? '');
        $dataNascimento = new Data($dado['data_nascimento'] ?? '');
        $emailPessoal = new Email($dado['email_pessoal'] ?? '');
        $emailTrabalho = new Email($dado['email_trabalho'] ?? '');
        $telefonePessoal = new Telefone($dado['telefone_pessoal'] ?? '');
        $telefoneTrabalho = new Telefone($dado['telefone_trabalho'] ?? '');
        $enderecoEstado = new EnderecoEstado($dado['endereco_estado'] ?? '');
        $enderecoCidade = $dado['endereco_cidade'] ?? '';
        $federacao = strCaixaAlta($dado['federacao'] ?? '');
        $salavip = $dado['salavip'] ?? '';
        $grupo = strCaixaBaixa($dado['grupo'] ?? '');
        $crmNumero = $dado['crm_numero'] ?? '';
        $crmEstado = new EnderecoEstado($dado['crm_estado'] ?? '');
        $termo = $dado['termo_lgpd'] ?? '';

        $estadoLista = (new ListaHelper())->uf()->add('FU', 'FU')->r();

        if ($nome->vazio()) {
            // NOME
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif (!$nome->valido()) {
            // DOCUMENTO
            mensagemErro('Campo inválido!', 'O campo nome deve ter pelo menos um sobrenome.');
        } elseif ($cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (!$cpf->valido()) {
            mensagemErro('Campo inválido!', 'O campo CPF não é um documento válido.');
        } elseif (!empty($matricula) && !preg_match('/^[0-9]{1,}$/', $matricula)) {
            mensagemErro('Campo inválido', 'A matrícula deve ser um valor inteiro.');
        } elseif (!empty($siape) && !preg_match('/^[0-9]{1,}$/', $siape)) {
            mensagemErro('Campo inválido', 'O SIAPE deve ser um valor inteiro.');
        } elseif ($emailPessoal->vazio() && $emailTrabalho->vazio()) {
            // EMAIL
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail.');
        } elseif (!$emailPessoal->vazio() && !$emailPessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo E-mail pessoal não é um e-mail válido.');
        } elseif (!$emailTrabalho->vazio() && !$emailTrabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo E-mail de trabalho não é um e-mail válido.');
        } elseif (!$telefonePessoal->vazio() && !$telefonePessoal->valido()) {
            // TELEFONE
            mensagemErro('Campo inválido!', 'O campo Telefone pessoal não é um telefone válido.');
        } elseif (!$telefoneTrabalho->vazio() && !$telefoneTrabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo Telefone de trabalho não é um telefone válido.');
        } elseif (!$genero->vazio() && !$genero->valido()) {
            // DADOS PESSOAIS
            mensagemErro('Campo inválido!', 'O campo Gênero não é um valor válido.');
        } elseif (!$estadoCivil->vazio() && !$estadoCivil->valido()) {
            mensagemErro('Campo inválido!', 'O campo Estado Civil não é um valor válido.');
        } elseif (!$dataNascimento->vazio() && (!$dataNascimento->valido() || !$dataNascimento->eDate())) {
            mensagemErro('Campo inválido!', 'O campo Data de nascimento não é uma data válida.');
        } elseif (!$enderecoEstado->vazio() && !$enderecoEstado->valido()) {
            // ENDEREÇO
            mensagemErro('Campo inválido!', 'O campo Estado do endereço não é uma UF válida.');
        } elseif (!empty($federacao) && !in_array($federacao, $estadoLista)) {
            mensagemErro('Campo inválido!', 'O campo Federação não é um valor válida.');
        } elseif (!empty($salavip) && !preg_match('/^[0-9]{1,}$/', $salavip)) {
            // OUTROS
            mensagemErro('Campo inválido', 'A Salavip deve ser um valor inteiro.');
        } elseif (!empty($crmNumero) && !preg_match('/^[0-9]{1,}$/', $crmNumero)) {
            mensagemErro('Campo inválido', 'O CRM deve ser um valor inteiro.');
        } elseif (!$crmEstado->vazio() && !$crmEstado->valido()) {
            mensagemErro('Campo inválido!', 'O estado do CRM não é uma UF válida.');
        }

        $this->dadoUsuario = removerIndiceVazio([
            'nome'             => $nome->nome(),
            'documento'        => (int)$cpf->numero(),
            'crm_numero'       => !empty($crmNumero) ? (int)$crmNumero : null,
            'crm_estado'       => $crmEstado->estado(),
            'matricula'        => $matricula,
            'siape'            => $siape,
            'sexo'             => $genero->numero(),
            'estado_civil'     => $estadoCivil->numero(),
            'aniversario'      => $dataNascimento->date(),
            'email_pessoal'    => $emailPessoal->email(),
            'email_trabalho'   => $emailTrabalho->email(),
            'telefone_celular' => $telefonePessoal->numero(),
            'telefone_fixo'    => $telefoneTrabalho->numero(),
            'uf'               => $enderecoEstado->estado(),
            'cidade'           => $enderecoCidade,
            'federacao'        => $federacao,
            'salavip'          => $salavip,
            'grupo'            => $grupo,
            'hash'             => $this->hash,
            'hash_data'        => agora(),
            'status'           => 1
        ]);
        if ($termo == 'sim') {
            $this->dadoUsuario['data_termo'] = hoje();
        }
    }
}
