<?php

namespace Helpers;

use Throwable;
use Erro\Excecao;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function base64_decode;

final class JwtHelper
{
    private string $algoritimo = 'HS256';
    private string $chavePublica;
    private string $chavePrivada;

    /**
     * @param  string|null $chave Nome do arquivo da chave RSA
     * @param  string|null $hash  Hash quando for usar RS256
     * @throws Excecao
     */
    public function __construct(
        private readonly ?string $chave = null,
        private ?string $hash = null
    ) {
        if (!empty($chave)) {
            $this->algoritimo = 'RS256';
            $this->pegarChave();
            return;
        }
        $this->hash = !is_null($hash) ? $hash : ENV('JWT_HASH', '');
    }

    /**
     * @throws Excecao
     */
    private function pegarChave(): void
    {
        $chave = $this->chave;
        if (
            file_exists(DIRETORIO_PRIVADO . '/jwt/' . $chave) && file_get_contents(
                DIRETORIO_PRIVADO . '/jwt/' . $chave
            )
        ) {
            $this->chavePrivada = file_get_contents(DIRETORIO_PRIVADO . '/jwt/' . $chave);
        } else {
            mensagemErro('Erro!', 'Chave privada não existe.', 500);
        }
        if (
            file_exists(DIRETORIO_PRIVADO . '/jwt/' . $chave) && file_get_contents(
                DIRETORIO_PRIVADO . '/jwt/' . $chave . '.pub'
            )
        ) {
            $this->chavePublica = file_get_contents(DIRETORIO_PRIVADO . '/jwt/' . $chave . '.pub');
        } else {
            mensagemErro('Erro!', 'Chave pública não existe.', 500);
        }
    }

    /**
     * Cria um token JWT
     *
     * @param  array   $payload Array com os dados que deseja colocar no body do JWT
     * @return string  String com o JWT
     * @throws Excecao
     */
    public function encode(array $payload): string
    {
        $key = !empty($this->chavePrivada) ? $this->chavePrivada : $this->hash;
        try {
            return JWT::encode($payload, $key, $this->algoritimo, null, ['kid' => uuid()]);
        } catch (Throwable) {
            mensagemErro('Erro!', 'Ocorreu um erro ao criar o JWT.', 500);
        }
    }

    /**
     * Valida se um JWT é valido
     *
     * @param  string $jwt JWT que deseja validar
     * @return bool
     */
    public function validar(string $jwt): bool
    {
        try {
            $this->decode($jwt);
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Pega o body do JWT
     *
     * @param  string  $jwt JWT que deseja retornar
     * @return array   Array do body
     * @throws Excecao
     */
    public function decode(string $jwt): array
    {
        $key = !empty($this->chavePublica) ? $this->chavePublica : $this->hash;
        try {
            $dados = JWT::decode($jwt, new Key($key, $this->algoritimo));
        } catch (Throwable $e) {
            mensagemErro(
                'Erro!',
                'O Token enviado não tem um corpo válido.',
                401,
                localhost: 'Erro no decode do JWT: ' . $e->getMessage()
            );
        }

        return (array)$dados;
    }

    /**
     * Pega o header do JWT
     *
     * @param  string $jwt JWT que deseja pegar o header
     * @return array  Array com o header
     */
    public function header(string $jwt): array
    {
        $explode = explode('.', $jwt);

        if (count($explode) != 3) {
            return [];
        }

        $dado = jsonDecode(base64_decode($explode[0]), true);
        return is_array($dado) ? $dado : [];
    }
}
