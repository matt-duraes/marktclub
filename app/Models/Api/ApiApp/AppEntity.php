<?php

namespace App\Models\Api\ApiApp;

use ORM\Entity;
use Modules\Botao;
use Helpers\CryptHelper;

final class AppEntity extends Entity
{
    protected string $ormTabela = TABELA_AUTH_APP;

    protected array $ormBuscar = [
        'nome', 'descricao', 'id_admin_empresa', 'tempo_vida', 'redirect_uri', 'scope_permitido',
        'campo_permitido', 'client_id', 'secret_id', 'audience', 'chave_privada', 'chave_publica',
        'authorization_code', 'client_credentials', 'refresh_token', 'chave_privada_fake', 'chave_publica_fake',
        'imagem_app'
    ];

    protected array $ormSalvar = [
        'chave_publica', 'chave_privada', 'client_id', 'secret_id'
    ];

    public string $nome;
    public string $descricao;
    public int $id_admin_empresa;
    public int $tempo_vida;
    public array $scope_permitido;
    public array $campo_permitido;
    public array $redirect_uri;
    public Botao $authorization_code;
    public Botao $client_credentials;
    public Botao $refresh_token;
    public string $client_id;
    public string $secret_id;
    public string $audience;
    public string $chave_privada;
    public string $chave_publica;
    public Botao $chave_privada_publica;
    public Botao $chave_publica_publica;
    public string $imagem;

    public function getId()
    {
        return $this->prop('id');
    }

    protected function regraPosBuscar()
    {
        $this->chave_privada_publica = new Botao(!empty($this->chave_privada_fake) ? 'sim' : 'nao');
        $this->chave_publica_publica = new Botao(!empty($this->chave_publica_fake) ? 'sim' : 'nao');
        $this->imagem = arquivoPrivado($this->imagem_app);
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
