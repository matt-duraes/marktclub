<?php

namespace Helpers;

use Google;
use Erro\Excecao;

final class SocialHelper
{
    private array $googleToken;

    /**
     * @param string      $rede  Qual rede social vai usar podendo ser google, facebook,
     *                           twitter, tumblr, whatsapp, reddit, pinterest ou pinterest
     * @param null|string $id    ID do usuário para o Facebook
     * @param null|string $token Token do usuário para o Facebook
     * @param null|string $code  Code para gerar o token para o Google
     */
    public function __construct(
        private ?string $rede = null,
        public ?string $id = null,
        private ?string $token = null,
        ?string $code = null
    ) {
        if ($rede == 'google' && !empty($code)) {
            $this->googleCriarTokenComAuthorizationCode($code);
        } elseif ($rede == 'google') {
            $this->googlePegarTokenDoCookie();
        } elseif ($rede == 'facebook' && !empty($id) && !empty($token)) {
            $this->facebookValidarToken();
        }
    }

    /**
     * Verifica se o usuário está logado e se o usuário tem o scope que deseja
     *
     * @param  array $scope Lista de scope que o usuário tem que ter
     * @return bool
     */
    public function logado(array $scope = []): bool
    {
        if ($this->rede != 'google') {
            mensagemErro('Erro!', 'Verifique a rede setada para continuar.');
        }

        if (!cookieExiste('GOOGLE_SOCIAL')) {
            return false;
        }

        $token = base64Decode(cookie('GOOGLE_SOCIAL'));
        if ($scope) {
            $scopeToken = is_array($token) && array_key_exists('scope', $token) ? explode(' ', $token['scope']) : [];
            foreach ($scope as $val) {
                if (!in_array($val, $scopeToken)) {
                    return false;
                }
            }
        }

        $cliente = new Google\Client();
        try {
            $cliente->setAccessToken($token);
        } catch (\Throwable) {
            return false;
        }

        if ($cliente->isAccessTokenExpired()) {
            return $this->googleRelogar($token);
        }

        $usuarioId = sessao('USUARIO.google');
        $googleId = $cliente->verifyIdToken($token['id_token'])['sub'] ?? '';
        if (empty($googleId) || empty($usuarioId) || $usuarioId != $googleId) {
            return false;
        }

        return true;
    }

    private function googleRelogar(array $token): bool
    {
        if (!array_key_exists('refresh_token', $token)) {
            return false;
        }

        $client = new Google\Client([
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri'  => env('GOOGLE_REDIRECT_URI')
        ]);

        try {
            $token = $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
        } catch (\Throwable) {
            return false;
        }

        $usuarioId = sessao('USUARIO.google');
        $googleId = $client->verifyIdToken($token['id_token'])['sub'] ?? '';
        if (empty($googleId) || empty($usuarioId) || $usuarioId != $googleId) {
            return false;
        }

        return $this->googleSetarToken($token);
    }

    /**
     * Retorna o ID do usuário para o Google e Facebook
     *
     * @return string
     */
    public function id()
    {
        if ($this->rede == 'google') {
            return $this->googlePegarId();
        } elseif ($this->rede == 'facebook') {
            return $this->id;
        }
        throw new Excecao(
            titulo: 'Erro ao pegar ID!',
            mensagem: 'Verifique o tipo de integração para pegar o ID.'
        );
    }

    public function token()
    {
        if ($this->rede == 'google') {
            return $this->googleToken['access_token'];
        } elseif ($this->rede == 'facebook') {
            return $this->token;
        }
    }

