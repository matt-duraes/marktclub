<?php

namespace Http;

use Erro\Erro;
use Helpers\CryptHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request as Psr7Request;

final class Request extends Psr7Request
{
    private Psr7Request $__requestInterno;
    private string $__metodo;
    private array $__dado = [];

    public function __construct()
    {
        $this->__requestInterno = Psr7Request::createFromGlobals();
        $this->__metodo = $this->__requestInterno->getMethod();
        $this->setarDado();
        $this->setarPropriedadesPublicas();
    }

    public function __get(string $propriedade)
    {
        $dado = $this->dado();
        if (!array_key_exists($propriedade, $dado)) {
            return null;
        }
        return $dado[$propriedade];
    }

    // doc
    /**
     * Retorna a classe origin Symfony\Component\HttpFoundation\Request
     *
     * @return Symfony\Component\HttpFoundation\Request Classe original do Symfony
     */
    public function request(): Psr7Request
    {
        return $this->__requestInterno;
    }

    // doc
    /**
     * Verifica se um parâmetro foi enviado na request
     *
     * @param   string $parametro   Parametro que deseja validar
     * @return  bool                True caso o parâmetro exista
     */
    public function existe(string $parametro): bool
    {
        $dado = $this->dado();
        return array_key_exists($parametro, $dado);
    }

    // doc
    /**
     * Pega uma chave específica da request
     *
     * @param string        $indice         Indice do item que deseja retornar
     * @param mixed         $padrao         Valor padrão caso o indice não exista
     * @return mixed                        O indice achado ou o valor padrão informado
     * @throws Erro\Excecao                    Caso o indice não exista e não tenha sido passado um valor padrão, irá disparar uma Excecao
     */
    public function chave(string $indice, $padrao = null)
    {
        if (!isset($this->__dado[$indice]) && null === $padrao) {
            return throw new \Erro\Excecao(
                titulo: 'Chave ' . $indice . ' não existe.',
                mensagem: 'A Chave "' . $indice . '" não existe na request enviada.'
            );
        }
        return $this->__dado[$indice] ?? $padrao;
    }

    // doc
    /**
     * Pega a lista de dados enviado na request limpando os valores usados apenas pelo sistema
     *
     * @return array    Array com a lista de dados recebidos pela request
     */
    public function dado(): array
    {
        $dado = $this->__dado;
        if (array_key_exists('ajax', $dado)) {
            unset($dado['ajax']);
        }
        if (array_key_exists('form_system_captcha', $dado)) {
            unset($dado['form_system_captcha']);
        }
        if (array_key_exists('form_system_hash', $dado)) {
            unset($dado['form_system_hash']);
        }
        if (array_key_exists('form_system_validacao', $dado)) {
            unset($dado['form_system_validacao']);
        }
        return $dado;
    }

    //doc
    /**
     * Pega a lista de dados selecionada pelo usuário
     *
     * @param   array   $lista      Lista de indices que serão retornados
     * @param   bool    $erro       Se o sistema deve retornar uma erro quando um indice não existir
     * @return  array               Array com a lista de dados recebidos pela request
     * @throws  Erro\Excecao        Caso $erro for true e não exista um indice da lista
     */
    public function lista(array $lista, bool $erro = true): array
    {
        if (empty($this->__dado) && !$erro) {
            return [];
        } elseif (empty($this->__dado)) {
            throw new \Erro\Excecao(titulo: 'Request vazia!', mensagem: 'Não existe nenhum dado na requisição para poder listar.');
        }

        $array = [];
        foreach ($this->__dado as $ind => $val) {
            if (in_array($ind, $lista)) {
                $array[$ind] = $val;
            } elseif ($erro) {
                throw new \Erro\Excecao(titulo: 'Indice não encontrado!', mensagem: 'O indice "' . $ind . '" não existe na requisição enviada.');
            }
        }
        return $array;
    }

