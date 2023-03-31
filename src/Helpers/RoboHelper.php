<?php

namespace Helpers;

use Erro\Erro;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;

class RoboHelper
{
    private HttpBrowser $Client;
    private Crawler $Crawler;
    private $ElementoAtual = null;
    private $elementoIndice = null;
    private string $metodo;

    /**
     * @param Array     $cookie         Cookie para o Robo
     * @param Array     $historico      Histórico para o Robo
     * @param Int       $timeout        Timeout que o Crawler vai esperar por resposta
     * @param String    $userAgent      UserAgent que o Crawler irá se passar
     */
    public function __construct(
        array $cookie = [],
        array $historico = [],
        int $timeout = 60,
        string $userAgent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0'
    ) {
        $this->Client = new HttpBrowser(
            HttpClient::create(['timeout' => $timeout]),
            $this->montarHistorico($historico),
            $this->montarCookie($cookie)
        );
        if (!empty($userAgent)) {
            $this->Client->setServerParameter('HTTP_USER_AGENT', $userAgent);
        }
    }

    private function montarHistorico(array $historico): ?array
    {
        return null;
    }

    /**
     * @param Array     $cookie     Array com a lista de Cookie que precisa ser setada
     */
    private function montarCookie(array $cookie): ?CookieJar
    {
        if (empty($cookie)) {
            return null;
        }
        $cookieJar = new CookieJar();
        foreach ($cookie as $r) {
            $r = (object) $r;
            $cookieJar->set(new Cookie(
                $r->name,
                $r->value,
                $r->expires ?? null,
                $r->path ?? null,
                $r->domain ?? '',
                $r->secure ?? false,
                $r->httpOnly ?? true,
                $r->encodedValue ?? false,
                $r->sameSite ?? null
            ));
        }
        return $cookieJar;
    }

    /*
    |--------------------------------------------------------------------------
    | METODOS PARA PEGAR INFORMAÇÕES DO CRAWLER
    |--------------------------------------------------------------------------
    */
    public function cliente(): HttpBrowser
    {
        return $this->Client;
    }

    public function robo(): Crawler
    {
        return $this->Crawler;
    }

    public function url(): string
    {
        return $this->Crawler->getUri();
    }

    public function status(): int
    {
        return $this->Client->getResponse()->getStatusCode();
    }
    public function metodo(): string
    {
        return $this->metodo;
    }

    public function cookie(): array
    {
        $Cookie = $this->Client->getCookieJar()->all();

        $array = [];
        foreach ($Cookie as $r) {
            $array[] = [
                'name' => $r->getName(),
                'value' => $r->getValue(),
                'expires' => $r->getExpiresTime(),
                'path' => $r->getPath(),
                'domain' => $r->getDomain(),
                'secure' => $r->isSecure(),
                'httpOnly' => $r->isHttpOnly(),
                'encodedValue' => $r->getRawValue(),
                'sameSite' => $r->getSameSite(),
            ];
        }
        return $array;
    }

    /*
    |--------------------------------------------------------------------------
    | REQUISIÇÃO
    |--------------------------------------------------------------------------
    */

