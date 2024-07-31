<?php

namespace App\Helpers\Cfm;

use Modules\Cpf;
use Modules\Data;
use Modules\Inteiro;
use Helpers\ListaHelper;

final class UsuarioHelper
{
    public string $linkToken;
    public string $linkLogin;
    public string $clientId;
    public string $secretId;
    public string $nome;
    public string $email;

    public function __construct(
        string $tipo,
        public readonly Cpf $cpf,
        public readonly Inteiro $inscricao,
        public readonly string $estado,
        public readonly Data $dataNascimento,
        public readonly string $nomeMae
    ) {
        $this->linkToken = env('CFM_API_LINK_TOKEN', '');
        $this->linkLogin = env('CFM_API_LINK_LOGIN', '');
        $this->clientId = env('CFM_API_CLIENT_ID', '');
        $this->secretId = env('CFM_API_SECRET_ID', '');

        $this->validarDado();
        $this->buscarUsuario($tipo);
    }

    private function validarDado()
    {
        $this->cpf->validar(campo: 'CPF');
        $this->inscricao->validar(campo: 'Inscrição');
        $estado = (new ListaHelper())->uf()->add('BR', 'BR')->r();
        if (!in_array($this->estado, $estado)) {
            mensagemErroValido(campo: 'Conselho');
        }
        $this->dataNascimento->validar(campo: 'Data de nascimento');
        if (empty($this->nomeMae)) {
            mensagemErroVazio(campo: 'Nome da mãe');
        }
    }

    private function buscarUsuario($tipo)
    {
        $inscricaoCampo = $tipo == 'funcionario' ? 'Matrícula' : 'CRM';
        $nomeMae = explode(' ', $this->nomeMae)[0] ?? '';

        $token = $this->pegarToken();
        if (empty($token)) {
            return mensagemErro(
                titulo: 'Erro!',
                mensagem: 'Ocorreu um erro ao fazer seu login, por favor, tente novamente.',
                status: 401
            );
        }
    }

    private function pegarToken()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->linkToken);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'client_credentials',
            'scope'      => 'openid'
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->secretId)
        ]);

        $retorno = jsonDecode(curl_exec($ch), true);
        curl_close($ch);

        if (!is_array($retorno) || !array_key_exists('access_token', $retorno)) {
            return '';
        }
        return $retorno['access_token'];
    }

    private function pegarUsuario()
}