    //doc
    /**
     * O contrário da lista, aqui você indica os dados que não quer achar
     *
     * @param   array           $lista      Lista de indices que serão removidos
     * @param   bool            $erro       Se o sistema deve retornar uma erro quando um indice não existir
     * @return  array                       Array com a lista de dados recebidos pela request
     * @throws  Erro\Excecao                Caso $erro for true e não exista um indice da lista
     */
    public function exeto(array $lista, bool $erro = true): array
    {
        if (empty($this->__dado) && !$erro) {
            return [];
        } elseif (empty($this->__dado)) {
            throw new Erro(mensagem: 'Não existe nenhum dado na requisição para poder remover.');
        }

        $dado = $this->__dado;
        foreach ($lista as $ind) {
            if (isset($dado[$ind])) {
                unset($dado[$ind]);
            } elseif ($erro) {
                throw new Erro(mensagem: 'A exeção "' . $ind . '" não existe na requisição enviada.');
            }
        }
        return $dado;
    }

    // doc
    /**
     * Igual o dado() mas quando os dados estão criptografados
     *
     * @param null|string   $chave          Caso queira usar uma chave simples para descriptografar
     * @param null|string   $chavePrivada   Caso queira usar uma chave privada para uma criptografia criada por chave pública
     * @return  array                       Array com a lista de dados recebidos pela request
     */
    public function dadoDecode(?string $chave = null, ?string $chavePrivada = null): array
    {
        if (empty($chave) && empty($chavePrivada)) {
            return [];
        }
        if (!empty($chave)) {
            $Crypt = new CryptHelper(chave: $chave);
        } else if (!empty($chavePrivada)) {
            $Crypt = new CryptHelper(chavePrivada: $chavePrivada);
        }

        $dado = $this->dado();
        $retorno = [];
        foreach ($dado as $ind => $val) {
            $valorDecode = $Crypt->decode($val);
            if (!empty($val) && empty($valorDecode)) {
                mensagemErro('Erro!', 'Não foi possível remover a criptografia do indice ' . $ind . '.');
            }
            $retorno[$ind] = $valorDecode;
        }

        return $this->purifier($retorno, purifier: true, html: true);
    }

    // doc
    /**
     * Pega todos os dados recebidos pela request, mesmo os que são usados apenas pelo sistema
     *
     * @return  array   Array com a lista de dados recebidos pela request
     */
    public function todos(): array
    {
        return $this->__dado;
    }

    // doc
    /**
     * Pega os dados da request quando eles foream enviados via JSON
     *
     * @param string    $indice         Indice que será acessado
     * @param bool      $purifier       Se true, o retorno será purificado
     * @param bool      $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  string|array   Array com a lista de dados recebidos pela request ou o valor do insice
     */
    public function json(string $indice = '', bool $purifier = true, bool $html = true): array|string
    {
        $dado = jsonDecode($this->body(), true);
        if (is_array($dado)) {
            return $this->purifier($dado, $indice, $purifier, $html);
        }
        return !empty($indice) ? '' : [];
    }

