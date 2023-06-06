<?php

namespace App\Models\Oauth\Fenae;

use Helpers\CurlHelper;
use League\OAuth2\Client\Provider\GenericProvider;

trait ProviderTrait
{
    private array $configuracao;
    private GenericProvider $provider;
    private string $authorizationUrl;
    private string $sessionEndUrl;

    /**
     * Seta um o provide da FENAE
     */
    private function setarProvider()
    {
        $this->pegarConfiguracao();
        $this->provider = new GenericProvider([
            'clientId'                => env('FENAE_CLIENT_ID'),
            'clientSecret'            => env('FENAE_CLIENT_SECRET'),
            'redirectUri'             => env('FENAE_REDIRECT_URI'),
            'urlAuthorize'            => $this->configuracao['authorization_endpoint'],
            'urlAccessToken'          => $this->configuracao['token_endpoint'],
            'urlResourceOwnerDetails' => $this->configuracao['userinfo_endpoint'],
            'pkceMethod'              => GenericProvider::PKCE_METHOD_S256,
            'scopes'                  => env('FENAE_SCOPE')
        ]);
        $this->authorizationUrl = $this->provider->getAuthorizationUrl();
        $this->sessionEndUrl = preg_replace('/\/{1,}$/', '', $this->configuracao['end_session_endpoint'])
        . '?post_logout_redirect_uri=' . env('FENAE_POST_LOGOUT_REDIRECT_URI')
        . '&state=' . $this->provider->getState();
    }

    private function pegarConfiguracao()
    {
        if (!sessaoExiste('FENAE_API_CONFIGURACAO')) {
            $Curl = new CurlHelper();
            $configuracao = $Curl->get(env('FENAE_LINK_CONFIGURACAO'))->array();
            sessao('FENAE_API_CONFIGURACAO', $configuracao);
        }
        $this->configuracao = sessao('FENAE_API_CONFIGURACAO');
    }
    private function pegarSessionEndpoint()
    {
        // end_session_endpoint
        // 302
        // https://localhost:44418/connect/endsession?post_logout_redirect_uri=https%3A%2F%2Flocalhost%3A44418%2Fapi%2Fdevelopment%2Ftest-client%2Fbff%2Fauth%2Flogout-callback&id_token_hint=eyJhbGciOiJSUzI1NiIsImtpZCI6IjNFRjY3RjBENDY0MjdDRjdGODU0NkZFNERDOUI0RTQ3M0ZENDU3ODciLCJ0eXAiOiJKV1QiLCJjdHkiOiJKV1QiLCJ4NXQiOiJQdlpfRFVaQ2ZQZjRWR19rM0p0T1J6X1VWNGMifQ.eyJuYmYiOjE2ODYwMDU5NzYsImV4cCI6MTY4NjAwNjI3NiwiaXNzIjoiaHR0cHM6Ly9sb2NhbGhvc3Q6NDQ0MTgiLCJhdWQiOiJ0ZXN0LWNsaWVudC5iZmYiLCJub25jZSI6IjYzODIxNjAyNzY5MjMzNTI4Ni5NV1U1WVdabVl6a3RPV0UzTVMwMFpHTmlMV0k1T0dFdE1XTTVZV0ZpT0RjMU1UaGlObVF4TlRFMU1qTXROREptTlMwMFlUQmpMV0kyWm1ZdE5XSm1NMlkyTmpZMFpHSXgiLCJpYXQiOjE2ODYwMDU5NzUsImF0X2hhc2giOiJrcW9EbVZQQUc4VU9UX1ZLMHkxeWFRIiwic19oYXNoIjoiR0tWdmtUaklRaFcwVW9oV0lMd0NwUSIsInNpZCI6IjYyNTkzNzVkLTNhZmQtNDJhZS05YTM1LTNlYTI0Nzc5YjBlNCIsInN1YiI6IjdiNGU0ZmE0LWE0MjYtNDFjYi1kNzljLTA4ZGIzNmM5ODk4MyIsImF1dGhfdGltZSI6MTY4NjAwNTk3NCwiaWRwIjoibG9jYWwiLCJuYW1lIjoiVElUVUxBUiAxODgwMjg5MDA4MCBGQUtFIExPR0lOIEZFTkFFIiwiY3BmIjoiMTg4MDI4OTAwODAiLCJiaXJ0aGRhdGUiOiIxOTgwLTEwLTI0IiwicGljdHVyZSI6Imh0dHBzOi8vbG9jYWxob3N0OjQ0NDE4L2Nvbm5lY3QvcHJvZmlsZS9waWN0dXJlP3N1Yj03YjRlNGZhNGE0MjY0MWNiZDc5YzA4ZGIzNmM5ODk4MyIsImVtYWlsIjoiMTg4MDI4OTAwODBAeW9wbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6ZmFsc2UsImdlbmRlciI6Im1hbGUiLCJhbXIiOlsicHdkIl19.T6D5BMhaFNNG78o_XsNPmr5905RAQG5ix41y5tyBSRAEj3OdSINDOPWAjRSRi-VswPV6jk98d4KCYmuNeP2cgwgY8Vw_OK1BP-qfqWBFKMe2a7rdpWgDK2p-sNt9VZwzEOlpBqpTiYtn0-uMQ-eAazkR-oh1C8-2ussVCZDgSq1Cb5Er-L-ZTwuW0kp-d47j6fxoEbBojhRbPXlmyXBNw1jvzafD-xKfl6CKkwo1AaG5cUuo-lnCoToJJP3K7u11y42AUzHf6pk57jg3baWv2msETY-In1U3t17GuIQTrxA16UZWhBgckOJ-ZF6sR8WiAbuGCDfTUtWGMVYAtxrcKg&state=CfDJ8E4J6bmNJntDtYdqc5MS8m_euWPMxiaBnQpxq2qheDECOEQc_72VeZDslpTNts5_l3WvM5VLe7Kn3t1BkPF2x0RYedNqlAReKld_LauV0CiBGkZCo9N4l7M7ihTKn_76Yb-VNhqhXWDEdXCEu20cC12LJ6qnqVthHAbWP39TxobWdCR-ktyhwA5PRKxgu7kQtg&x-client-SKU=ID_NETSTANDARD2_0&x-client-ver=6.15.1.0
        // https://localhost:44418/api/development/test-client/bff/auth/logout-frontchannel?sid=2ef3be03-746f-462d-83a5-0cbc689326cf&iss=https%3A%2F%2Flocalhost%3A44418
    }
}