    public function metaTag(string|array $titulo, string|array $descricao, null|string|array $imagem = null)
    {
        $titulo = $this->pegarMetaReal($titulo, TITULO);
        $descricao = $this->pegarMetaReal($titulo, DESCRICAO);
        $imagem = $this->pegarMetaImagem($imagem);
        $link = LINK . '/' . URI;

        $tituloTag = $titulo == TITULO ? TITULO : $titulo . ' | ' . TITULO;

        return '
            <meta property="twitter:description" content="' . $descricao . '">
            <meta property="twitter:card" content="summary_large_image">
            <meta property="twitter:title" content="' . $titulo . '">
            ' . $imagem . '
            <link rel="canonical" href="' . $link . '">
            <meta property="og:type" content="article">
            <meta property="og:description" content="' . $descricao . '">
            <meta property="og:title" content="' . $titulo . '">
            <meta property="og:locale" content="pt_BR">
            <meta property="og:site_name" content="' . TITULO . '">
            <meta property="og:url" content="' . $link . '">
            <link rel="amphtml" href="' . $link . '">
            <meta property="ia:markup_url" content="' . $link . '">

            <meta name="title" content="' . $titulo . '">
            <meta name="description" content="' . $descricao . '">

            <title>' . $tituloTag . '</title>
        ';
    }

    private function pegarMetaReal(string|array $lista, string $padrao)
    {
        $lista = !is_array($lista) ? [$lista] : $lista;
        foreach ($lista as $valor) {
            if (!empty($valor)) {
                return $valor;
            }
        }
        return $padrao;
    }

    private function pegarMetaImagem($imagem)
    {
        $imagem = $this->pegarMetaReal($imagem, env('IMAGEM_SOCIAL', ''));
        if (!empty($imagem)) {
            return '
            <meta itemprop="image" content="' . $imagem . '">
            <meta property="og:image" content="' . $imagem . '">
            <meta property="twitter:image" content="' . $imagem . '">
            ';
        }
        return  '';
    }

    /*/
    |--------------------------------------------------------------------------
    | MÉTODO PARA COMPARTILHAMENTO
    |--------------------------------------------------------------------------
    |
    | Gera um link para compartilhar dependendo da rede
    |
    /*/
    public function compartilhar(?string $url = null, ?string $texto = null, ?string $by = null): string
    {
        if ($this->rede == 'facebook' && !empty($url)) {
            return 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
        } elseif ($this->rede == 'twitter' && !empty($url) && !empty($texto)) {
            $by = !empty($by) ? '&via=' . $by : '';
            return 'https://twitter.com/intent/tweet?text=' . $texto . '&url=' . $url . $by;
        } elseif ($this->rede == 'whatsapp' && !empty($texto)) {
            return 'whatsapp://send?text=' . urlencode($url . PHP_EOL . PHP_EOL . $texto);
        } elseif ($this->rede == 'reddit' && !empty($texto) && !empty($url)) {
            return 'http://reddit.com/submit?url=' . $url . '&amp;title=' . $texto;
        } elseif ($this->rede == 'tumblr' && !empty($url)) {
            return 'http://www.tumblr.com/share/link?url=' . $url;
        } elseif ($this->rede == 'pinterest' && !empty($texto) && !empty($url)) {
            return 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=&description=' . $texto;
        } elseif ($this->rede == 'linkedin' && !empty($texto) && !empty($url)) {
            return 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $texto;
        }
        return '';
    }

    /*/
    |--------------------------------------------------------------------------
    | GERA BLOCO DE COMENTÁRIO
    |--------------------------------------------------------------------------
    |
    | Gera o bloco de comentário do Facebook
    |
    /*/
    public function comentario(string $link, $numero = 5)
    {
        return '
            <div class="fb-comments" data-href="' . $link . '" data-width="100%" data-numposts="' . $numero . '"></div>
        ';
    }

    /*/
    |--------------------------------------------------------------------------
    | BUSCA O NÚMERO DE COMENTÁRIO
    |--------------------------------------------------------------------------
    /*/
    public function numeroComentario(string $url): int
    {
        if ($this->rede == 'facebook') {
            $ch = curl_init('https://graph.facebook.com/?ids=' . str_replace(' ', '+', $url));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

            $retorno = jsonDecode(curl_exec($ch), false);
            return isset($retorno->$url->share->comment_count) ? $retorno->$url->share->comment_count : 0;
        }

        return 0;
    }

