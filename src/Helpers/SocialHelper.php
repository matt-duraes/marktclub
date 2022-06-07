<?php

namespace Helpers;

use Erro\Excecao;
use Google_Client;

final class SocialHelper
{
    private $plataforma;

    /*/
    |--------------------------------------------------------------------------
    | MÉTODO PARA COMPARTILHAMENTO
    |--------------------------------------------------------------------------
    |
    | Gera um link para compartilhar dependendo da plataforma
    |
    /*/
    public function compartilhar(String $parametro1, String $parametro2, String $parametro3 = ''): String
    {
        if ($this->plataforma == 'facebook' && !empty($parametro1)) {
            return 'https://www.facebook.com/sharer/sharer.php?u=' . $parametro1;
        } elseif ($this->plataforma == 'twitter' && !empty($parametro1) && !empty($parametro2)) {
            $by = !empty($parametro3) ? '&via=' . $parametro3 : '';
            return 'https://twitter.com/intent/tweet?text=' . $parametro1 . '&url=' . $parametro2 . $by;
        } elseif ($this->plataforma == 'whatsapp' && !empty($parametro1) && !empty($parametro2)) {
            return 'whatsapp://send?text=' . urlencode($parametro1 . ' - ' . $parametro2);
        } elseif ($this->plataforma == 'reddit' && !empty($parametro1) && !empty($parametro2)) {
            return 'http://reddit.com/submit?url=' . $parametro2 . '&amp;title=' . $parametro1;
        } elseif ($this->plataforma == 'tumblr' && !empty($parametro1)) {
            return 'http://www.tumblr.com/share/link?url=' . $parametro1;
        } elseif ($this->plataforma == 'pinterest' && !empty($parametro1) && !empty($parametro2)) {
            return 'https://pinterest.com/pin/create/button/?url=' . $parametro2 . '&media=&description=' . $parametro1;
        } elseif ($this->plataforma == 'linkedin' && !empty($parametro1) && !empty($parametro2)) {
            return 'https://www.linkedin.com/shareArticle?mini=true&url=' . $parametro2 . '&title=' . $parametro1;
        }
    }

    /*/
    |--------------------------------------------------------------------------
    | GERA BLOCO DE COMENTÁRIO
    |--------------------------------------------------------------------------
    |
    | Gera o bloco de comentário do Facebook
    |
    /*/
    public function comentario(String $link, $numero = 5)
    {
        return '<div class="fb-comments" data-href="' . $link . '" data-width="100%" data-numposts="' . $numero . '"></div>';
    }

    /*/
    |--------------------------------------------------------------------------
    | BUSCA O NÚMERO DE COMENTÁRIO
    |--------------------------------------------------------------------------
    /*/
    public function numeroComentario(String $url): Int
    {
        if ($this->plataforma == 'facebook') {
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
    public function numeroCompartilhamento($url): Int
    {
        if ($this->plataforma == 'facebook') {
            $ch = curl_init('https://graph.facebook.com/?ids=' . str_replace(' ', '+', $url));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

            $retorno = jsonDecode(curl_exec($ch), false);
            return isset($retorno->$url->share->share_count) ? $retorno->$url->share->share_count : 0;
        }

        return 0;
    }

    /*/
    |--------------------------------------------------------------------------
    | SETA AS PLATAFORMAS
    |--------------------------------------------------------------------------
    /*/
    public function facebook()
    {
        $this->plataforma = 'facebook';
        return $this;
    }

    public function google()
    {
        $this->plataforma = 'google';
        return $this;
    }

    public function twitter()
    {
        $this->plataforma = 'twitter';
        return $this;
    }

    public function whatsapp()
    {
        $this->plataforma = 'whatsapp';
        return $this;
    }

    public function reddit()
    {
        $this->plataforma = 'reddit';
        return $this;
    }

    public function tumblr(String $url)
    {
        $this->plataforma = 'tumblr';
        return $this;
    }

    public function pinterest(String $titulo, String $url)
    {
        $this->plataforma = 'pinterest';
        return $this;
    }

    public function linkedin($titulo, $url)
    {
        $this->plataforma = 'linkedin';
        return $this;
    }

    /**
     * @param String $id    token_id, access_token ou similiar da plataforma
     */
    public function validarToken(string $token): bool
    {
        if ($this->plataforma == 'facebook') {
            return $this->validarTokenFacebook($token);
        } elseif ($this->plataforma == 'google') {
            return $this->validarTokenGoogle($token);
        }
        throw new Excecao(
            titulo: 'Erro ao validar token!',
            mensagem: 'Verifique o tipo de integração para validar o token.'
        );
    }

    private function validarTokenFacebook($token): bool
    {
        $appId = env('FACEBOOK_APP_ID');
        $appSecret = env('FACEBOOK_APP_SECRET');
        $url = 'https://graph.facebook.com/debug_token?input_token=' . $appId . '|' . $appSecret . '&access_token=' . $token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $retorno = jsonDecode(curl_exec($ch), true);
        curl_close($ch);
        return inKey(['data->is_valid', 'data->app_id'], $retorno) &&
            $retorno['data']['is_valid'] &&
            $retorno['data']['app_id'] == $appId;
    }

    private function validarTokenGoogle($token): bool
    {
        $google = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $google->verifyIdToken($token);
        return inKey('sub', $payload) && !empty($payload['sub']);
    }

    public function imagem($id, string $token)
    {
        if ($this->plataforma == 'facebook') {
            return $this->imagemFacebook($id, $token);
        } elseif ($this->plataforma == 'google') {
            return $this->imagemGoogle($id, $token);
        }
        throw new Excecao(
            titulo: 'Erro ao pegar imagem!',
            mensagem: 'Verifique o tipo de integração para pegar a imagem.'
        );
    }

    private function imagemFacebook($id, $token)
    {
        $url = 'https://graph.facebook.com/v11.0/' . $id . '/picture?redirect=false&access_token=' . $token . '&width=300&height=300';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $retorno = jsonDecode(curl_exec($ch), true);
        curl_close($ch);

        return inKey('data->url', $retorno) ? $retorno['data']['url'] : '';
    }

    private function imagemGoogle($id, $token)
    {
        $google = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $google->verifyIdToken($token);
        if ($payload['sub'] != $id) {
            throw new Excecao(
                titulo: 'Erro ao validar imagem!',
                mensagem: 'Não foi possível validar o proprietário da imagem.'
            );
        }
        return str_replace('s96-c', 's384-c', $payload['picture']);
    }
}
