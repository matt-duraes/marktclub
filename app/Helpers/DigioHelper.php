<?php

namespace App\Helpers;

final class DigioHelper
{

    private string $link;
    private string $clientId;
    private string $secredId;
    private array $usuario;

    public function __construct(
        private ?string $id
    ) {
        $this->link = env('DIGIO_API_LINK', '');
        $this->clientId = env('DIGIO_API_CLIENT_ID', '');
        $this->secredId = env('DIGIO_API_SECRET_ID', '');

        $this->buscarUsuarioViaCurl();
        $this->validarRetornoUsuario();
        $this->montarUsuario();
    }

    /**
     * Pega o nome, e-mail e CPF do usuário
     *
     * @return array Array com dados do usuário
     */
    public function usuario()
    {
        return $this->usuario;
    }

    private function pegarToken()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->link . '/auth/realms/digio-apis/protocol/openid-connect/token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&client_id=' . $this->clientId . '&client_secret=' . $this->secredId);

        $retorno = json_decode(curl_exec($ch), true);
        // $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // $erro = curl_error($ch);
        // $info = curl_getinfo($ch);
        if (!is_array($retorno) || !array_key_exists('access_token', $retorno)) {
            mensagemErro(
                'Erro!',
                'Não foi possível buscar o usuário, por favor, tente novamente.',
                localhost: 'Não foi possível pegar o token do DIGIO.'
            );
        }
        return $retorno['access_token'];
    }

    private function buscarUsuarioViaCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->link . '/partners/marktclub/customers/info?partner=MARKTCLUB&client-id=' . $this->id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->pegarToken()]);

        $retorno = json_decode(curl_exec($ch), true);
        $this->usuario = is_array($retorno) ? $retorno : [];
    }
    private function validarRetornoUsuario()
    {
        $usuario = $this->usuario;
        if (!array_key_exists('document', $usuario)) {
            $erro = array_key_exists('error', $usuario) && array_key_exists('message', $usuario['error']) ? ' - ' . $usuario['error']['message'] : '';
            mensagemErro('Erro!', 'Não foi possível achar seu usuário, por favor, tente novamente.', localhost: 'Erro na busca do usuário no DIGIO' . $erro . '.');
        }
    }
    private function montarUsuario()
    {
        $usuario = $this->usuario;
        $this->usuario = [
            'nome' => $usuario['name'],
            'email_pessoal' => $usuario['email'],
            'documento' => $usuario['document']
        ];
    }
}
