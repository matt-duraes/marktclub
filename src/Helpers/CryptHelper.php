<?php

namespace Helpers;

use Throwable;

final class CryptHelper
{
    /**
     * @param  string|null  $chave         Chave para criptografar, ENV('CRYPT_HASH') por padrão
     * @param  string|null  $cifra
     * @param  string|null  $chavePublica  Chave pública caso queira usar RSA
     * @param  string|null  $chavePrivada  Chave privada caso queira usar RSA
     * @param  bool         $url           Se vai converter o encode em URL
     */
    public function __construct(
        private ?string $chave = null,
        private ?string $cifra = null,
        private readonly ?string $chavePublica = null,
        private readonly ?string $chavePrivada = null,
        private readonly bool $url = false
    ) {
        if (empty($chave)) {
            $this->chave = ENV('CRYPT_HASH');
        }

        if (empty($this->chave)) {
            $this->chave = '3876b388a5d5a2417af13bc7d6335925c5e82695bf84873a3c1a2b34fb918a5a';
        }

        $this->cifra = !empty($cifra) ? $cifra : 'AES-256-CBC';
    }

    /**
     * Criptografa os dados enviados
     *
     * @param  mixed  $dados  Dados a serem criptografado
     * @return  string|bool
     */
    public function encode(mixed $dados): string|bool
    {
        if (!empty($this->chavePublica)) {
            $hash = $this->encodeRsa($dados);
        } else {
            $hash = $this->encodeChave($dados);
        }

        return $this->url ? str_replace(['+', '/', '='], ['-', '_', ':'], $hash) : $hash;
    }

    /**
     * Criptografa os dados enviados
     *
     * @param  mixed  $dados  Dados a serem criptografado
     * @return string|bool
     */
    private function encodeRsa(mixed $dados): string|bool
    {
        if (empty($dados)) {
            return '';
        }

        if (is_array($dados) || is_object($dados)) {
            $dados = json_encode($dados);
        }

        try {
            $status = openssl_public_encrypt($dados, $hash, $this->chavePublica);
        } catch (Throwable) {
            return false;
        } finally {
            if ($status) {
                return base64_encode($hash);
            }

            return false;
        }
    }

    private function encodeChave(mixed $dados): string|bool
    {
        if (in_array($this->cifra, openssl_get_cipher_methods())) {
            return false;
        }

        if (is_array($dados) || is_object($dados)) {
            $dados = json_encode($dados);
        }

        $iv = strCodigo(openssl_cipher_iv_length($this->cifra));

        try {
            $hash = openssl_encrypt($dados, $this->cifra, $this->chave, 0, $iv);
            return $iv . $hash;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Descriptografa o hash enviado
     *
     * @param  string  $hash  Hash que deve ser descriptografado
     * @return array|string|bool
     */
    public function decode(string $hash): array|string|bool
    {
        $hash = $this->url ? str_replace(['-', '_', ':'], ['+', '/', '='], $hash) : $hash;

        if (!empty($this->chavePrivada)) {
            $dados = $this->decodeRsa($hash);
        } else {
            $dados = $this->decodeChave($hash);
        }

        if ($dados === false) {
            return false;
        }

        $retorno = jsonDecode($dados, true);

        if (is_array($retorno)) {
            return $retorno;
        }

        return (string)$dados;
    }

    /**
     * Descriptografa o hash enviado
     *
     * @param  string  $hash  Hash que deve ser descriptografado
     * @return array|string|bool
     */
    private function decodeRsa(string $hash): array|string|bool
    {
        if (openssl_private_decrypt(base64_decode($hash), $dado, $this->chavePrivada)) {
            return $dado;
        }
        return false;
    }

    /**
     * Descriptografa o hash enviado
     *
     * @param  string  $hash  Hash que deve ser descriptografado
     * @return array|string|bool
     */
    private function decodeChave(string $hash): array|string|bool
    {
        if (in_array($this->cifra, openssl_get_cipher_methods())) {
            return false;
        }

        $iv = mb_substr($hash, 0, openssl_cipher_iv_length($this->cifra));
        $texto = str_replace(
            ['-', '_', ':'],
            ['+', '/', '='],
            mb_substr($hash, openssl_cipher_iv_length($this->cifra), null)
        );

        try {
            return openssl_decrypt(
                data: $texto,
                cipher_algo: $this->cifra,
                passphrase: $this->chave,
                iv: $iv
            );
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Gera um par de chave pública e privada
     *
     * @param  int  $bits  Quantidade de bits da chave
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