    /**
     * Faz uma requisição usando método GET
     *
     * @param string    $link   Link que dese ser enviado
     * @param array     $dado   Dado para ser enviado na query
     */
    public function get(string $link, array $dado = []): self
    {
        $query = [];
        foreach ($dado as $ind => $val) {
            if (is_string($val) || is_object($val)) {
                $val = json_encode($val);
            } elseif (!is_string($val)) {
                $val = (string) $val;
            }
            $query[] = $ind . '=' . urlencode($val);
        }
        if ($query && str_contains($link, '?') && preg_match('/\?$/', $link)) {
            $link .= implode('&', $query);
        } elseif ($query && str_contains($link, '?')) {
            $link .= '&' . implode('&', $query);
        } elseif ($query) {
            $link .= '?' . implode('&', $query);
        }

        $this->metodo = 'GET';
        $this->requisicao($link, metodo: 'GET');
        return $this;
    }
    public function post(string $link, array $dado = [])
    {
        $this->metodo = 'POST';
        $this->requisicao($link, $dado, 'POST');
        return $this;
    }
    private function requisicao(
        string $link,
        array $dado = [],
        string $metodo = 'GET',
    ) {
        $this->ElementoAtual = null;
        if ($metodo == 'POST') {
            $this->Crawler = $this->Client->request($metodo, $link, $dado);
        } else {
            $this->Crawler = $this->Client->request($metodo, $link);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA ELEMENTOS
    |--------------------------------------------------------------------------
    */
    /**
     * Pega um elemento
     *
     * @param string    $elemento   Seletor CSS do elemento que deseja pegar
     * @param int       $indice     Pela apenas 1 elemento pelo indice informado
     * @return self
     */
    public function elemento(string $elemento, ?int $indice = null): self
    {
        if (is_int($indice)) {
            $this->elementoIndice = true;
            $this->ElementoAtual = $this->Crawler->filter($elemento)->eq($indice);
        } else {
            $this->elementoIndice = false;
            $this->ElementoAtual = $this->Crawler->filter($elemento);
        }
        return $this;
    }

    /**
     * Pega um elemento pai do elemento do this elemento
     *
     * @param string $elemento  Seletor CSS do elemento pai
     * @return self
     */
    public function pai(string $elemento): self
    {
        $this->ElementoAtual = $this->ElementoAtual->closest($elemento);
        return $this;
    }

    /**
     * Clica em um this elemento que tenha um href
     *
     * @return bool|self
     */
    public function click(): bool|RoboHelper
    {
        try {
            $link = $this->ElementoAtual->attr('href');
            if (!filter_var($link, FILTER_VALIDATE_URL)) {
                return false;
            }
            $this->requisicao($link);
            return $this;
        } catch (Erro) {
            return false;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | TRATA FORMULÁRIOS
    |--------------------------------------------------------------------------
    */
    /**
     * Envia um formulário
     */
    public function enviar(): self
    {
        $this->Crawler = $this->Client->submit($this->ElementoAtual);
        return $this;
    }

    /**
     * Pega ou seta um valor do formulário
     *
     * @param array     $dado       Array com a propriedade e valor a ser setado
     * @param string    $indice     Indice para pegar um valor
     * @return string|array|bool|self
     */
    public function form(?array $dado = null, ?string $indice = null): string|array|bool|RoboHelper
    {
        if (!$this->ElementoAtual->matches('form')) {
            throw new \Exception('O elemento não é um formulário.');
        }

        if (is_array($dado) && $dado) {
            $this->setarValor($dado);
            return $this;
        }
        return $this->pegarValor($indice);
    }

    private function setarValor(array $dado)
    {
        try {
            $this->ElementoAtual->form()->setValues($dado);
        } catch (Erro $erro) {
            throw new $erro('Erro em setar os parametros do formulário.');
        }
    }

    private function pegarValor(?string $indice): string|array|bool
    {
        $form = $this->ElementoAtual;
        $valor = $form->form()->getValues();

        if (!is_array($valor)) {
            return false;
        } elseif (!empty($indice) && isset($valor[$indice])) {
            return $valor[$indice];
        }
        return $valor;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA ELEMENTOS
    |--------------------------------------------------------------------------
    */
    /**
     * Pega um atributo do this elemento
     *
     * @param string $atributo  Atributo que deseja pegar
     * @return string|array String quando for passado o indice do elemento ou array com a lista de todos os elementos
     */
    public function attr(string $atributo): string|array
    {
        if ($this->elementoIndice) {
            return $this->ElementoAtual->attr($atributo);
        } else {
            return $this->ElementoAtual->each(function ($node) use ($atributo) {
                return $node->attr($atributo);
            });
        }
    }

    /**
     * Pega o texto de um this elemento
     *
     * @return string|array String quando for passado o indice do elemento ou array com a lista de todos os elementos
     */
    public function texto(): string|bool|array
    {
        if ($this->elementoIndice) {
            try {
                return trim($this->ElementoAtual->text());
            } catch (Erro) {
                return false;
            }
        } else {
            try {
                return $this->ElementoAtual->each(function ($node) {
                    return trim($node->text());
                });
            } catch (Erro) {
                return false;
            }
        }
    }

    /**
     * Pega os links do this elemento
     *
     * @return string|array String quando for passado o indice do elemento ou array com a lista de todos os elementos
     */
    public function link(): string|array|bool
    {
        if ($this->elementoIndice) {
            try {
                if ($this->ElementoAtual->matches('a')) {
                    return $this->ElementoAtual->attr('href');
                }
                return false;
            } catch (\Throwable $th) {
                return false;
            }
        } else {
            try {
                $lista = $this->ElementoAtual->each(function ($node) {
                    if ($node->matches('a')) {
                        return $node->attr('href');
                    } else {
                        return false;
                    }
                });
                $array = [];
                if ($lista) {
                    foreach ($lista as $link) {
                        if (false !== $link) {
                            $array[] = $link;
                        }
                    }
                }
                return $array;
            } catch (\Throwable $th) {
                return false;
            }
        }
    }

    /**
     * Pega as imagens do this elemento
     *
     * @return string|array String quando for passado o indice do elemento ou array com a lista de todos os elementos
     */
    public function imagem(): bool|string|array
    {
        $imagem = $this->ElementoAtual->images();
        if ($this->elementoIndice) {
            if ($imagem) {
                return $imagem[0]->getUri();
            }
            return false;
        } elseif ($imagem) {
            $lista = [];
            foreach ($imagem as $node) {
                $lista[] = $node->getUri();
            }
            return $lista;
        }
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA O HTML
    |--------------------------------------------------------------------------
    */
    /**
     * Verifica se o texto existe no body
     *
     * @param string $texto Texto que deseja buscar
     * @return bool
     */
    public function textoExiste(string $texto): bool
    {
        $textoFiltro = $this->Crawler->filter('body')->text();
        return preg_match('/' . $texto . '/', $textoFiltro);
    }

    /**
     * Pega ou seta um HTML
     *
     * @param ?string    $html       Html para ser inserido
     */
    public function html(?string $html = null): self|string|false
    {
        if (!is_null($html)) {
            $this->addHtml($html);
            return $this;
        }
        return $this->pegarHtml();
    }

    private function pegarHtml()
    {
        if (is_null($this->ElementoAtual)) {
            return $this->Crawler->html();
        }
        try {
            return $this->ElementoAtual->html();
        } catch (Erro) {
            return false;
        }
    }

    private function addHtml($html)
    {
        if (is_null($this->ElementoAtual)) {
            $this->Crawler->addHtmlContent($html);
        }
        try {
            $this->ElementoAtual->addHtmlContent($html);
        } catch (Erro) {
            return false;
        }
    }

    /**
     * Clica em um link pelo texto do link
     *
     * @param string        $texto      Texto do link que deseja clicar
     */
    public function clickLink($texto)
    {
        $this->Crawler = $this->Client->clickLink($texto);
    }
}
