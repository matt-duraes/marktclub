<?php

namespace App\Models\Api\ApiApp;

use ORM\Entity;
use Helpers\CryptHelper;

final class AppEntity extends Entity
{
    protected string $_tabela = TABELA_AUTH_APP;

    protected array $_buscar = [
        'nome',
        'id_admin_empresa',
        'tempo_vida',
        'redirect_uri',
        'scope_permitido',
        'campo_permitido',
        'client_id',
        'audience',
        'chave_privada'
    ];

    protected array $_update = ['chave_publica', 'chave_privada', 'client_id', 'secret_id'];

    public array $scope_permitido;
    public array $campo_permitido;
    public array $redirect_uri;
    public string $chave_privada;

    public function getId()
    {
        return $this->prop('id');
    }

    public function criarChavePublica()
    {
        $Crypt = new CryptHelper();
        $chave = $Crypt->gerarChave();

        $this->chave_privada = $chave['privada'];
        $this->chave_publica = $chave['publica'];
    }

    public function criarClientId()
    {
        $url = explode('/', preg_replace(['/^http(s){0,1}\:\/\//', '/\:[0-9]+/'], ['', ''], $this->redirect_uri[0] ?? ''))[0];
        $quantidade = is_string($url) && !empty($url) ? mb_strlen($url, 'UTF-8') : 0;

        $tamanhoCodigo = 77 - $quantidade;
        $this->client_id = strCodigo(10, false, false) . '-' . strCodigo(10) . strCodigo($tamanhoCodigo, outro: '#$%!*') . '.' . $url;
    }

    public function criarSecretId()
    {
        $this->secret_id = strCodigo(5, false, false) . '-' . strCodigo(10) . strCodigo(64, outro: '#$%!*');
    }
}
