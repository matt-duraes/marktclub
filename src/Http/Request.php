<?php

namespace Http;

use Erro\Erro;
use Helpers\CryptHelper;
use Symfony\Component\HttpFoundation\Request as Psr7Request;

final class Request extends Psr7Request
{
    private Psr7Request $__requestInterno;
    private string $__metodo;
    private array $__dado = [];

    public function __construct(array $descriptografar = [], ?string $chave = null)
    {
        $this->__requestInterno = Psr7Request::createFromGlobals();
        $this->__metodo = $this->__requestInterno->getMethod();

        $chave = !empty($descriptografar) && empty($chave) && defined('TOKEN') && array_key_exists('app', TOKEN) ? TOKEN['app']->chave_privada : '';
        $this->setarDado($descriptografar, $chave);
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
     * @param   string          $parametro  Parametro que deseja validar
     * @param   null|string     $titulo     Título caso deseja retornar um erro
     * @param   null|string     $mensagem   Mensagem caso deseja retornar um erro
     * @return  bool|self                   True caso o parâmetro exista ou self se tive passado mensagem de erro
     * @throws  Erro\Excecao                Erro caso o campo parametro não exista e tenha passado uma mensagem de erro
     */
    public function existe(string $parametro, ?string $titulo = null, ?string $mensagem = null): bool
    {
        $dado = $this->dado();
        $existe = array_key_exists($parametro, $dado);
        if (!$existe && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo obrigatório!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } else if ($existe && !empty($mensagem)) {
            return $this;
        }
        return $existe;
    }

    // doc
    /**
     * Verifica que um parâmetro não foi enviado ou se ele está vazio
     *
     * @param   string          $parametro  Parametro que deseja validar
     * @param   null|string     $titulo     Título caso deseja retornar um erro
     * @param   null|string     $mensagem   Mensagem caso deseja retornar um erro
     * @return  bool|self                   True caso não exista ou esteja vazio ou self se tive passado mensagem de erro
     * @throws  Erro\Excecao                Erro caso o campo esteja vazio e tenha passado uma mensagem de erro
     */
    public function vazio(string $parametro, ?string $titulo = null, ?string $mensagem = null): bool|self
    {
        $dado = $this->dado();
        $vazio = !array_key_exists($parametro, $dado) || empty($dado[$parametro]);
        if ($vazio && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo obrigatório!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } else if (!$vazio && !empty($mensagem)) {
            return $this;
        }
        return $vazio;
    }

    // doc
    /**
     * Verifica que um parâmetro é uma data valida
     *
     * @param   string          $parametro  Parametro que deseja validar
     * @param   null|string     $titulo     Título caso deseja retornar um erro
     * @param   null|string     $mensagem   Mensagem caso deseja retornar um erro
     * @return  bool|self                   True caso não exista ou esteja vazio ou self se tive passado mensagem de erro
     * @throws  Erro\Excecao                Erro caso o campo esteja vazio e tenha passado uma mensagem de erro
     */
    public function validarData(string $parametro, ?string $titulo = null, ?string $mensagem = null): bool|self
    {
        $dado = $this->dado();
        $eData = array_key_exists($parametro, $dado) && validarData($dado[$parametro]);

        if (!$eData && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo inválido!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } else if ($eData && !empty($mensagem)) {
            return $this;
        }
        return $eData;
    }
    // doc
    /**
     * Verifica que um parâmetro é uma date valida
     *
     * @param   string          $parametro  Parametro que deseja validar
     * @param   null|string     $titulo     Título caso deseja retornar um erro
     * @param   null|string     $mensagem   Mensagem caso deseja retornar um erro
     * @return  bool|self                   True caso não exista ou esteja vazio ou self se tive passado mensagem de erro
     * @throws  Erro\Excecao                Erro caso o campo esteja vazio e tenha passado uma mensagem de erro
     */
    public function validarDate(string $parametro, ?string $titulo = null, ?string $mensagem = null): bool|self
    {
        $dado = $this->dado();
        $eData = array_key_exists($parametro, $dado) && validarDate($dado[$parametro]);

        if (!$eData && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo inválido!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } else if ($eData && !empty($mensagem)) {
            return $this;
        }
        return $eData;
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
            throw new \Erro\Excecao(
                titulo: 'Chave ' . $indice . ' não existe.',
                mensagem: 'A Chave "' . $indice . '" não existe na request enviada.'
            );
        }
        return array_key_exists($indice, $this->__dado) ? $this->purifier(lista: $this->__dado, indice: $indice) : $padrao;
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
        return $this->purifier($dado);
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
        return $this->purifier($array);
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
        return $this->purifier($dado);
    }

