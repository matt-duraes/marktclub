<?php

namespace Erro;

use Throwable;

final class Excecao extends \Exception
{
    private string $tipo;

    /**
     * @param string         $titulo   Título para o erro
     * @param string         $mensagem Texto para a mensagem de erro
     * @param int            $status   Status Html para o erro
     * @param int            $codigo   Código para o erro
     * @param string         $campo    Campo de erro para quando usar código
     * @param array          $lista    Lista para o retorno
     * @param array          $dado     Array de dado com retorno, exemplo:
     *                                 ['titulo'=> '...', 'mensagem' => '...', ...]
     * @param array          $header   Header a ser informado na exceção
     * @param null|Throwable $previous Próximo erro
     */
    public function __construct(
        private string $titulo = '',
        private string $mensagem = '',
        private int $status = 400,
        private int $codigo = 0,
        private string $campo = '',
        private array $lista = [],
        private array $dado = [],
        private array $header = [],
        private ?Throwable $previous = null
    ) {
        $this->limparLista();
        $this->desfragmentarDado();
        $this->verificarStatusPermitido();
        $this->verificarTipoExcecao();

        parent::__construct(message: $this->mensagem, code: $this->codigo, previous: $this->previous);
    }

    public function header()
    {
        return $this->header;
    }

    public function retorno()
    {
        if ($this->tipo == 'codigo') {
            return $this->retornoCodigo();
        } elseif ($this->tipo == 'status') {
            return $this->retornoPagina();
        }
        return $this->retornoNormal();
    }

    public function retornoNormal()
    {
        $retorno = [];
        if (!empty($this->titulo)) {
            $retorno['titulo'] = $this->titulo;
        }
        if (!empty($this->mensagem)) {
            $retorno['mensagem'] = $this->mensagem;
        }
        if (!empty($this->codigo)) {
            $retorno['codigo'] = $this->codigo;
        }
        if (!empty($this->lista)) {
            $retorno = array_merge($this->lista, $retorno);
        }

        if ($this->acao() == 'json') {
            return [
                'status' => 'erro',
                'erro'   => $retorno
            ];
        }
        return $this->retornarPaginaHtml($retorno);
    }

    public function retornoCodigo()
    {
        $linha = file(ROOT . '/files/erro/lista.txt');

        $titulo = '';
        $mensagem = '';
        $codigo = $this->codigo;

        if ($this->eCodigoPadraoErro($codigo)) {
            $this->status = $codigo;
        }

        foreach ($linha as $l) {
            $explode = explode(':', $l);
            if ($explode[0] == $codigo) {
                $titulo = $explode[1];
                $mensagem = str_replace(
                    ['{CAMPO}', '  '],
                    [$this->campo, ' '],
                    trim(preg_replace('/\s\s+/', ' ', $explode[2]))
                );
                break;
            }
        }
        if (empty($titulo) && empty($mensagem)) {
            throw new Erro(
                mensagem: 'Você não passou um código que está na lista de erro, verifique o código informado ou adicione ele na lista.',
                arquivo: 'trace:1'
            );
        }

        $retorno = ['codigo' => $codigo];
        if (!empty($titulo)) {
            $retorno['titulo'] = $titulo;
        }
        if (!empty($mensagem)) {
            $retorno['mensagem'] = $mensagem;
        }
        if ($this->lista) {
            $retorno = array_merge($retorno, $this->lista);
        }
        if ($this->acao() == 'json') {
            return [
                'status' => 'erro',
                'erro'   => $retorno
            ];
        }
        return $this->retornarPaginaHtml($retorno);
    }

    public function retornoPagina(): array | string
    {
        $acao = $this->acao();
        $status = $this->status;
        if (!$this->eCodigoPadraoErro($status)) {
            throw new Erro(
                mensagem: 'Você deve passar um status 400, 401, 403, 404 ou 500 para chamar uma página de status Html.',
                arquivo: 'trace:1'
            );
        }
        if ($acao == 'json' && 400 == $status) {
            return [
                'status' => 'erro',
                'erro'   => [
                    'titulo'   => 'Erro de requisição!',
                    'mensagem' => 'Foi enviado uma requisição ruim (Bad Request), verifique os dados enviado e tente novamente.',
                    'codigo'   => 400
                ]
            ];
        } elseif ($acao == 'json' && 401 == $status) {
            return [
                'status' => 'erro',
                'erro'   => [
                    'titulo'   => 'Erro de permissão!',
                    'mensagem' => 'Você não autenticou essa requisição, faça sua autenticação e tente novamente.',
                    'codigo'   => 401
                ]
            ];
        } elseif ($acao == 'json' && 403 == $status) {
            return [
                'status' => 'erro',
                'erro'   => [
                    'titulo'   => 'Erro de permissão!',
                    'mensagem' => 'Você não tem permissão para acessar essa informação, verifique suas permissões e tente novamente.',
                    'codigo'   => 403
                ]
            ];
        } elseif ($acao == 'json' && 404 == $status) {
            return [
                'status' => 'erro',
                'erro'   => [
                    'titulo'   => 'Página não existe!',
                    'mensagem' => 'Essa página ou recurso não existe ou foi movida para outra URL.',
                    'codigo'   => 404
                ]
            ];
        } elseif ($acao == 'json' && 500 == $status) {
            return [
                'status' => 'erro',
                'erro'   => [
                    'titulo'   => 'Erro interno!',
                    'mensagem' => 'Ocorreu um erro interno, por favor, tente novamente, se o erro persistir, contate o suporte.',
                    'codigo'   => 500
                ]
            ];
        }

        $diretorio = defined('ROUTE_DIRETORIO') ? ROUTE_DIRETORIO : 'Site';
        if (
            file_exists(
                ROOT . '/html/views/' . mb_strtolower($diretorio, 'UTF-8') . '/erro_geral/' . $status . '.php'
            )
        ) {
            ob_start();
            require_once ROOT . '/html/views/' . mb_strtolower($diretorio, 'UTF-8') . '/erro_geral/' . $status . '.php';
            return ob_get_clean();
        }

        ob_start();
        require_once ROOT . '/src/Html/Excecao/' . $status . '.php';
        return ob_get_clean();
    }

