<?php

namespace Helpers;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CurlHelper
{
    private $ssl = true;
    private $parametro;
    private $body = [];
    private $header = [];
    private $option;
    private $json = [];
    protected $requisicao;
    private $retornoValor;
    private $retornoStatus;
    private $retornoErro;
    private $retornoHeader;
    private $retornoInfo;
    private $urlUsada = '';
    private $metodoUsado = '';
    private bool $erroValidar = false;
    private string $erroTitulo = 'Erro!';
    private string $erroMensagem = '';
    private int $erroStatus = 400;

    public function __construct(
        private $url = null
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
    public function url()
    {
        return $this->urlUsada;
    }
    /**
     * Pega o último Método usado
     *
     * @return string
     */
    public function metodo()
    {
        return $this->metodoUsado;
    }

    /**
     * Valida se existe erro apos executar o CURL
     *
     * @param   string          $mensagem   Mensagem de erro padrão caso a resposta não tenha
     * @param   null|string     $titulo     Título de erro padrão caso a resposta não tenha
     * @param   null|int        $status     Status HTML em caso de erro
     * @return  Self
     */
    public function validar(string $mensagem, ?string $titulo = null, ?int $status = null): self
    {
        $this->erroValidar = true;
        $this->erroMensagem = $mensagem;
        if (!empty($titulo)) {
            $this->erroTitulo = $titulo;
        }
        if (!empty($status)) {
            $this->erroStatus = $status;
        }
        return $this;
    }

    /**
     * Seta os parametros da URL
     * @param array $parametro Parametro que deve ser enviado
     * @return Self
     */
    public function parametro(null|string|array $parametro = null): self|string|array
    {
        if (is_null($parametro)) {
            return !empty($this->parametro) ? $this->parametro : $this->requisicao['parametro'];
        } else if (is_string($parametro)) {
            return $this->parametro[$parametro] ?? '';
        } else if (!empty($this->parametro)) {
            $this->parametro = $this->parametro + $parametro;
        } else {
            $this->parametro = $parametro;
        }

        return $this;
    }
    /**
     * Seta ou pega o body da requisição
     *
     * @param null|string|array $body Body que deve ser enviado ou string para pegar um indice ou null para pegar todos os indices
     * @return Self|array|string
     */
    public function body(null|string|array $body = null, bool $merge = true): self|array|string
    {
        if (is_null($body)) {
            return !empty($this->body) ? $this->body : $this->requisicao['body'];
        } else if (is_string($body)) {
            return $this->body[$body] ?? '';
        } else if (!empty($this->body) && $merge) {
            $this->body = $this->body + $body;
        } else {
            $this->body = $body;
        }

        return $this;
    }

    /**
     * Seta o header para a requisição
     *
     * @param array $header Dados que devem ser enviado no header
     * @return Self|array|string
     */
    public function header(null|array|string $header = null): string|array|Self
    {
        if (is_null($header)) {
            return !empty($this->header) ? $this->header : $this->requisicao['header'];
        } else if (is_string($header)) {
            return $this->header[$header];
        } else if (!empty($header) && is_array($header)) {
            $this->header = array_merge($this->header, $header);
            return $this;
        }
        return $this;
    }

    /**
     * Envia ou pega o json do body
     *
     * @param array $json Json para ser enviado no body
     * @return Self|array|string
     */
    public function json(null|string|array $json = null): self|string|array
    {
        if (is_null($json)) {
            return !empty($this->json) ? $this->json : $this->requisicao['json'];
        } else if (is_string($json)) {
            return $this->json[$json] ?? '';
        }

        $this->json = $json;
        return $this;
    }

    /**
     * Seta os option para o CURL
     *
     * @param array $option Option do CURL
     * @return Self
     */
    public function option(array $option): self
    {
        $this->option = $option;
        return $this;
    }

    /**
     * Remover a validação do SSL
     *
     * @return Self
     */
    public function removerSsl(): self
    {
        $this->ssl = false;
        return $this;
    }

    /**
     * Envia um arquivo no body
     *
     * @param array $dado Lista com arquivo para ser feito o upload podendo ser um upload ou arquivo no servidor
     * @return Self
     */
    public function arquivo(array $arquivo): self
    {
        $lista = [];
        foreach ($arquivo as $ind => $val) {
            if ($val instanceof UploadedFile) {
                $lista[$ind] = curl_file_create($val->getPathname(), $val->getMimeType(), $val->getClientOriginalName());
            } elseif (is_array($val) && isset($val['tmp_name']) && file_exists($val['tmp_name'])) {
                $lista[$ind] = curl_file_create($val['tmp_name'], $val['type'] ?? mime_content_type($val['tmp_name']), $val['name'] ?? '');
            } elseif (is_string($val) && file_exists($val)) {
                $lista[$ind] = curl_file_create($val, mime_content_type($val), basename($val));
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

    /*
    |--------------------------------------------------------------------------
    | METODOS DA API
    |--------------------------------------------------------------------------
    */

    /**
     * Envia uma requisição POST
     *
     * @param string $url Url que deve ser enviado a requisição
     * @return Self
     */
    public function post(string $url): self
    {
        $this->curl('POST', $url);
        return $this;
    }

    /**
     * Envia uma requisição GET
     *
     * @param string $url Url que deve ser enviado a requisição
     * @return Self
     */
    public function get(string $url): self
    {
        $this->curl('GET', $url);
        return $this;
    }

    /**
     * Envia uma requisição PUT
     *
     * @param string $url Url que deve ser enviado a requisição
     * @return Self
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
     * @param string $url Url que deve ser enviado a requisição
     * @return Self
     */
    public function delete(string $url): self
    {
        $this->curl('DELETE', $url);
        return $this;
    }

    /**
     * Envia uma requisição PATCH
     *
     * @param string $url Url que deve ser enviado a requisição
     * @return Self
     */
    public function patch(string $url): self
    {
        $this->curl('PATCH', $url);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | RETORNOS
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna um array como resposta
     */
    public function array()
    {
        return $this->retorno(true);
    }

    /**
     * Retorna um stdClass object como resposta
     */
    public function object()
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

    private function retorno(Bool $tipo)
    {
        $retorno = $this->retornoValor;
        $dado = jsonDecode($retorno, $tipo);

        if ($tipo && !is_array($dado)) {
            return ['erro' => true, 'titulo' => 'Retorno incorreto!', 'texto' => $retorno];
        } elseif (!$tipo && !is_object($dado)) {
            return (object) ['erro' => true, 'titulo' => 'Retorno incorreto!', 'texto' => $retorno];
        }

        return $dado;
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
     * Retonar o debug
     *
     * @return array
     */
    public function debug(): array
    {
        return [
            'requisicao' => $this->requisicao,
            'retorno' => [
                'valor' => $this->retornoValor,
                'erro' => $this->retornoErro,
                'status' => $this->retornoStatus,
                'header' => $this->retornoHeader,
                'info' => $this->retornoInfo,
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CURL
    |--------------------------------------------------------------------------
    */
    protected function curl(String $metodo, String $url)
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
            $parametroExplode[] = !is_array($val) && !is_object($val) && !empty($val) ? $ind . '=' . urlencode($val) : $ind . '=';
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
                } else if ($ind == 'CURLOPT_ENCODING') {
                    curl_setopt($ch, CURLOPT_ENCODING, $val);
                } else if ($ind == 'CURLOPT_HTTP_VERSION') {
                    curl_setopt($ch, CURLOPT_HTTP_VERSION, $val);
                } else if ($ind == 'CURLOPT_MAXREDIRS') {
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

        if ($this->erroValidar) {
            respostaJson($this->object(), $this->erroMensagem, $this->erroTitulo, $this->erroStatus);
        }

        $this->requisicao = [
            'url' => $url,
            'body' => $body,
            'parametro' => $parametro,
            'json' => $json,
            'header' => $header,
            'option' => $option,
        ];

        $this->parametro = [];
        $this->body = [];
        $this->json = [];

        return $this;
    }
}
