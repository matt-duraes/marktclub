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
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

final class LoginModel extends Entity
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    private array $dado;
    private int $idEmpresa;
    private ?string $idUsuario = null;
    private ?int $statusUsuario = null;
    private string $hash;

    public function __construct(
        private Request $request
    ) {
        if (!defined('TOKEN')) {
            mensagemStatus(401);
        }

        parent::__construct();

        $this->idEmpresa = TOKEN['empresa']->get('id');

        $this->dado = $request->dado();
        $this->hash = uuid();

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]]
        ]);
        $this->linkClube = $Construtor->link_clube;

        $this->verificarCamposObrigatorio();
        $this->validarRequest();
        $this->verificarSeUsuarioJaExiste();
        if ($this->idUsuario) {
            $this->atualizarUsuarioJaExistente();
            return;
        }
        $this->salvarNovoUsuario();
    }

    public function link()
    {
        if (SISTEMA == 'HOMOLOGACAO') {
            return 'https://apiv4homologacao.marktclub.com.br/login/api-ok/' . base64Encode([
                'nome' => $this->dado['nome'],
                'data' => agora(),
                'hash' => $this->hash
            ], true);
        }
        return 'https://' . $this->linkClube . '/login/api/' . $this->hash;
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
        } else if (!$request->existe('cpf')) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if (!$request->existe('email_pessoal') && !$request->existe('email_trabalho')) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail.');
        }
        return;
    }
    private function validarRequest()
    {
        $dado = $this->dado;
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

        $estadoLista = (new ListaHelper())->uf()->add('FU', 'FU')->r();

        // NOME
        if ($nome->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo nome é obrigatório.');
        } else if (!$nome->valido()) {
            mensagemErro('Campo inválido!', 'O campo nome deve ter pelo menos um sobrenome.');
            // DOCUMENTO
        } else if ($cpf->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo CPF é obrigatório.');
        } else if (!$cpf->valido()) {
            mensagemErro('Campo inválido!', 'O campo CPF não é um documento válido.');
        } else if (!empty($matricula) && preg_match('/^[0-9]{1,}$/', $matricula)) {
            mensagemErro('Campo inválido', 'A matrícula deve ser um valor inteiro.');
        } else if (!empty($siape) && preg_match('/^[0-9]{1,}$/', $siape)) {
            mensagemErro('Campo inválido', 'O SIAPE deve ser um valor inteiro.');
            //EMAIL
        } else if ($emailPessoal->vazio() && $emailTrabalho->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar pelo menos um e-mail.');
        } else if (!$emailPessoal->vazio() && !$emailPessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo E-mail pessoal não é um e-mail válido.');
        } else if (!$emailTrabalho->vazio() && !$emailTrabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo E-mail de trabalho não é um e-mail válido.');
            // TELEFONE
        } else if (!$telefonePessoal->vazio() && !$telefonePessoal->valido()) {
            mensagemErro('Campo inválido!', 'O campo Telefone pessoal não é um telefone válido.');
        } else if (!$telefoneTrabalho->vazio() && !$telefoneTrabalho->valido()) {
            mensagemErro('Campo inválido!', 'O campo Telefone de trabalho não é um telefone válido.');
            // DADOS PESSOAIS
        } else if (!$genero->vazio() && !$genero->valido()) {
            mensagemErro('Campo inválido!', 'O campo Gênero não é um valor válido.');
        } else if (!$estadoCivil->vazio() && !$estadoCivil->valido()) {
            mensagemErro('Campo inválido!', 'O campo Estado Civil não é um valor válido.');
        } else if (!$dataNascimento->vazio() && (!$dataNascimento->valido() || !$dataNascimento->eDate())) {
            mensagemErro('Campo inválido!', 'O campo Data de nascimento não é uma data válida.');
            // ENDEREÇO
        } else if (!$enderecoEstado->vazio() && !$enderecoEstado->valido()) {
            mensagemErro('Campo inválido!', 'O campo Estado do endereço não é uma UF válida.');
        } else if (!empty($federacao) && !in_array($federacao, $estadoLista)) {
            mensagemErro('Campo inválido!', 'O campo Federação não é um valor válida.');
            // OUTROS
        } else if (!empty($salavip) && preg_match('/^[0-9]{1,}$/', $salavip)) {
            mensagemErro('Campo inválido', 'A Salavip deve ser um valor inteiro.');
        }

        $this->dado = removerIndiceVazio([
            'nome' => $nome->nome(),
            'documento' => (int) $cpf->numero(),
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
    private function verificarSeUsuarioJaExiste()
    {
        $usuario = $this->campo(['cod', 'status'])->where([
            ['empresa', $this->idEmpresa],
            ['documento', $this->dado['documento']],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ])->primeiro();

        if (existeErro($usuario, 'cod')) {
            return;
        }
        $this->idUsuario = $usuario->cod;
        $this->statusUsuario = $usuario->status;
    }

    private function atualizarUsuarioJaExistente()
    {
        $agora = agora();
        $hoje = hoje();

        $dado = [
            'tipo' => 1,
            'data_atualizacao' => $agora,
            'data_dado' => $hoje
        ];

        if (array_key_exists('email_pessoal', $this->dado) || array_key_exists('email_trabalho', $this->dado)) {
            $dado['data_email'] = $hoje;
        }
        if ($this->statusUsuario != 1) {
            $dado['primeiro_acesso'] = 1;
        }

        $salvar = $this->dado(array_merge($this->dado, $dado))->where(['cod', $this->idUsuario])->update();
        if (existeErro($salvar, 'id') || empty($salvar['id'])) {
            mensagemErro('Erro ao atualizar!', 'Ocorreu um erro ao atualizar o usuário.', status: 500);
        }
    }
    private function salvarNovoUsuario()
    {
        $agora = agora();
        $hoje = hoje();

        $dado = [
            'cod' => uuid(),
            'tipo' => 1,
            'data_atualizacao' => $agora,
            'data_dado' => $hoje,
            'empresa' => $this->idEmpresa,
            'data_criacao' => $agora,
            'primeiro_acesso' => 1
        ];

        if (array_key_exists('email_pessoal', $this->dado) || array_key_exists('email_trabalho', $this->dado)) {
            $dado['data_email'] = $hoje;
        }

        $salvar = $this->dado(array_merge($this->dado, $dado))->insert();
        if (existeErro($salvar, 'id') || empty($salvar['id'])) {
            mensagemErro('Erro ao salvar!', 'Ocorreu um erro ao criar o usuário.', status: 500);
        }
        $this->idUsuario = $salvar['id'];
    }
}