    // doc
    /**
     * Pegar o mesmo valor do $_GET
     *
     * @param string    $indice         Indice que será acessado
     * @param bool      $purifier       Se true, o retorno será purificado
     * @param bool      $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  array   Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function _GET(string $indice = '', bool $purifier = true, bool $html = true): array | string
    {
        return $this->purifier($this->__requestInterno->query->all(), $indice, $purifier, $html);
    }

    // doc
    /**
     * Pega o mesmo valor do $_POST
     *
     * @param string    $indice         Indice que será acessado
     * @param bool      $purifier       Se true, o retorno será purificado
     * @param bool      $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  array   Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function _POST(string $indice = '', bool $purifier = true, bool $html = true): array | string
    {
        if (!in_array($this->__metodo, ['POST', 'GET'])) {
            throw new Erro(mensagem: 'Você está tentando pegar um POST em uma requisição com método ' . $this->__metodo . '.');
        }

        $_POST = $this->__requestInterno->request->all();
        if (is_array($_POST)) {
            return $this->purifier($_POST, $indice, $purifier, $html);
        }

        $phpInput = $this->__requestInterno->getContent();

        $_POST = jsonDecode($phpInput, true);
        if (is_array($_POST)) {
            return $this->purifier($_POST, $indice, $purifier, $html);
        }

        return empty($indice) ? [] : '';
    }

    //doc
    /**
     * Pegar a request igual o $_POST mas quando for usado o método PUT
     *
     * @param string    $indice         Indice que será acessado
     * @param bool      $purifier       Se true, o retorno será purificado
     * @param bool      $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  array   Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function _PUT(string $indice = '', bool $purifier = true, bool $html = true): array | string
    {
        if (!in_array($this->__metodo, ['PUT', 'GET'])) {
            throw new Erro(mensagem: 'Você está tentando pegar um PUT em uma requisição com método ' . $this->__metodo . '.');
        }

        $_PUT = $this->__requestInterno->request->all();
        if (!empty($_PUT)) {
            return $this->purifier($_PUT, $indice, $purifier, $html);
        }

        $phpInput = $this->__requestInterno->getContent();

        $_PUT = jsonDecode($phpInput, true);
        if (is_array($_PUT)) {
            return $this->purifier($_PUT, $indice, $purifier, $html);
        }

        $_PUT = $this->retornarPhpInput($phpInput);

        if (is_array($_PUT)) {
            return $this->purifier($_PUT, $indice, $purifier, $html);
        }

        return empty($indice) ? [] : '';
    }

    // doc
    /**
     * Pegar o mesmo valor do $_FILES
     *
     * @param string    $indice     Indice que será acessado
     * @return  null|array|\Symfony\Component\HttpFoundation\File\UploadedFile   Array com a lista de arquivos recebidos pela request ou UploadedFile do Symfony caso passa um indice ou null caso não ache o indice
     * @throws  Erro\Execao     Caso tente pegar um arquivo em um método que não seja POST
     */
    public function _FILES(string $indice = ''): null|array|\Symfony\Component\HttpFoundation\File\UploadedFile
    {
        if ($this->__metodo != 'POST') {
            throw new \Erro\Excecao(titulo: 'Erro no método!', mensagem: 'FILES só podem ser pegos em uma requisição com método POST.');
        } elseif (!empty($indice)) {
            return $this->__requestInterno->files->get($indice, null);
        }
        return $this->__requestInterno->files->all();
    }

    // doc
    /**
     * Pegar o headers recebidos pelo request
     *
     * @param string    $indice         Indice que será acessado
     * @param bool      $purifier       Se true, o retorno será purificado
     * @param bool      $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  array   Array com a lista de header recebidos pela request ou o valor do indice
     */
    public function header(string $indice = '', bool $purifier = false, bool $html = true): array | string
    {
        return $this->purifier($this->__requestInterno->headers->all(), $indice, $purifier, $html);
    }

    // doc
    /**
     * Pega os cookies recebidos pela request
     *
     * @param string    $indice         Indice que será acessado
     * @param bool      $purifier       Se true, o retorno será purificado
     * @param bool      $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  array   Array com a lista de cookies recebidos pela request ou o valor do indice
     */
    public function cookie(string $indice = '', bool $purifier = false, bool $html = true): array | string
    {
        return $this->purifier($this->__requestInterno->cookies->all(), $indice, $purifier, $html);
    }

    // doc
    /**
     * Pega o $_SERVER recebido pela request
     *
     * @param string    $indice         Indice que será acessado
     * @param bool      $purifier       Se true, o retorno será purificado
     * @param bool      $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  array   Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function server(string $indice = '', bool $purifier = false, bool $html = false): array | string
    {
        return $this->purifier($this->__requestInterno->server->all(), $indice, $purifier, $html);
    }

    // doc
    /**
     * Pega o body da request
     *
     * @return string String com o body
     */
    public function body(): string
    {
        return $this->__requestInterno->getContent();
    }

    // doc
    /**
     * Pega a URL da request
     *
     * @return string String com a URl da request
     */
    public function url(): string
    {
        return $this->__requestInterno->getUri();
    }

    // doc
    /**
     * Pega a URI da request
     *
     * @return string String com a URI da request
     */
    public function uri(): string
    {
        return $this->__requestInterno->getPathInfo();
    }

