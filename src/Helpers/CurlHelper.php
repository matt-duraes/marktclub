<?php

namespace Helpers;

use Erro\Excecao;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CurlHelper
{
    protected $requisicao;
    protected bool $apiHelper = false;
    private bool $ssl = true;
    private $parametro;
    private array $body = [];
    private array $header = [];
    private $option;
    private array $json = [];
    private $retornoValor;
    private $retornoStatus;
    private $retornoErro;
    private $retornoHeader;
    private $retornoInfo;
    private string $urlUsada = '';
    private string $metodoUsado = '';
    private bool $erroValidar = false;
    private string $erroTitulo = 'Erro!';
    private string $erroMensagem = '';
    private int $erroStatus = 400;
    private bool $erroRetorno = true;
    private bool $erroLogin = false;

    public function __construct(
        private readonly ?string $url = null
    ) {
        $this->resetar();
    }

    public function resetar(): self
    {
        $this->retornoErro = [];
        $this->retornoStatus = 0;
        $this->retornoValor = '';
        $this->retornoHeader = [];
        $this->retornoInfo = [];

        $this->parametro = [];
        $this->body = [];
        $this->header = [];
        $this->option = [];
        $this->json = [];

        $this->urlUsada = '';
        $this->metodoUsado = '';

        $this->requisicao = [];

        return $this;
    }

    /**
     * Pega a última URL usada
     *
     * @return string
     */
    public function url(): string
    {
        return $this->urlUsada;
    }

    /**
     * Pega o último Método usado
     *
     * @return string
     */
    public function metodo(): string
    {
        return $this->metodoUsado;
    }

    /**
     * Valida se existe erro apos executar o CURL
     *
     * @param  string|null $mensagem Mensagem de erro padrão caso a resposta não tenha
     * @param  string|null $titulo   Título de erro padrão caso a resposta não tenha
     * @param  int|null    $status   Status HTML em caso de erro
     * @param  bool        $retorno  Se pode mostar o retorno de erro ou obriga a usar o da mensagem
     * @param  bool        $login    Se erro 401 ou 403 redireciona o usuário para o login
     * @param  bool        $ajax     Tratar o GET como ajax em caso de erro do login
     * @return CurlHelper
     */
    public function validar(
        string $mensagem = null,
        string $titulo = null,
        int $status = null,
        bool $retorno = true,
        bool $login = false
    ): self {
        $this->erroLogin = $login;
        $this->erroValidar = true;
        $this->erroRetorno = $retorno;
        if (!empty($mensagem)) {
            $this->erroMensagem = $mensagem;
        }
        if (!empty($titulo)) {
            $this->erroTitulo = $titulo;
        }
        if (!empty($status)) {
            $this->erroStatus = $status;
        }
        return $this;
    }

    /**
     * Seta os parâmetros da URL
     *
     * @param  string|array|null       $parametro Parâmetro que deve ser enviado
     * @return CurlHelper|string|array
     */
    public function parametro(string|array $parametro = null): self|string|array
    {
        if (is_null($parametro)) {
            return !empty($this->parametro) ? $this->parametro : $this->requisicao['parametro'];
        } elseif (is_string($parametro)) {
            return $this->parametro[$parametro] ?? '';
        } elseif (!empty($this->parametro)) {
            $this->parametro = $this->parametro + $parametro;
        } else {
            $this->parametro = $parametro;
        }

        return $this;
    }

    /**
     * Seta ou pega o body da requisição.
     *
     * @param  null|string|array       $body  Body que deve ser enviado ou string para pegar um índice ou null para pegar
     *                                        todos os índices
     * @param  bool                    $merge
     * @return CurlHelper|array|string
     */
    public function body(null|string|array $body = null, bool $merge = true): self|array|string
    {
        if (is_null($body)) {
            return !empty($this->body) ? $this->body : $this->requisicao['body'];
        } elseif (is_string($body)) {
            return $this->body[$body] ?? '';
        } elseif (!empty($this->body) && $merge) {
            $this->body = $this->body + $body;
        } else {
            $this->body = $body;
        }

        return $this;
    }

    /**
     * Envia ou pega o json do body
     *
     * @param  string|array|null       $json Json para ser enviado no body
     * @return CurlHelper|string|array
     */
    public function json(string|array $json = null): self|string|array
    {
        if (is_null($json)) {
            return !empty($this->json) ? $this->json : $this->requisicao['json'];
        } elseif (is_string($json)) {
            return $this->json[$json] ?? '';
        }

        $this->json = $json;
        return $this;
    }

    /**
     * Seta os option para o CURL
     *
     * @param  array      $option Option do CURL
     * @return CurlHelper
     */
    public function option(array $option): self
    {
        $this->option = $option;
        return $this;
    }

    /**
     * Remover a validação do SSL
     *
     * @return CurlHelper
     */
    public function removerSsl(): self
    {
        $this->ssl = false;
        return $this;
    }

    /**
     * Envia um ou mais arquivos no body
     *
     * @param  array      $arquivos Lista com arquivo para ser feito o upload podendo ser um upload ou arquivo no servidor
     * @return CurlHelper
     */
    public function arquivo(array $arquivos): self
    {
        $lista = [];
        foreach ($arquivos as $index => $arquivo) {
            if ($arquivo instanceof UploadedFile) {
                $lista[$index] = curl_file_create(
                    $arquivo->getPathname(),
                    $arquivo->getMimeType(),
                    $arquivo->getClientOriginalName()
                );
            } elseif (is_array($arquivo) && isset($arquivo['tmp_name']) && file_exists($arquivo['tmp_name'])) {
                $lista[$index] = curl_file_create(
                    $arquivo['tmp_name'],
                    $arquivo['type'] ?? mime_content_type($arquivo['tmp_name']),
                    $arquivo['name'] ?? ''
                );
            } elseif (is_string($arquivo) && file_exists($arquivo)) {
                $lista[$index] = curl_file_create($arquivo, mime_content_type($arquivo), basename($arquivo));
            }
        }

        if (!empty($this->body)) {
            $this->body = array_merge($this->body, $lista);
        } else {
            $this->body = $lista;
        }

        if (!empty($this->header)) {
            $this->header['Content-Type'] = 'multipart/form-data';
        } else {
            $this->header = ['Content-Type' => 'multipart/form-data'];
        }

        return $this;
    }

    /**
     * Envia uma requisição POST
     *
     * @param  string     $url Url que deve ser enviado a requisição
     * @return CurlHelper
     * @throws Excecao
     */
    public function post(string $url): self
    {
        $this->curl('POST', $url);
        return $this;
    }

    /**
     * @throws Excecao
     */
    protected function curl(string $metodo, string $url): self
    {
        $this->urlUsada = $this->url . $url;
        $this->metodoUsado = $metodo;

        $json = [];
        $parametro = $this->parametro;
        $body = $this->body;
        $header = $this->header;
        $option = $this->option;

        if (!empty($this->json)) {
            $json = $this->json;
            $body = [];
        }

        $parametroExplode = [];
        foreach ($parametro as $ind => $val) {
            $parametroExplode[] = !is_array($val) && !is_object($val) && !empty($val) ? $ind . '=' . urlencode(
                $val
            ) : $ind . '=';
        }
        $parametroUrl = implode('&', $parametroExplode);
        if (!empty($parametroUrl)) {
            $parametroUrl = str_contains($this->urlUsada, '?') ? '&' . $parametroUrl : '?' . $parametroUrl;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->urlUsada . $parametroUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if (!$this->ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }

        if ($option) {
            foreach ($option as $ind => $val) {
                if ($ind == 'CURLOPT_TIMEOUT') {
                    curl_setopt($ch, CURLOPT_TIMEOUT, $val);
                } elseif ($ind == 'CURLOPT_ENCODING') {
                    curl_setopt($ch, CURLOPT_ENCODING, $val);
                } elseif ($ind == 'CURLOPT_HTTP_VERSION') {
                    curl_setopt($ch, CURLOPT_HTTP_VERSION, $val);
                } elseif ($ind == 'CURLOPT_MAXREDIRS') {
                    curl_setopt($ch, CURLOPT_MAXREDIRS, $val);
                }
            }
        }

        if ($header) {
            $array_header = [];
            foreach ($header as $ind => $val) {
                $array_header[] = $ind . ':' . $val;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $array_header);
        }

        if (!empty($body)) {
            foreach ($body as $ind => $val) {
                if (is_array($val)) {
                    $body[$ind] = json_encode($val);
                }
            }

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $metodo == 'PUT' ? http_build_query($body) : $body);
        } elseif (!empty($json)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        }

        $retornoHeader = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $valor) use (&$retornoHeader) {
            $matches = [];

            if (preg_match('/^([^:]+)\s*:\s*([^\x0D\x0A]*)\x0D?\x0A?$/', $valor, $matches)) {
                $ind = mb_strtolower($matches[1], 'UTF-8');
                $retornoHeader[$ind] = $matches[2];
            }

            return strlen($valor);
        });

        $retornoValor = curl_exec($ch);
        $retornoStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $retornoErro = curl_error($ch);
        $retornoInfo = curl_getinfo($ch);

        $this->retornoErro = $retornoErro;
        $this->retornoValor = $retornoValor;
        $this->retornoStatus = $retornoStatus;
        $this->retornoHeader = $retornoHeader;
        $this->retornoInfo = $retornoInfo;
        curl_close($ch);

        $tokenInvalido = $this->erroLogin && in_array($this->status(), [401, 403]) && !empty(route('sair.index'));
        if ($tokenInvalido) {
            mensagemErro(
                'Usuário deslogado!',
                'O usuário foi deslogado, por favor, faça o login novamente.',
                status: 401,
                codigo: 4001
            );
        } elseif ($this->erroValidar) {
            respostaJson($this, $this->erroMensagem, $this->erroTitulo, $this->erroStatus, $this->erroRetorno);
        }

        $this->requisicao = [
            'url'       => $url,
            'body'      => $body,
            'parametro' => $parametro,
            'json'      => $json,
            'header'    => $header,
            'option'    => $option,
        ];

        $this->parametro = [];
        $this->body = [];
        $this->json = [];
        $this->limparHeader();

        return $this;
    }

    private function limparHeader()
    {
        $token = $this->header['Authorization'] ?? $this->header['authorization'] ?? '';
        $this->header = [];
        if (!empty($token)) {
            $this->header = ['Authorization' => $token];
        }
    }

    /**
     * Envia uma requisição GET
     *
     * @param  string     $url Url que deve ser enviado a requisição
     * @return CurlHelper
     * @throws Excecao
     */
    public function get(string $url): self
    {
        $this->headerJson();
        $this->curl('GET', $url);
        return $this;
    }

    public function headerJson(): self
    {
        if (array_key_exists('Content-Type', $this->header)) {
            return $this;
        }
        $this->header(['Content-Type' => 'application/json']);
        return $this;
    }

    /**
     * Seta o header para a requisição
     *
     * @param  array|string|null       $header Dados que devem ser enviado no header
     * @return CurlHelper|string|array
     */
    public function header(null|array|string $header = null): self|string|array
    {
        if (is_null($header)) {
            return !empty($this->header) ? $this->header : $this->requisicao['header'];
        } elseif (is_string($header)) {
            return $this->header[$header];
        } elseif (!empty($header) && is_array($header)) {
            $this->header = array_merge($this->header, $header);
            return $this;
        }
        return $this;
    }

    /**
     * Envia uma requisição PUT
     *
     * @param  string     $url Url que deve ser enviado a requisição
     * @return CurlHelper
     * @throws Excecao
     */
    public function put(string $url): self
    {
        if (!empty($this->header)) {
            $this->header['Content-Type'] = 'application/x-www-form-urlencoded';
        } else {
            $this->header = ['Content-Type' => 'application/x-www-form-urlencoded'];
        }
        $this->curl('PUT', $url);
        return $this;
    }

    /**
     * Envia uma requisição DELETE
     *
     * @param  string     $url Url que deve ser enviado a requisição
     * @return CurlHelper
     * @throws Excecao
     */
    public function delete(string $url): self
    {
        $this->curl('DELETE', $url);
        return $this;
    }

    /**
     * Envia uma requisição PATCH
     *
     * @param  string     $url Url que deve ser enviado a requisição
     * @return CurlHelper
     * @throws Excecao
     */
    public function patch(string $url): self
    {
        $this->curl('PATCH', $url);
        return $this;
    }

    /**
     * Retorna um array como resposta
     */
    public function array()
    {
        return $this->retorno(true);
    }

    private function retorno(bool $tipo): object|bool|array
    {
        $retorno = $this->retornoValor;
        $eJson = (json_decode($retorno) !== null);
        $dado = $eJson ? jsonDecode($retorno, $tipo) : $retorno;

        if ($tipo && (!$eJson || !is_array($dado))) {
            return ['erro' => true, 'titulo' => 'Retorno incorreto!', 'texto' => $retorno];
        } elseif (!$tipo && (!$eJson || !is_object($dado))) {
            return (object)['erro' => true, 'titulo' => 'Retorno incorreto!', 'texto' => $retorno];
        }

        return $dado;
    }

    /**
     * Retorna um stdClass object como resposta
     */
    public function object(): object
    {
        return $this->retorno(false);
    }

    /**
     * Retorna uma string como resposta
     */
    public function string()
    {
        return $this->retornoValor;
    }

    /**
     * Pega o status de resposta da requisição
     */
    public function status(): int
    {
        return $this->retornoStatus;
    }

    /**
     * Pega o retorno do erro
     */
    public function erro()
    {
        return $this->retornoErro;
    }

    /**
     * Retorna o debug
     *
     * @return array
     */
    public function debug(): array
    {
        return [
            'requisicao' => $this->requisicao,
            'retorno'    => [
                'valor'  => $this->retornoValor,
                'erro'   => $this->retornoErro,
                'status' => $this->retornoStatus,
                'header' => $this->retornoHeader,
                'info'   => $this->retornoInfo
            ]
        ];
    }
}
