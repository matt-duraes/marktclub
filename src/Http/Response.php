<?php

namespace Http;

use stdClass;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as Psr7Response;

final class Response extends Psr7Response
{
    private Psr7Response $responseBody;
    private JsonResponse $responseJson;
    private RedirectResponse $responseLocation;
    private BinaryFileResponse $responseFile;
    private string $tipo;

    // doc
    /**
     * O response pode ser retornado diretamente do seu construtor, mas, nada impede de usar um método para enviar a resposta adequada, por isso mesmo todos os seus métodos são opcionais
     *
     * @param string            $body       Body para respostas com string
     * @param stdClass|array    $json       Array ou Object para retorno em formato de JSON
     * @param string            $url        URL para fazer o location do usuário
     * @param string            $download   Path de um arquivo para downlaod
     * @param string            $arquivo    Path de um arquivo para mostrar direto no navegador
     * @param array             $header     Header para resposta no formato ['indice' => 'valor']
     * @param int               $status     Status HTML entre 100 e 399
     */
    public function __construct(
        private string $body = '',
        private stdClass|array $json = [],
        private string $url = '',
        private string $download = '',
        private string $arquivo = '',
        private array $header = [],
        private int $status = 200,
    ) {
        if (!empty($this->json)) {
            $this->json($this->json, $this->status, $this->header);
            return;
        } elseif (!empty($this->url)) {
            $status = $this->status == 200 ? 302 : $this->status;
            $this->location($this->url, $status, $this->header);
            return;
        } elseif (!empty($this->download)) {
            $this->download(arquivo: $this->download, status: $this->status, header: $this->header);
            return;
        } elseif (!empty($this->arquivo)) {
            $this->arquivo(arquivo: $this->arquivo, status: $this->status, header: $this->header);
            return;
        }
        $this->tipo = 'responseBody';
        $this->responseBody = new Psr7Response($this->body, $this->status, $this->header);
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $tipo = $this->tipo;
        if ($tipo == 'responseBody') {
            return $this->responseBody->send();
        } elseif ($tipo == 'responseJson') {
            return $this->responseJson->send();
        } elseif ($tipo == 'responseLocation') {
            return $this->responseLocation->send();
        } elseif ($tipo == 'responseFile') {
            return $this->responseFile->send();
        }
    }

    // doc
    /**
     * Retorna a classe origin Symfony\Component\HttpFoundation\{Response,JsonResponse,RedirectResponse,BinaryFileResponse} dependendo do tipo de resposta solicitada
     *
     * @return Symfony\Component\HttpFoundation\{Response,JsonResponse,RedirectResponse,BinaryFileResponse} Classe original do Symfony dependendo do tipo de resposta solicitada
     */
    public function response(): Psr7Response
    {
        $tipo = $this->tipo;
        return $this->$tipo;
    }

    // doc
    /**
     * Seta os Cookie para a resposta
     *
     * @param array $cookie     Lista de cookie no padrão ['nome_cookie' => ['value' => '', 'secure' => '', 'domain' => '', 'expire' => '', 'httpOnly' => '', 'raw' => '', 'path' => '', 'sameSite' => '']]
     * @return self
     */
    public function cookie(array $cookie): self
    {
        $tipo = $this->tipo;
        foreach ($cookie as $ind => $val) {
            $value = $val;
            $secure = true;
            $domain = $_SERVER['HTTP_HOST'];
            $expire = strtotime('+30 minutes');
            $httpOnly = true;
            $raw = false;
            $path = '/';
            $sameSite = 'lax';
            if (is_array($val)) {
                $value = $val['value'] ?? '';
                $secure = $val['secure'] ?? $secure;
                $domain = $val['domain'] ?? $domain;
                $expire = $val['expire'] ?? $expire;
                $httpOnly = $val['httpOnly'] ?? $httpOnly;
                $raw = $val['raw'] ?? $raw;
                $path = $val['path'] ?? $path;
                $sameSite = $val['sameSite'] ?? $sameSite;
            }
            $this->$tipo->headers->setCookie(
                new Cookie(
                    name: $ind,
                    value: $value,
                    path: $path,
                    secure: $secure,
                    domain: $domain,
                    expire: $expire,
                    httpOnly: $httpOnly,
                    raw: $raw,
                    sameSite: $sameSite
                )
            );
        }
        return $this;
    }

