<?php

namespace Http;

use Erro\Excecao;
use stdClass;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as Psr7Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class Response extends Psr7Response
{
    private Psr7Response $responseBody;
    private JsonResponse $responseJson;
    private RedirectResponse $responseLocation;
    private BinaryFileResponse $responseFile;
    private string $tipo;

    // doc

    /**
     * O response pode ser retornado diretamente do seu construtor, mas, nada impede de usar um método para enviar a
     * resposta adequada, por isso mesmo todos os seus métodos são opcionais
     *
     * @param  string          $body      Body para respostas com string
     * @param  stdClass|array  $json      Array ou Object para retorno em formato de JSON
     * @param  string          $url       URL para fazer o location do usuário
     * @param  string          $download  Path de um arquivo para downlaod
     * @param  string          $arquivo   Path de um arquivo para mostrar direto no navegador
     * @param  array           $header    Header para resposta no formato ['indice' => 'valor']
     * @param  int             $status    Status HTML entre 100 e 399
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly string $body = '',
        private readonly stdClass|array $json = [],
        private readonly string $url = '',
        private readonly string $download = '',
        private readonly string $arquivo = '',
        private readonly array $header = [],
        private readonly int $status = 200
    ) {
        if (!empty($this->json)) {
            $this->json($this->json, $this->status, $this->header);
        } elseif (!empty($this->url)) {
            $status = ($this->status == 200) ? 302 : $this->status;
            $this->location($this->url, $status, $this->header);
        } elseif (!empty($this->download)) {
            $this->download($this->download, $this->status, $this->header);
        } elseif (!empty($this->arquivo)) {
            $this->arquivo($this->arquivo, $this->status, $this->header);
        }

        $this->tipo = 'responseBody';
        $this->responseBody = parent::__construct($this->body, $this->status, $this->header);
    }

    /**
     * Retorna um JSON na response
     *
     * @param  stdClass|array  $dados   Array ou stdClass que deseja retornar
     * @param  int             $status  Status para o retorno
     * @param  array           $header  Header do cabeçalho
     *
     * @return self
     */
    public function json(stdClass|array $dados, int $status = 200, array $header = []): self
    {
        $this->tipo = 'responseJson';
        $this->responseJson = new JsonResponse($dados, $status, $header);
        $this->responseJson->headers->set('Content-Type', 'application/json');
        return $this;
    }

    /**
     * Retorna um location para uma URL informada
     *
     * @param  string  $url     A URL que dese ser redirecionada
     * @param  int     $status  Status HTTP que deve ser usando podendo ser: 301 = URL mudou, você não deve mais usar
     *                          essa URL e sim a nova; 302 = Movida temporariamente, continue usando a mesma URL; 307 =
     *                          Igual a 302 mas mantem o método usado; 308 = Igual a 301 mas mantem o método usado.
     * @param  array   $header  Header para ser usando no location
     *
     * @return self
     * @throws Excecao Exceção caso o status não seja um dos listados
     */
    public function location(string $url, int $status = 302, array $header = []): self
    {
        $this->tipo = 'responseLocation';
        if (!in_array($status, [301, 302, 307, 308])) {
            mensagemStatus(400, localhost: 'O status para redirecionamento deve ser 301, 302, 307 ou 308.');
        }
        $this->responseLocation = new RedirectResponse($url, $status, $header);
        return $this;
    }

    // doc

    /**
     * Faz o download de um arquivo
     *
     * @param  string  $arquivo  Path do arquivo que deseja fazer download
     * @param  int     $status   Status para o header
     * @param  array   $header   Header do cabeçalho
     *
     * @return self
     * @throws Excecao
     */
    public function download(string $arquivo, int $status = 200, array $header = []): self
    {
        if (!file_exists($arquivo)) {
            mensagemStatus(
                404,
                localhost: 'Arquivo não encontrado ou inválido. Verifique se o caminho.'
            );
        }

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
     * @param  string  $arquivo  Path do arquivo
     * @param  int     $status   Status para o header
     * @param  array   $header   Header do cabeçalho
     *
     * @return self
     * @throws Excecao
     */
    public function arquivo(string $arquivo, int $status = 200, array $header = []): self
    {
        if (!file_exists($arquivo)) {
            mensagemStatus(
                404,
                localhost: 'Arquivo não encontrado ou inválido. Verifique se o caminho.'
            );
        }

        $this->tipo = 'responseFile';
        $nome = pathinfo($arquivo, PATHINFO_BASENAME);
        $mimeType = mime_content_type($arquivo);

        $this->responseFile = new BinaryFileResponse($arquivo, $status, $header);
        $this->responseFile->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $nome);
        $this->responseFile->headers->set('Content-Type', $mimeType);
        return $this;
    }

    // doc

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render()->getContent();
    }

    // doc

    /**
     * @return BinaryFileResponse|JsonResponse|Psr7Response|RedirectResponse
     */
    public function render(): BinaryFileResponse|RedirectResponse|JsonResponse|Psr7Response
    {
        return match ($this->tipo) {
            'responseJson' => $this->responseJson->send(),
            'responseLocation' => $this->responseLocation->send(),
            'responseFile' => $this->responseFile->send(),
            default => $this->responseBody->send()
        };
    }

    // doc

    /**
     * Retorna a classe origin
     * Symfony\Component\HttpFoundation\Response,JsonResponse,RedirectResponse,BinaryFileResponse dependendo do tipo
     * de resposta solicitada
     *
     * @return BinaryFileResponse|JsonResponse|Psr7Response|RedirectResponse Classe original do Symfony dependendo do
     *                                                                       tipo de resposta solicitada
     */
    public function response(): BinaryFileResponse|RedirectResponse|JsonResponse|Psr7Response
    {
        $tipo = $this->tipo;
        return $this->$tipo;
    }

    // doc

    /**
     * Seta os Cookies para a resposta
     *
     * @param  array  $cookies  Lista de cookie no padrão ['nome_cookie' => ['value' => '', 'secure' => '', 'domain' =>
     *                          '', 'expire' => '', 'httpOnly' => '', 'raw' => '', 'path' => '', 'sameSite' => '']]
     *
     * @return self
     */
    public function cookie(array $cookies): self
    {
        $tipo = $this->tipo;
        foreach ($cookies as $ind => $val) {
            $value = $val;
            $secure = true;
            $domain = $_SERVER['HTTP_HOST'];
            $expire = strtotime('+30 minutes');
            $httpOnly = true;
            $raw = false;
            $path = '/';
            $sameSite = Cookie::SAMESITE_LAX;
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
                new Cookie($ind, $value, $expire, $path, $domain, $secure, $httpOnly, $raw, $sameSite)
            );
        }
        return $this;
    }

    // doc

    /**
     * Seta o valor do Status HTTP da página
     *
     * @param  int  $status  Status podendo ser de 100 a 399
     *
     * @return self
     * @throws Excecao Execão caso passa um status fora do range permitido
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
     * @param  array  $header  Header do cabeçalho
     *
     * @return self
     */
    public function header(array $header): self
    {
        $tipo = $this->tipo;
        foreach ($header as $type => $value) {
            $this->$tipo->headers->set($type, $value);
        }
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
}
