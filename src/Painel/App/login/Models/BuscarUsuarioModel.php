<?php

namespace PainelApp\login\Models;

use stdClass;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use PainelApp\login\Models\Trait\ChaveTrait;
use App\Classes\UsuarioEquipe\Status as StatusEquipe;
use App\Classes\ComercialEmpresa\Status as StatusEmpresa;

final class BuscarUsuarioModel
{
    use ChaveTrait;

    private string $idUsuario;
    private stdClass $usuario;
    private stdClass $empresa;

    public function __construct()
    {
        $this->idUsuario = sessao('USUARIO.id');
        $this->setarChaves();
        $this->buscarDadoUsuario();
        $this->validarSeUsuarioPodeLogar();
        $this->remontarSessaoUsuario();
        $this->buscarDadoEmpresa();
        $this->validarSeEmpresaPodeLogar();
        $this->montarDadoEmpresa();
    }

    private function buscarDadoUsuario()
    {
        try {
            $this->usuario = (new ApiHelper(token: true))
                ->get('/usuario-equipe/' . $this->idUsuario)
                ->object()
                ->dado ?? [];
        } catch (\Throwable $e) {
            $this->erroGeral('Erro ao buscar usuário.', $e);
        }
    }

    private function validarSeUsuarioPodeLogar()
    {
        if ($this->usuario->status != StatusEquipe::ATIVO) {
            $this->erroGeral('Usuário não está ativo.');
        }
    }

    private function remontarSessaoUsuario()
    {
        $Crypt = new CryptHelper(chavePrivada: $this->chavePrivada);
        $body = $this->usuario;

        $cpf = $Crypt->decode($body->cpf);

        $emailPessoal = $Crypt->decode($body->email_pessoal);
        $emailTrabalho = $Crypt->decode($body->email_trabalho);
        $email = !empty($emailTrabalho) ? $emailTrabalho : $emailPessoal;

        sessao('USUARIO', [
            'id'        => $body->id,
            'empresa'   => $body->empresa,
            'nome'      => $Crypt->decode($body->nome),
            'email'     => $email,
            'imagem'    => $Crypt->decode($body->imagem),
            'cpf'       => $cpf,
            'google'    => $Crypt->decode($body->google ?? ''),
            'facebook'  => $Crypt->decode($body->facebook ?? ''),
            'marktclub' => $body->marktclub,
            'permissao' => $body->permissao,
            'gerente'   => $body->gerente,
            'admin'     => $body->admin,
            'dev'       => in_array($cpf, jsonDecode(env('DEV_DOCUMENTO', []), true, true))
        ]);
    }

    private function buscarDadoEmpresa()
    {
        try {
            $this->empresa = (new ApiHelper(token: true))
                ->get('/comercial-empresa/' . sessao('USUARIO.empresa')->id)
                ->object()
                ->dado ?? [];
        } catch (\Throwable $e) {
            $this->erroGeral('Erro ao buscar empresa.', $e);
        }
    }

    private function validarSeEmpresaPodeLogar()
    {
        if (!in_array($this->empresa->status, [StatusEmpresa::ATIVO, StatusEmpresa::PROSPECCAO])) {
            $this->erroGeral('Empresa não está ativa.');
        }
    }

    private function montarDadoEmpresa()
    {
        $empresa = $this->empresa;
        $Crypt = new CryptHelper(chavePrivada: $this->chavePrivada);
        sessao('EMPRESA', [
            'id'     => $empresa->id,
            'nome'   => $Crypt->decode($empresa->nome_fantasia),
            'cnpj'   => $Crypt->decode($empresa->cnpj),
            'imagem' => $Crypt->decode($empresa->imagem),
            'slug'   => $empresa->slug,
            'status' => $empresa->status
        ]);
    }

    private function erroGeral(?string $mensagem = null, ?\Throwable $e = null)
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer seu login, por favor, tente novamente.', localhost: $mensagem, error: $e);
    }
}