    // doc
    /**
     * Seta o valor do Status HTTP da página
     *
     * @param   int     $status     Status podendo ser de 100 a 399
     * @return  Self
     * @throws  Erro\Execao Execão caso passa um status fora do range permitido
     */
    public function status(int $status): self
    {
        if ($status < 100 || $status > 399) {
            mensagemStatus(400, localhost: 'Você deve passar um Status entre 100 e 399 para a resposta.');
        }
        $tipo = $this->tipo;
        $this->$tipo->setStatusCode($status);
        return $this;
    }

    // doc
    /**
     * Seta um header para o retorno no padrao ['header' => 'valor']
     *
     * @param array         $header     Header do cabeçalho
     * @return Self
     */
    public function header(array $header): self
    {
        $tipo = $this->tipo;
        foreach ($header as $ind => $val) {
            $this->$tipo->headers->set($ind, $val);
        }
        return $this;
    }

    // doc
    /**
     * Retorna um JSON na response
     *
     * @param stdClass|array    $array      Array ou stdClass que deseja retornar
     * @param int               $status     Status para o retorno
     * @param array             $header     Header do cabeçalho
     * @return self
     */
    public function json(stdClass|array $array, int $status = 200, array $header = []): self
    {
        $this->tipo = 'responseJson';
        $this->responseJson = new JsonResponse($array, $status, $header);
        $this->responseJson->headers->set('Content-Type', 'application/json');
        return $this;
    }

    // doc
    /**
     * Retorna um JSON com padrão para JS
     *
     * @return self
     */
    public function js(): self
    {
        $this->responseJson->setCallback('handleResponse');
        return $this;
    }

    // doc
    /**
     * Retorna um location para uma URL informada
     *
     * @param string        $url        A URL que dese ser redirecionada
     * @param int           $status     Status HTTP que deve ser usando podendo ser: 301 = URL mudou, você não deve mais usar essa URL e sim a nova; 302 = Movida temporariamente, continue usando a mesma URL; 307 = Igual a 302 mas mantem o método usado; 308 = Igual a 301 mas mantem o método usado.
     * @param array         $header     Header para ser usando no location
     * @return self
     * @throws  Erro\Execao     Exceção caso o status não seja um dos listados
     */
    public function location(string $url, int $status = 302, array $header = []): self
    {
        $this->tipo = 'responseLocation';
        if (!in_array($status, [301, 302, 307, 308])) {
            mensagemStatus(400, localhost: 'O status para redirecionamento deve ser 301, 302, 307 ou 308.');
        }
        $location = new RedirectResponse($url, $status, $header,);
        $this->responseLocation = $location;
        return $this;
    }

    // doc
    /**
     * Faz o download de um arquivo
     *
     * @param string    $arquivo    Path do arquivo que deseja fazer download
     * @param int       $status     Status para o header
     * @param array     $header     Header do cabeçalho
     * @return Self
     */
    public function download(string $arquivo, int $status = 200, array $header = []): self
    {
        $this->tipo = 'responseFile';
        $nome = pathinfo($arquivo, PATHINFO_BASENAME);
        $mimeType = mime_content_type($arquivo);

        $this->responseFile = new BinaryFileResponse($arquivo, $status, $header);
        $this->responseFile->headers->set('Content-Type', $mimeType);
        $this->responseFile->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $nome);
        return $this;
    }

    // doc
    /**
     * Abre um arquivo no navegador
     *
     * @param string    $arquivo    Path do arquivo
     * @param int       $status     Status para o header
     * @param array     $header     Header do cabeçalho
     * @return Self
     */
    public function arquivo(string $arquivo, int $status = 200, array $header = []): self
    {
        if (!file_exists($arquivo)) {
            mensagemStatus(status: 404, localhost: 'Arquivo não foi encontrado.');
        }

        $mimeType = mime_content_type($arquivo);

        $this->tipo = 'responseFile';
        $nome = pathinfo($arquivo, PATHINFO_BASENAME);

        $this->responseFile = new BinaryFileResponse($arquivo, $status, $header);
        $this->responseFile->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $nome);
        $this->responseFile->headers->set('Content-Type', $mimeType);
        return $this;
    }
}
