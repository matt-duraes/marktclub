<?php

namespace App\Helpers\Cfm;

use Modules\Cpf;
use Modules\Data;
use Helpers\ListaHelper;

final class UsuarioHelper
{
    public const TIPO_FUNCIONARIO = 'funcionario';
    public const TIPO_MEDICO = 'medico';

    public string $linkToken;
    public string $linkLogin;
    public string $clientId;
    public string $secretId;
    public string $nome;
    public string $email_pessoal;
    public string $email_trabalho;

    public function __construct(
        private string $tipo,
        public readonly Cpf $cpf,
        public readonly string $inscricao,
        public readonly string $estado,
        public readonly Data $dataNascimento,
        public readonly string $nomeMae
    ) {
        $this->linkToken = env('CFM_API_LINK_TOKEN', '');
        $this->linkLogin = env('CFM_API_LINK_LOGIN', '');
        $this->clientId = env('CFM_API_CLIENT_ID', '');
        $this->secretId = env('CFM_API_SECRET_ID', '');

        $this->validarDado();
        $this->buscarUsuario();
    }

    private function validarDado()
    {
        $this->cpf->validar(campo: 'CPF');
        $inscricaoCampo = $this->tipo == 'funcionario' ? 'Matrícula' : 'CRM';
        if (empty($this->inscricao)) {
            mensagemErroVazio(campo: $inscricaoCampo);
        }
        $estado = (new ListaHelper())->uf()->add('BR', 'BR')->r();
        if (!in_array($this->estado, $estado)) {
            mensagemErroValido(campo: 'Conselho');
        }
        $this->dataNascimento->validar(campo: 'Data de nascimento');
        if (empty($this->nomeMae)) {
            mensagemErroVazio(campo: 'Nome da mãe');
        }
    }

    private function buscarUsuario()
    {
        // $usuario = $this->requisicaoUsuario();
        $usuario = [
            'emails' => [
                ['email' => emailAleatorio()],
                ['email' => emailAleatorio()]
            ],
            'nome'   => nomeCompletoAleatorio(),
        ];
        $this->email_pessoal = $this->pegarEmail($usuario['emails'] ?? []);
        $this->email_trabalho = $this->pegarEmail($usuario['emails'] ?? [], $this->email_pessoal);
        $this->nome = $usuario['nome'] ?? '';
    }

    private function pegarEmail(array $listaEmail = [], ?string $jaUsado = null)
    {
        $emailFinal = '';
        foreach ($listaEmail as $email) {
            if (!empty($jaUsado) && $email['email'] == $jaUsado) {
                continue;
            }
            if (
                str_contains($email['email'], '@gmail') ||
                str_contains($email['email'], '@outlook') ||
                str_contains($email['email'], '@hotmail')
            ) {
                return $email['email'];
            }
            $emailFinal = $email['email'];
        }
        return $emailFinal;
    }

    private function requisicaoToken()
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
        ppe(curl_exec($ch));
        $retorno = jsonDecode(curl_exec($ch), true);
        curl_close($ch);

        if (!is_array($retorno) || !array_key_exists('access_token', $retorno)) {
            return '';
        }

        $token = $retorno['access_token'];
        if (empty($token)) {
            return mensagemErro(
                titulo: 'Erro!',
                mensagem: 'Ocorreu um erro ao fazer seu login, por favor, tente novamente.',
                status: 401
            );
        }
        return $token;
    }

    private function requisicaoUsuario()
    {
        $nomeMae = explode(' ', $this->nomeMae)[0] ?? '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->linkLogin);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'tipo'       => $this->tipo == 'funcionario' ? 'F' : 'M',
            'cpf'        => $this->cpf->numero(),
            'inscricao'  => preg_replace('/[^0-9]/', '', $this->inscricao),
            'uf'         => $this->estado,
            'nascimento' => $this->dataNascimento->date(),
            'nomeMae'    => $nomeMae
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->requisicaoToken()
        ]);

        $retorno = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!is_array($retorno) || !array_key_exists('nome', $retorno)) {
            return mensagemErro(
                titulo: 'Erro!',
                mensagem: 'Não foi possível validar seus dados, por favor, verifique os dados informados e tente novamente.',
                status: 401
            );
        }
        return $retorno;
    }
}
