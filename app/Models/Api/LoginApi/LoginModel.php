<?php

namespace App\Models\Api\LoginApi;

use ORM\Entity;
use Modules\Cpf;
use Http\Request;
use Modules\Data;
use Modules\Nome;
use Modules\Email;
use Modules\Genero;
use Modules\Telefone;
use Helpers\ListaHelper;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;
use App\Models\Api\LoginApi\Trait\LinkTrait;
use App\Models\Api\LoginApi\Trait\UsuarioTrait;
use App\Models\Api\LoginApi\Trait\ConstrutorTrait;

final class LoginModel extends Entity
{
    use ConstrutorTrait;
    use UsuarioTrait;
    use LinkTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    private string $linkClube;
    private int $idEmpresa;
    private array $dadoUsuario;
    private ?string $idUsuario = null;
    private ?int $statusUsuario = null;
    private ?string $hash = null;

    public function __construct(
        private Request $request
    ) {
        if (!defined('TOKEN')) {
            mensagemStatus(401);
        }

        parent::__construct();

        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->dadoUsuario = $request->dado();
        $this->hash = uuid();

        $this->buscarLinkClube();
        $this->verificarCamposObrigatorio();
        $this->validarRequest();

        $this->verificarSeUsuarioJaExiste();
        if (!empty($this->idUsuario)) {
            $this->atualizarUsuarioJaExistente();
            return;
        }
        $this->salvarNovoUsuario();
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR USUÁRIO
    |--------------------------------------------------------------------------
    */
    private function verificarCamposObrigatorio(): void
    {
        $request = $this->request;
        if (!$request->existe('nome')) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif (!$request->existe('cpf')) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (!$request->existe('email_pessoal') && !$request->existe('email_trabalho')) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail.');
        }
        return;
    }
    private function validarRequest()
    {
        $dado = $this->dadoUsuario;
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
        $grupo = strCaixaAlta($dado['grupo'] ?? '');
        $crmNumero = $dado['crm_numero'] ?? '';
        $crmEstado = new EnderecoEstado($dado['crm_estado'] ?? '');

        $estadoLista = (new ListaHelper())->uf()->add('FU', 'FU')->r();

        // NOME
        if ($nome->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } elseif (!$nome->valido()) {
            mensagemErro('Campo inválido!', 'O campo nome deve ter pelo menos um sobrenome.');
            // DOCUMENTO
        } elseif ($cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } elseif (!$cpf->valido()) {
            mensagemErro('Campo inválido!', 'O campo CPF não é um documento válido.');
        } elseif (!empty($matricula) && !preg_match('/^[0-9]{1,}$/', $matricula)) {
            mensagemErro('Campo inválido', 'A matrícula deve ser um valor inteiro.');
        } elseif (!empty($siape) && !preg_match('/^[0-9]{1,}$/', $siape)) {
            mensagemErro('Campo inválido', 'O SIAPE deve ser um valor inteiro.');
            //EMAIL
        } elseif ($emailPessoal->vazio() && $emailTrabalho->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail.');
        } elseif (!$emailPessoal->vazio() && !$emailPessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo E-mail pessoal não é um e-mail válido.');
        } elseif (!$emailTrabalho->vazio() && !$emailTrabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo E-mail de trabalho não é um e-mail válido.');
            // TELEFONE
        } elseif (!$telefonePessoal->vazio() && !$telefonePessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo Telefone pessoal não é um telefone válido.');
        } elseif (!$telefoneTrabalho->vazio() && !$telefoneTrabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo Telefone de trabalho não é um telefone válido.');
            // DADOS PESSOAIS
        } elseif (!$genero->vazio() && !$genero->valido()) {
            mensagemErro('Campo inválido!', 'O campo Gênero não é um valor válido.');
        } elseif (!$estadoCivil->vazio() && !$estadoCivil->valido()) {
            mensagemErro('Campo inválido!', 'O campo Estado Civil não é um valor válido.');
        } elseif (!$dataNascimento->vazio() && (!$dataNascimento->valido() || !$dataNascimento->eDate())) {
            mensagemErro('Campo inválido!', 'O campo Data de nascimento não é uma data válida.');
            // ENDEREÇO
        } elseif (!$enderecoEstado->vazio() && !$enderecoEstado->valido()) {
            mensagemErro('Campo inválido!', 'O campo Estado do endereço não é uma UF válida.');
        } elseif (!empty($federacao) && !in_array($federacao, $estadoLista)) {
            mensagemErro('Campo inválido!', 'O campo Federação não é um valor válida.');
            // OUTROS
        } elseif (!empty($salavip) && !preg_match('/^[0-9]{1,}$/', $salavip)) {
            mensagemErro('Campo inválido', 'A Salavip deve ser um valor inteiro.');
        } elseif (!empty($crmNumero) && !preg_match('/^[0-9]{1,}$/', $crmNumero)) {
            mensagemErro('Campo inválido', 'O CRM deve ser um valor inteiro.');
        } elseif (!$crmEstado->vazio() && !$crmEstado->valido()) {
            mensagemErro('Campo inválido!', 'O estado do CRM não é uma UF válida.');
        }

        $this->dadoUsuario = removerIndiceVazio([
            'nome' => $nome->nome(),
            'documento' => (int) $cpf->numero(),
            'crm_numero' => !empty($crmNumero) ? (int) $crmNumero : null,
            'crm_estado' => $crmEstado->estado(),
            'matricula' => $matricula,
            'siape' => $siape,
            'sexo' => $genero->numero(),
            'estado_civil' => $estadoCivil->numero(),
            'aniversario' => $dataNascimento->date(),
            'email_pessoal' => $emailPessoal->email(),
            'email_trabalho' => $emailTrabalho->email(),
            'telefone_celular' => $telefonePessoal->numero(),
            'telefone_fixo' => $telefoneTrabalho->numero(),
            'uf' => $enderecoEstado->estado(),
            'cidade' => $enderecoCidade,
            'federacao' => $federacao,
            'salavip' => $salavip,
            'grupo' => $grupo,
            'hash' => $this->hash,
            'hash_data' => agora(),
            'status' => 1
        ]);
    }
}