    public function acao(): string
    {
        $header = getallheaders();
        $metodo = $_SERVER['REQUEST_METHOD'];

        $contentType = $_SERVER['HTTP_CONTENT_TYPE'] ?? $_SERVER['CONTENT_TYPE'] ??
            $_SERVER['HTTP_ACCEPT'] ?? $_SERVER['ACCEPT'] ?? $header['Content-type'] ??
            $header['Content-Type'] ?? $header['content-type'] ?? $header['Accept'] ??
            $header['accept'] ?? false;
        $contentType = explode(',', $contentType)[0] ?? false;

        if ($metodo == 'GET' && mb_strtolower($contentType, 'UTF-8') != 'application/json' && !isset($_GET['ajax'])) {
            return 'html';
        }
        return 'json';
    }

    public function status()
    {
        $status = $this->status;
        if ($status >= 400 || $status < 600) {
            return $status;
        }
        return 400;
    }

    private function retornarPaginaHtml(array $dado)
    {
        if (SISTEMA == 'LOCALHOST') {
            $dado += [
                'erroArquivo' => $this->getFile(),
                'erroLinha'   => $this->getLine(),
                'erroTrace'   => $this->getTraceAsString(),
            ];
        }

        $diretorio = defined('ROUTE_DIRETORIO') ? ROUTE_DIRETORIO : 'Site';
        if (file_exists(ROOT . '/html/views/' . mb_strtolower($diretorio, 'UTF-8') . '/erro_geral/excecao.php')) {
            ob_start();
            require_once ROOT . '/html/views/' . mb_strtolower($diretorio, 'UTF-8') . '/erro_geral/excecao.php';
            return ob_get_clean();
        }

        ob_start();
        require_once ROOT . '/src/Html/Excecao/excecao.php';
        return ob_get_clean();
    }

    private function limparLista(): void
    {
        $lista = $this->lista;
        if (!$lista) {
            return;
        }
        unset($lista['titulo'], $lista['texto'], $lista['codigo'], $lista['status']);
        $this->lista = $lista;
    }

    private function desfragmentarDado(): void
    {
        $dado = $this->dado;
        if (!$dado) {
            return;
        }
        if (isset($dado['titulo']) && !empty($dado['titulo'])) {
            $this->titulo = $dado['titulo'];
            unset($dado['titulo']);
        }
        if (isset($dado['mensagem']) && !empty($dado['mensagem'])) {
            $this->mensagem = $dado['mensagem'];
            unset($dado['mensagem']);
        }
        if (isset($dado['status']) && !empty($dado['status'])) {
            $this->status = $dado['status'];
            unset($dado['status']);
        }
        if (isset($dado['codigo']) && !empty($dado['codigo'])) {
            $this->codigo = $dado['codigo'];
            unset($dado['codigo']);
        }

        if ($dado) {
            $this->lista = array_merge($this->lista, $dado);
        }
        $this->dado = [];
    }

    private function verificarStatusPermitido(): void
    {
        $status = $this->status;
        if ($status < 400 || $status > 599) {
            throw new Erro(
                mensagem: 'Você deve passar um status de erro válido para a exceção.',
                arquivo: 'trace:1'
            );
        }
    }

    private function verificarTipoExcecao(): void
    {
        $this->tipo = 'normal';
        $mensagemLimpa = empty($this->titulo) && empty($this->mensagem);
        if ($mensagemLimpa && !empty($this->codigo)) {
            $this->tipo = 'codigo';
        } elseif ($mensagemLimpa && !empty($this->status)) {
            $this->tipo = 'status';
        }
    }

    private function eCodigoPadraoErro(int $codigo)
    {
        return in_array($codigo, [400, 401, 403, 404, 500]);
    }
}