    /*/
    |--------------------------------------------------------------------------
    | BUSCA O NÚMERO DE COMPARTILHAMENTO
    |--------------------------------------------------------------------------
    /*/
    public function numeroCompartilhamento($url): int
    {
        if ($this->rede == 'facebook') {
            $ch = curl_init('https://graph.facebook.com/?ids=' . str_replace(' ', '+', $url));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

            $retorno = jsonDecode(curl_exec($ch), false);
            return isset($retorno->$url->share->share_count) ? $retorno->$url->share->share_count : 0;
        }

        return 0;
    }

    public function imagem()
    {
        if ($this->rede == 'facebook') {
            return $this->imagemFacebook();
        } elseif ($this->rede == 'google') {
            return $this->imagemGoogle();
        }
        throw new Excecao(
            titulo: 'Erro ao pegar imagem!',
            mensagem: 'Verifique o tipo de integração para pegar a imagem.'
        );
    }

    private function imagemFacebook()
    {
        $url = 'https://graph.facebook.com/v11.0/' . $this->id . '/picture?redirect=false&access_token='
            . $this->token . '&width=300&height=300';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $retorno = jsonDecode(curl_exec($ch), true);
        curl_close($ch);

        return inKey('data.url', $retorno) ? $retorno['data']['url'] : '';
    }

    private function imagemGoogle()
    {
        $google = new Google\Client();
        $body = $google->verifyIdToken($this->googleToken['id_token']);
        if (!array_key_exists('picture', $body)) {
            mensagemErro('Erro!', 'Não foi possível pegar sua imagem do Google.');
        }

        return str_replace('s96-c', 's384-c', $body['picture']);
    }

    private function googleCriarTokenComAuthorizationCode($code)
    {
        $redirectUri = env('GOOGLE_REDIRECT_URI', '');
        if (empty($redirectUri)) {
            $redirectUri = LINK;
        }
        $client = new Google\Client([
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri'  => $redirectUri
        ]);

        $token = $client->fetchAccessTokenWithAuthCode($code);
        return $this->googleSetarToken($token);
    }

    private function googleSetarToken($token): bool
    {
        if (!is_array($token) || !array_key_exists('refresh_token', $token)) {
            $this->googleToken = [];
            return false;
        }
        cookie('GOOGLE_SOCIAL', base64Encode($token));
        $this->googleToken = $token;
        return true;
    }

    private function facebookValidarToken(): void
    {
        $appId = env('FACEBOOK_APP_ID');
        $appSecret = env('FACEBOOK_APP_SECRET');
        $url = 'https://graph.facebook.com/debug_token?input_token=' . $appId . '|' . $appSecret
            . '&access_token=' . $this->token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $retorno = jsonDecode(curl_exec($ch), true);
        curl_close($ch);
        if (
            !inKey(['data.is_valid', 'data.app_id'], $retorno) &&
            $retorno['data']['is_valid'] &&
            $retorno['data']['app_id'] == $appId
        ) {
            mensagemErro('Erro!', 'Não foi possível validar sua conta do Facebook.');
        }
    }

    private function googlePegarId()
    {
        $google = new Google\Client();
        $body = $google->verifyIdToken($this->googleToken['id_token']);

        if (!array_key_exists('sub', $body)) {
            mensagemErro('Erro!', 'Não foi possível pegar sua imagem do Google.');
        }
        return $body['sub'];
    }

    private function googlePegarTokenDoCookie()
    {
        if (!cookieExiste('GOOGLE_SOCIAL')) {
            $this->googleToken = [];
            return;
        }
        $token = base64Decode(cookie('GOOGLE_SOCIAL'));
        $this->googleToken = is_array($token) ? $token : [];
    }
}
