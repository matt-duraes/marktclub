<?php

namespace Helpers;

final class CryptHelper
{
    /**
     * @param null|string   $chave          Chave para criptografar, ENV('CRYPT_HASH') por padrão
     * @param null|string   $cipher         Um método cipher válido, AES-256-GCM por padrão
     * @param null|string   $chavePublica   Chave pública caso queira usar RSA
     * @param null|string   $chavePrivada   Chave privada caso queira usar RSA
     * @param bool          $url            Se vai converter o encode em URL
     */
    public function __construct(
        private ?string $chave = null,
        private ?string $cifra = null,
        private ?string $chavePublica = null,
        private ?string $chavePrivada = null,
        private bool $url = false
    ) {
        $this->chave = !empty($chave) ? $chave : ENV('CRYPT_HASH', '');
        $this->chave = !empty($this->chave) ? $this->chave : '3876b388a5d5a2417af13bc7d6335925c5e82695bf84873a3c1a2b34fb918a5a';
        $this->cifra = !empty($cifra) ? $cifra : 'AES-256-CBC';
    }

    /*
    |--------------------------------------------------------------------------
    | ENCODE
    |--------------------------------------------------------------------------
    */

    /**
     * Criptografa o dado enviado
     *
     * @param   mixed       $dado   Valor a ser criptografado
     * @return  string|bool
     */
    public function encode($dado): string|bool
    {
        if (!empty($this->chavePublica)) {
            $hash = $this->encodeRsa($dado);
        } else {
            $hash = $this->encodeChave($dado);
        }

        return $this->url ? str_replace(['+', '/', '='], ['-', '_', ':'], $hash) : $hash;
    }
    private function encodeRsa($dado): string|bool
    {
        if (is_array($dado) || is_object($dado)) {
            $dado = json_encode($dado);
        }

        if (empty($dado)) {
            return '';
        }

        try {
            $status = openssl_public_encrypt($dado, $hash, $this->chavePublica);
        } catch (\Throwable) {
            return false;
        }
        if ($status) {
            return base64_encode($hash);
        }
        return false;
    }
    private function encodeChave($dado): string|bool
    {
        $cifra = $this->cifra;
        if (in_array($cifra, openssl_get_cipher_methods())) {
            return false;
        }

        if (is_array($dado) || is_object($dado)) {
            $dado = json_encode($dado);
        }

        $iv = strCodigo(openssl_cipher_iv_length($cifra));
        try {
            $hash = openssl_encrypt($dado, $cifra, $this->chave, 0, $iv);
            return $iv . $hash;
        } catch (\Throwable) {
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DECODE
    |--------------------------------------------------------------------------
    */

    /**
     * Descriptografa o hash enviado
     *
     * @param string    $hash   Hash que deve ser descriptografado
     * @return array|string|bool
     */
    public function decode(string $hash): array|string|bool
    {
        $hash = $this->url ? str_replace(['-', '_', ':'], ['+', '/', '='], $hash) : $hash;
        if (!empty($this->chavePrivada)) {
            $dado = $this->decodeRsa($hash);
        } else {
            $dado = $this->decodeChave($hash);
        }
        if ($dado === false) {
            return false;
        }

        $retorno = jsonDecode($dado, true);
        if (is_array($retorno)) {
            return $retorno;
        }
        return (string) $dado;
    }

    private function decodeRsa(string $hash): array|string|bool
    {
        if (openssl_private_decrypt(base64_decode($hash), $dado, $this->chavePrivada)) {
            return $dado;
        }
        return false;
    }
    private function decodeChave(string $hash): array|string|bool
    {
        $cifra = $this->cifra;
        if (in_array($cifra, openssl_get_cipher_methods())) {
            return false;
        }

        $iv = mb_substr($hash, 0, openssl_cipher_iv_length($cifra));
        $texto = str_replace(['-', '_', ':'], ['+', '/', '='], mb_substr($hash, openssl_cipher_iv_length($cifra), null));

        try {
            $dado = openssl_decrypt(
                data: $texto,
                cipher_algo: $cifra,
                passphrase: $this->chave,
                options: 0,
                iv: $iv
            );
            return $dado;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Gera um par de chave pública e privada
     *
     * @param int $bits Quantidade de bits da chave
     * @return array Array com a chave privada e publica ['privada' => '', 'publica' => '']
     */
    public function gerarChave(int $bits = 2048): array
    {
        $certificado = openssl_pkey_new([
            'digest_alg' => 'sha512',
            'private_key_bits' => $bits,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ]);
        openssl_pkey_export($certificado, $privada);
        $publica = openssl_pkey_get_details($certificado)['key'];

        return [
            'privada' => $privada,
            'publica' => $publica
        ];
    }
}