    // doc
    /**
     * Pega o método usado na request
     *
     * @return string String com o método usado na request
     */
    public function metodo(): string
    {
        return $this->__metodo;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function setarDado(): void
    {
        $metodo = $this->__metodo;

        $lista = [];
        if (in_array($metodo, ['GET', 'DELETE'])) {
            $lista = $this->__requestInterno->query->all();
        } elseif (in_array($metodo, ['POST', 'PUT'])) {
            $lista = $this->pegarRequestOuBody();
        }

        $lista = $this->purifier(lista: $lista, purifier: true, html: true);
        if ($metodo == 'POST') {
            $file = $this->__requestInterno->files->all();
            if ($file) {
                $lista = $lista + $file;
            }
        }

        $this->__dado = $lista;
        return;
    }

    private function setarPropriedadesPublicas()
    {
        if (!$this->__dado) {
            return;
        }
        foreach ($this->__dado as $ind => $val) {
            $this->$ind = $val;
        }
    }

    private function pegarRequestOuBody(): array
    {
        $dado = $this->__requestInterno->request->all();
        if (!empty($dado)) {
            return $dado;
        }
        $phpInput = $this->__requestInterno->getContent();
        $body = jsonDecode($phpInput, true);
        if (is_array($body) && $body) {
            return $body;
        }
        return $this->retornarPhpInput($phpInput);
    }
    private function retornarPhpInput($input)
    {
        if (empty($input)) {
            return [];
        }
        $explode = explode(PHP_EOL, $input);

        $dado = [];
        $quantidade = count($explode);
        for ($i = 0; $i < $quantidade;) {
            if (preg_match('/^-{1,}[a-f0-9]+/', $explode[$i])) {
                $numeroInidice = $i + 1;
                $numeroValor = $i + 3;
                if (!array_key_exists($numeroInidice, $explode) || !array_key_exists($numeroValor, $explode)) {
                    break;
                }
                $indice = explode('name="', $explode[$numeroInidice]);
                if (count($indice) < 1) {
                    break;
                }
                $dado[explode('"', $indice[1])[0]] = trim($explode[$numeroValor]);
                $i += 4;
                continue;
            }
            $i++;
        }
        return $dado;
    }

    private function purifier(array $lista, string $indice = '', bool $purifier = true, bool $html = true): array | string
    {
        $config = \HTMLPurifier_Config::createDefault();
        $def = $config->getHTMLDefinition(1);
        $def->addAttribute('a', 'target', new \HTMLPurifier_AttrDef_Enum(['_blank']));
        $def->addAttribute('a', 'download', new \HTMLPurifier_AttrDef_Enum(['download', '']));
        $def->addElement('section', 'Block', 'Flow', 'Common');
        $Purifier = new \HTMLPurifier($config);

        if (!$lista) {
            return !empty($indice) ? '' : [];
        } elseif (!$purifier && !$html && !empty($indice)) {
            return array_key_exists($indice, $lista) ? $lista[$indice] : '';
        } elseif (!$purifier && !$html) {
            return $lista;
        } elseif (!empty($indice) && !array_key_exists($indice, $lista)) {
            return '';
        } elseif (!empty($indice) && is_array($lista[$indice])) {
            return $this->purifier(lista: $lista[$indice], purifier: $purifier, html: $html);
        } elseif (!empty($indice)) {
            $valor = $lista[$indice];
            if ($purifier) {
                $valor = $Purifier->purify($valor);
            }
            if ($html) {
                $valor = strip_tags($valor);
            }
            return $valor;
        }

        $dado = [];
        foreach ($lista as $ind => $val) {
            if (is_array($val)) {
                $dado[$ind] = $this->purifier(lista: $val, purifier: $purifier, html: $html);
                continue;
            }
            if ($purifier) {
                $val = $Purifier->purify($val);
            }
            if ($html) {
                $val = strip_tags($val);
            }
            $dado[$ind] = $val;
        }
        return $dado;
    }
}
