<?php

namespace App\Helpers;

use Helpers\ApiHelper;
use Helpers\CryptHelper;

class ClubeApiHelper extends ApiHelper
{
    protected string $idUsuario;
    public CryptHelper $Crypt;

    public function __construct(
        private ?string $scope = null
    ) {
        $token = is_null($scope) ? true : false;
        parent::__construct(scope: $scope, token: $token);
        $this->idUsuario = sessao('USUARIO.id');
        $this->setarCryptHelper();
    }

    private function setarCryptHelper()
    {
        if (!empty($this->scope)) {
            $this->pegarChaveViaScope();
            return;
        }
        if (!sessaoExiste('CRYPT_CHAVE_PUBLICA') || empty(sessao('CRYPT_CHAVE_PUBLICA'))) {
            $chavePublica = $this->pegarChavePublica();
            sessao('CRYPT_CHAVE_PUBLICA', $chavePublica);
        }
        if (!sessaoExiste('CRYPT_CHAVE_PRIVADA') || empty(sessao('CRYPT_CHAVE_PRIVADA'))) {
            $chave = $this->pegarChavePrivada();
            sessao('CRYPT_CHAVE_PRIVADA', $chave);
        }
        $this->Crypt = new CryptHelper(
            chavePublica: sessao('CRYPT_CHAVE_PUBLICA'),
            chavePrivada: sessao('CRYPT_CHAVE_PRIVADA')
        );
    }

    private function pegarChaveViaScope()
    {
        if (!sessaoExiste('CRYPT_CHAVE_PUBLICA_SCOPE') || empty(sessao('CRYPT_CHAVE_PUBLICA_SCOPE'))) {
            $chavePublica = $this->pegarChavePublica();
            sessao('CRYPT_CHAVE_PUBLICA_SCOPE', $chavePublica);
        }
        if (!sessaoExiste('CRYPT_CHAVE_PRIVADA_SCOPE') || empty(sessao('CRYPT_CHAVE_PRIVADA_SCOPE'))) {
            $chavePrivada = $this->pegarChavePrivada();
            sessao('CRYPT_CHAVE_PRIVADA_SCOPE', $chavePrivada);
        }
        $this->Crypt = new CryptHelper(
            chavePublica: sessao('CRYPT_CHAVE_PUBLICA_SCOPE'),
            chavePrivada: sessao('CRYPT_CHAVE_PRIVADA_SCOPE')
        );
    }

    private function pegarChavePublica()
    {
        return $this
            ->get('/admin/chave-publica')
            ->object()->dado->chave ?? '';
    }

    private function pegarChavePrivada()
    {
        return $this
            ->get('/admin/chave-privada')
            ->object()->dado->chave ?? '';
    }
}
