<?php

namespace Route;

use Closure;
use Erro\Erro;
use Erro\Excecao;

final class Route
{
    private static array $Route = [
        'rota' => [
            'GET' => [],
            'POST' => [],
            'PUT' => [],
            'DELETE' => [],
        ]
    ];

    private static array $middleware = [
        'pre' => [],
        'pos' => []
    ];
    private static array $middlewareGrupo = [
        'pre' => [],
        'pos' => []
    ];

    private static array $criptografia = [
        'lista' => [],
        'chave' => null
    ];
    private static array $criptografiaGrupo = [
        'lista' => [],
        'chave' => null
    ];

    private static string $nome;
    private static string $nomeGrupo;
    private static string $controller;
    private static string $action;
    private static array $request = [];
    private static array $link = [];
    private static bool $eGrupo = true;
    private static bool $semGrupo = false;
    private static bool $rotaUnica = true;

    /**
     * Cria um grupo para as rotas
     *
     * @param Closure   $callback   Função de retorno com o grupo
     * @param bool      $encandear  Se será encandeado ou não o método
     */
    public static function grupo(Closure $callback, bool $encandear = false)
    {
        self::$nomeGrupo = self::$nome;
        if (self::middlewareVazia(self::$middlewareGrupo) && !self::middlewareVazia(self::$middleware)) {
            self::$middlewareGrupo = self::$middleware;
        }

        if (empty(self::$criptografiaGrupo['lista']) && !empty(self::$criptografia['lista'])) {
            self::$criptografiaGrupo = self::$criptografia;
        }

        self::$nome = '';
        self::$middleware = [
            'pre' => [],
            'pos' => []
        ];

        self::$criptografia = [
            'lista' => [],
            'chave' => null
        ];

        self::$eGrupo = false;
        call_user_func($callback);
        self::$eGrupo = true;

        self::$nomeGrupo = '';
        self::$controller = '';
        if ($encandear) {
            return __CLASS__;
        }
        self::$middlewareGrupo = [
            'pre' => [],
            'pos' => []
        ];
        self::$criptografiaGrupo = [
            'lista' => [],
            'chave' => null
        ];
    }

    private static function middlewareVazia($middleware)
    {
        return empty($middleware['pre']) && empty($middleware['pos']);
    }

    /**
     * Adicionar no começo de uma Rota caso não queira usar um grupo
     */
    public static function semGrupo()
    {
        self::$eGrupo = false;
        self::$semGrupo = true;
        return __CLASS__;
    }

    /**
     * NÃO USAR ESSE MÉTODO
     */
    public static function _rotaNaoUnica()
    {
        self::$rotaUnica = false;
        return __CLASS__;
    }