    // doc
    /**
     * Pega todos os dados recebidos pela request, mesmo os que são usados apenas pelo sistema
     *
     * @return  array   Array com a lista de dados recebidos pela request
     */
    public function todos(): array
    {
        return $this->purifier($this->__dado);
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
    public function _JSON(string $indice = '', bool $purifier = true, bool $html = true): array|string
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
     * @param   string          $indice         Indice que será acessado
     * @param   bool            $purifier       Se true, o retorno será purificado
     * @param   bool            $html           Se true, o retorno irá limpar qualquer tag HTML
     * @return  array|string                    Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function _POST(string $indice = '', bool $purifier = true, bool $html = true): array | string
    {
        if (!in_array($this->__metodo, ['POST', 'GET'])) {
            return !empty($indice) ? false : [];
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
        if ($this->__metodo != 'PUT') {
            return !empty($indice) ? false : [];
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
            return !empty($indice) ? false : [];
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
    private function setarDado(array $descriptografar = [], ?string $chave = null): void
    {
        $metodo = $this->__metodo;

        $lista = [];
        if (in_array($metodo, ['GET', 'DELETE'])) {
            $lista = $this->_JSON() ? $this->__requestInterno->query->all() + $this->_JSON() : $this->__requestInterno->query->all();
        } elseif (in_array($metodo, ['POST', 'PUT'])) {
            $lista = $this->pegarRequestOuBody();
        }

        $file = $this->__requestInterno->files->all();
        if ($metodo == 'POST' && $file) {
            $lista = $lista + $file;
        }

        if (empty($chave)) {
            $this->__dado = $lista;
            return;
        }

        $Crypt = new CryptHelper(chavePrivada: $chave);
        foreach ($lista as $ind => $val) {
            if (!empty($descriptografar) && (!in_array($ind, $descriptografar) || array_key_exists($ind, $file))) {
                $lista[$ind] = $val;
                continue;
            }

            $valorDescriptografado = $Crypt->decode($val);
            if (!empty($val) && empty($valorDescriptografado)) {
                mensagemErro('Erro!', 'O indice ' . $ind . ' não pode ser descriptografado.');
            }
            $lista[$ind] = $valorDescriptografado;
        }

        $this->__dado = $lista;
    }

    private function setarPropriedadesPublicas($teste = false)
    {
        if (!$this->__dado) {
            return;
        }

        foreach (array_keys($this->__dado) as $ind) {
            $this->$ind = $this->purifier(lista: $this->__dado, indice: $ind);
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
        $def->addElement('figcaption', 'Block', 'Flow', 'Common');
        $def->addElement('figure', 'Block', 'Flow', 'Common');
        $def->addElement('tbody', false, 'Required: tr', 'Common');
        $def->addElement('thead', false, 'Required: tr', 'Common');
        $def->addElement('code', false, 'Flow', 'Common');
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
            if ($html) {
                $valor = strip_tags($valor);
            }
            if ($purifier) {
                $valor = $Purifier->purify($valor);
            }
            return $valor;
        }

        $dado = [];
        foreach ($lista as $ind => $val) {
            if (is_array($val)) {
                $dado[$ind] = $this->purifier(lista: $val, purifier: $purifier, html: $html);
                continue;
            }
            if ($html) {
                $val = !empty($val) ? strip_tags($val) : '';
            }
            if ($purifier) {
                $val = $Purifier->purify($val);
            }
            $dado[$ind] = $val;
        }
        return $dado;
    }
}