    /**
     * Da um nome para o construtor ou para as rotas
     *
     * @param string $nome Nome desejado
     * @return Self
     */
    public static function nome(string $nome)
    {
        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z\_]*[a-zA-Z]{1}$/', $nome)) {
            throw new Erro(
                arquivo: 'trace:1',
                mensagem: '
                    O nome ' . $nome . ' deve ter apenas letras e underline e deve começar e terminar com 1 letra.
                '
            );
        }
        if (!self::$eGrupo && empty(self::$action)) {
            self::$action = $nome;
        }
        self::$nome = $nome;
        return __CLASS__;
    }

    /**
     * Lista de requestes desejados
     *
     * @param string|array  $request    Lista de request podendo ser "*" para qualquer parâmetro ou uma lista em um array
     * @param null|string   $tipo       Tipo de request podendo ser get, post, put, json ou files, caso null, pega padrão da rota
     */
    public static function request(string|array $request, ?string $tipo = null)
    {
        if (self::$eGrupo) {
            self::erroNaoPodeChamarNoGrupo('request');
        } elseif (!empty($tipo) && !in_array($tipo, ['get', 'post', 'put', 'json', 'files'])) {
            throw new Erro(
                arquivo: 'trace:1',
                mensagem: 'O tipo ' . $tipo . ' para o request não é um valor aceito.'
            );
        } elseif (is_string($request) && $request != '*') {
            throw new Erro(
                arquivo: 'trace:1',
                mensagem: 'A request deve ser uma string contendo "*" para qualquer parâmetro ou uma lista em um array.'
            );
        }
        self::$request[$tipo == null ? 'padrao' : $tipo] = $request;
        return __CLASS__;
    }

    /**
     * Middleware para ser executada antes ou depois da rota
     *
     * @param string        $classe         Classe do middleware que deseja chamar
     * @param string        $action         Action do middleware
     * @param null|array    $parametro      Array com os dados do parametros
     * @param null|array    $construtor     Array com os dados do contrutor
     * @param bool          $pos            Passar true para chamar o middleware apos a rota
     * @return Self
     */
    public static function middleware(
        string $classe,
        string $action,
        ?array $parametro = null,
        ?array $construtor = null,
        bool $pos = false
    ) {
        $tipo = $pos ? 'pos' : 'pre';
        self::$middleware[$tipo][] = [
            'classe' => $classe,
            'action' => $action,
            'construtor' => !empty($construtor) ? $construtor : [],
            'parametro' => !empty($parametro) ? $parametro : []
        ];
        return __CLASS__;
    }

    /**
     * Middleware para ser executada antes ou depois da rota
     *
     * @param   array           $lista      Classe com a constante que tem a lista de dados que são criptografados
     * @param   null|string     $chave      Chave para descriptografar
     * @return  Self
     */
    public static function criptografia(array $lista, ?string $chave = null)
    {
        self::$criptografia = [
            'lista' => $lista,
            'chave' => $chave
        ];
        return __CLASS__;
    }

    /**
     * Controller que deseja usar
     *
     * @param string $nome    Nome do controller que deseja usar
     * @return Self
     */
    public static function controller(string $nome)
    {
        self::$controller = $nome;
        return __CLASS__;
    }
    /**
     * Action que deseja usar
     *
     * @param string $nome    Nome da action que deseja usar
     * @return Self
     */
    public static function action(string $nome)
    {
        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z\_]*[a-zA-Z]{1}$/', $nome)) {
            throw new Erro(
                arquivo: 'trace:1',
                mensagem: '
                    O action deve ter apenas letras e underline e deve começar e terminar com 1 letra.
                '
            );
        }
        self::$action = $nome;
        return __CLASS__;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PARA CRIAR ROTA
    |--------------------------------------------------------------------------
    */

    /**
     * Cria uma rota VIEW
     *
     * @param string $uri   URI desejada, passar array para multiplas rotas
     */
    public static function view(string|array $uri): void
    {
        $uri = is_string($uri) ? [$uri] : $uri;
        foreach ($uri as $valor) {
            self::setarRota('VIEW', $valor);
        }
        self::limparRota();
    }
    /**
     * Cria uma rota GET
     *
     * @param string $uri   URI desejada, passar array para multiplas rotas
     */
    public static function get(string|array $uri): void
    {
        $uri = is_string($uri) ? [$uri] : $uri;
        foreach ($uri as $valor) {
            self::setarRota('GET', $valor);
        }
        self::limparRota();
    }
    /**
     * Cria uma rota POST
     *
     * @param string $uri   URI desejada, passar array para multiplas rotas
     */
    public static function post(string|array $uri): void
    {
        $uri = is_string($uri) ? [$uri] : $uri;
        foreach ($uri as $valor) {
            self::setarRota('POST', $valor);
        }
        self::limparRota();
    }
    /**
     * Cria uma rota PUT
     *
     * @param string $uri   URI desejada, passar array para multiplas rotas
     */
    public static function put(string|array $uri): void
    {
        $uri = is_string($uri) ? [$uri] : $uri;
        foreach ($uri as $valor) {
            self::setarRota('PUT', $valor);
        }
        self::limparRota();
    }
    /**
     * Cria uma rota DELETE
     *
     * @param string $uri   URI desejada, passar array para multiplas rotas
     */
    public static function delete(string|array $uri): void
    {
        $uri = is_string($uri) ? [$uri] : $uri;
        foreach ($uri as $valor) {
            self::setarRota('DELETE', $valor);
        }
        self::limparRota();
    }

    public static function pegarRota(string $controller, string $action)
    {
        $metodo = mb_strtoupper($_SERVER['REQUEST_METHOD'], 'UTF-8');
        if (!in_array($metodo, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'Método enviado inválido.', status: 404);
        }

        $rota = self::$Route['rota'][$metodo] ?? '';
        if (empty($rota)) {
            throw new Excecao(titulo: 'Erro!', mensagem: 'Não existe rotas para esse método.', status: 404);
        }

        $controller = $controller == 'index' ? '' : $controller;
        $action = $action == 'index' ? '' : $action;

        if (empty($controller) && empty($action)) {
            $rotaFinal = $rota['/'] ?? $rota['/*'] ?? [];
        } elseif (empty($action)) {
            $rotaFinal = $rota['/' . $controller] ?? $rota['/*'] ?? [];
        } else {
            $rotaFinal = $rota['/' . $controller . '/' . $action] ??
                $rota['/' . $controller . '/*'] ??
                $rota['/*/' . $action] ??
                $rota['/*/*'] ??
                [];
        }

        $eView = false;
        if (is_array($rotaFinal) && array_key_exists('metodo', $rotaFinal) && $rotaFinal['metodo'] == 'VIEW') {
            $eView = true;
        }
        define('ROTA_VIEW', $eView);

        return $rotaFinal;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private static function limparRota()
    {
        self::$request = [];
        self::$middleware = [
            'pre' => [],
            'pos' => []
        ];
        self::$criptografia = [
            'lista' => [],
            'chave' => null
        ];
        self::$action = '';
        self::$nome = '';
        self::$rotaUnica = true;
        if (self::$semGrupo) {
            self::$eGrupo = true;
            self::$semGrupo = false;
        }
    }
    private static function setarRota(string $metodo, string $uri)
    {
        $metodoReal = $metodo;
        $metodo = $metodo == 'VIEW' ? 'GET' : $metodo;
        $url = self::criarUrl($uri);
        self::adicionarLink($metodoReal, $url);

        if (array_key_exists($url, self::$Route['rota'][$metodo]) && !self::$rotaUnica) {
            return;
        } else if (array_key_exists($url, self::$Route['rota'][$metodo])) {
            throw new Erro(
                arquivo: 'trace:1',
                mensagem: 'A uri ' . $url . ' no método ' . $metodo . ' já foi declarada.'
            );
        } elseif (empty(self::$controller) || empty(self::$action)) {
            throw new Erro(
                arquivo: 'trace:1',
                mensagem: 'Você deve passar um controller para a url ' . $url . ' no método ' . $metodo . '.'
            );
        }

        $middleware = [
            'pre' => array_merge(self::$middlewareGrupo['pre'], self::$middleware['pre']),
            'pos' => array_merge(self::$middlewareGrupo['pos'], self::$middleware['pos']),
        ];

        $request = self::pegarRequest($metodoReal);
        self::$Route['rota'][$metodo][$url] = [
            'uri' => $uri,
            'metodo' => $metodoReal,
            'middleware' => $middleware,
            'criptografia' => !empty(self::$criptografia['lista']) ? self::$criptografia : self::$criptografiaGrupo,
            'request' => $request,
            'controller' => self::$controller,
            'action' => self::$action,
        ];
    }

    private static function pegarRequest($metodo): array
    {
        $request = self::$request;
        $metodo = $metodo == 'VIEW' ? 'GET' : $metodo;
        $lista = [];

        if (array_key_exists('padrao', $request) && $metodo != 'DELETE') {
            $lista[mb_strtolower($metodo, 'UTF-8')] = $request['padrao'];
        } elseif (array_key_exists('padrao', $request) && $metodo == 'DELETE') {
            $lista['get'] = $request['padrao'];
        }
        if (array_key_exists('files', $request) && $metodo == 'POST') {
            $lista['files'] = $request['files'];
        }
        if (array_key_exists('get', $request) && in_array($metodo, ['GET', 'POST', 'DELETE'])) {
            $lista['get'] = $request['get'];
        }
        if (array_key_exists('post', $request) && $metodo == 'POST') {
            $lista['post'] = $request['post'];
        } elseif (array_key_exists('put', $request) && $metodo == 'PUT') {
            $lista['put'] = $request['put'];
        }

        if (array_key_exists('json', $request)) {
            $lista['json'] = $request['json'];
        }

        return $lista;
    }

    private static function adicionarLink($metodo, $uri)
    {
        $nome = self::$nome;
        if (empty($nome)) {
            return;
        }

        $nome = !empty(self::$nomeGrupo) ? self::$nomeGrupo . '.' . $nome : $nome;
        self::$link[mb_strtolower($metodo, 'UTF-8') . '.' . $nome] = str_replace('/*', '', $uri);
    }

    private static function criarUrl(string $uri): string
    {
        $explode = explode('/', trim($uri));
        if (empty($explode) || empty($explode[1])) {
            return '/';
        }

        $url = '';
        $controller = $explode[1];
        $action = $explode[2] ?? '';

        if (!empty($controller)) {
            $url .= preg_match("/^\{[a-zA-Z0-9_]+\}$/", $controller) ? '/*' : '/' . $controller;
        }
        if (!empty($action)) {
            $url .= preg_match("/^\{[a-zA-Z0-9_]+\}$/", $action) ? '/*' : '/' . $action;
        }

        return $url;
    }

    private static function erroNaoPodeChamarNoGrupo($metodo)
    {
        throw new Erro(
            arquivo: 'trace:1',
            mensagem: 'Você não pode passar o método ' . $metodo . ' em um grupo.'
        );
    }

    public static function route($rota)
    {
        $explode = explode('.', $rota);

        $quantidade = count($explode);
        if ($quantidade > 3 || $quantidade < 2) {
            return '';
        } elseif ($quantidade == 2) {
            array_unshift($explode, 'view');
        }
        return self::$link[implode('.', $explode)] ?? '';
    }
}
