<?php

namespace Http;

use Erro\Erro;
use Erro\Excecao;
use HTMLPurifier;
use Helpers\CryptHelper;
use HTMLPurifier_Config;
use HTMLPurifier_AttrDef_Enum;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request as Psr7Request;

final class Request extends Psr7Request
{
    private Psr7Request $psr7Request;
    private string $metodo;
    private array $dados = [];

    /**
     * @throws Excecao
     */
    public function __construct(array $descriptografar = [], string $chave = null)
    {
        $this->psr7Request = Psr7Request::createFromGlobals();
        $this->metodo = $this->psr7Request->getMethod();

        $chave = !empty($descriptografar) && empty($chave) && defined('TOKEN') && array_key_exists(
            'app',
            TOKEN
        )
            ? TOKEN['app']->chave_privada
            : '';
        $this->setarDado($descriptografar, $chave);
        $this->setarPropriedadesPublicas();
    }

    /**
     * @throws Excecao
     */
    private function setarDado(array $descriptografar = [], ?string $chave = null): void
    {
        $lista = [];
        if (in_array($this->metodo, ['GET', 'DELETE'], true)) {
            $lista = $this->getJson()
                ? $this->psr7Request->query->all() + $this->getJson()
                : $this->psr7Request->query->all();
        } elseif (in_array($this->metodo, ['POST', 'PUT'], true)) {
            $lista = $this->pegarRequestOuBody();
        }

        $file = $this->psr7Request->files->all();
        if ($this->metodo === 'POST' && $file) {
            $lista = $lista + $file;
        }

        if (empty($chave)) {
            $this->dados = $lista;
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

        $this->dados = $lista;
    }

    // doc
    /**
     * Pega os dados da request quando eles foream enviados via JSON
     *
     * @param string $indice   Indice que será acessado
     * @param bool   $purifier Se true, o retorno será purificado
     * @param bool   $html     Se true, o retorno irá limpar qualquer tag HTML
     *
     * @return string|array Array com a lista de dados recebidos pela request ou o valor do insice
     */
    public function getJson(string $indice = '', bool $purifier = true, bool $html = true): array|string
    {
        $dados = jsonDecode($this->body(), true);
        if (is_array($dados)) {
            return $this->purifier($dados, $indice, $purifier, $html);
        }
        return !empty($indice) ? '' : [];
    }

    // doc
    /**
     * Pega o body da request
     *
     * @return string String com o body
     */
    public function body(): string
    {
        return $this->psr7Request->getContent();
    }

    // doc
    /**
     * @param array  $lista
     * @param string $indice
     * @param bool   $purifier
     * @param bool   $html
     *
     * @return array|string
     */
    private function purifier(array $lista, string $indice = '', bool $purifier = true, bool $html = true): array|string
    {
        $config = HTMLPurifier_Config::createDefault();
        $def = $config->getHTMLDefinition(1);
        $def->addAttribute('a', 'target', new HTMLPurifier_AttrDef_Enum(['_blank']));
        $def->addAttribute('a', 'download', new HTMLPurifier_AttrDef_Enum(['download', '']));
        $def->addElement('section', 'Block', 'Flow', 'Common');
        $def->addElement('figcaption', 'Block', 'Flow', 'Common');
        $def->addElement('figure', 'Block', 'Flow', 'Common');
        $def->addElement('tbody', false, 'Required: tr', 'Common');
        $def->addElement('thead', false, 'Required: tr', 'Common');
        $def->addElement('code', false, 'Flow', 'Common');
        $htmlPurifier = new HTMLPurifier($config);

        if (empty($lista)) {
            return !empty($indice) ? '' : [];
        } elseif (!$purifier && !$html && !empty($indice)) {
            return array_key_exists($indice, $lista) ? $lista[$indice] : '';
        } elseif (!$purifier && !$html) {
            return $lista;
        } elseif (!empty($indice) && !array_key_exists($indice, $lista)) {
            return '';
        } elseif (!empty($indice) && is_array($lista[$indice])) {
            return $this->purifier($lista[$indice], purifier: $purifier, html: $html);
        } elseif (!empty($indice)) {
            $valor = $lista[$indice];
            if ($html) {
                $valor = strip_tags($valor);
            }
            if ($purifier) {
                $valor = $htmlPurifier->purify($valor);
            }
            return $valor;
        }

        $dados = [];
        foreach ($lista as $ind => $val) {
            if (is_array($val)) {
                $dados[$ind] = $this->purifier($val, purifier: $purifier, html: $html);
                continue;
            }
            if ($html) {
                $val = !empty($val) ? strip_tags($val) : '';
            }
            if ($purifier) {
                $val = $htmlPurifier->purify($val);
            }
            $dados[$ind] = $val;
        }
        return $dados;
    }

    // doc
    /**
     * @return array
     */
    private function pegarRequestOuBody(): array
    {
        $dado = $this->psr7Request->request->all();
        if (!empty($dado)) {
            return $dado;
        }
        $phpInput = $this->psr7Request->getContent();
        $body = jsonDecode($phpInput, true);
        if (is_array($body) && $body) {
            return $body;
        }
        return $this->retornarPhpInput($phpInput);
    }

    // doc
    /**
     * @param string $input
     *
     * @return array
     */
    private function retornarPhpInput(string $input): array
    {
        if (empty($input)) {
            return [];
        }

        $inputExplode = explode(PHP_EOL, $input);
        $dado = [];

        for ($i = 0; $i < count($inputExplode);) {
            if (preg_match('/^-{1,}[a-f0-9]+/', $inputExplode[$i])) {
                $numeroInidice = $i + 1;
                $numeroValor = $i + 3;
                if (
                    !array_key_exists($numeroInidice, $inputExplode)
                    || !array_key_exists($numeroValor, $inputExplode)
                ) {
                    break;
                }
                $indice = explode('name="', $inputExplode[$numeroInidice]);
                if (count($indice) < 1) {
                    break;
                }
                $dado[explode('"', $indice[1])[0]] = trim($inputExplode[$numeroValor]);
                $i += 4;
            } else {
                $i++;
            }
        }
        return $dado;
    }

    // doc
    /**
     */
    private function setarPropriedadesPublicas(): void
    {
        if (empty($this->dados)) {
            return;
        }

        foreach (array_keys($this->dados) as $key) {
            $this->$key = $this->purifier($this->dados, $key);
        }
    }

    // doc
    /**
     * @param string $propriedade
     *
     * @return mixed
     */
    public function __get(string $propriedade)
    {
        $dados = $this->dado();
        if (!array_key_exists($propriedade, $dados)) {
            return null;
        }
        return $dados[$propriedade];
    }

    //doc
    /**
     * Pega a lista de dados enviado na request limpando os valores usados apenas pelo sistema
     *
     * @return array Array com a lista de dados recebidos pela request
     */
    public function dado(): array
    {
        $dados = $this->dados;
        if (array_key_exists('ajax', $dados)) {
            unset($dados['ajax']);
        }
        if (array_key_exists('form_system_captcha', $dados)) {
            unset($dados['form_system_captcha']);
        }
        if (array_key_exists('form_system_hash', $dados)) {
            unset($dados['form_system_hash']);
        }
        if (array_key_exists('form_system_validacao', $dados)) {
            unset($dados['form_system_validacao']);
        }
        return $this->purifier($dados);
    }

    //doc
    /**
     * Retorna a classe origin Symfony\Component\HttpFoundation\Request
     *
     * @return Psr7Request Classe original do Symfony
     */
    public function request(): Psr7Request
    {
        return $this->psr7Request;
    }

    // doc
    /**
     * Verifica se um parâmetro foi enviado na request
     *
     * @param string      $parametro Parametro que deseja validar
     * @param string|null $titulo    Título caso deseja retornar um erro
     * @param string|null $mensagem  Mensagem caso deseja retornar um erro
     *
     * @return bool|self True caso o parâmetro exista ou self se tive passado mensagem de erro
     * @throws Excecao   Erro caso o campo parametro não exista e tenha passado uma mensagem de erro
     */
    public function existe(string $parametro, string $titulo = null, string $mensagem = null): bool|self
    {
        $existe = array_key_exists($parametro, $this->dado());
        if (!$existe && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo obrigatório!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } elseif ($existe && !empty($mensagem)) {
            return $this;
        }
        return $existe;
    }

    // doc
    /**
     * Verifica que um parâmetro não foi enviado ou se ele está vazio
     *
     * @param string      $parametro Parametro que deseja validar
     * @param string|null $titulo    Título caso deseja retornar um erro
     * @param string|null $mensagem  Mensagem caso deseja retornar um erro
     *
     * @return bool|self True caso não exista ou esteja vazio ou self se tive passado mensagem de erro
     * @throws Excecao   Erro caso o campo esteja vazio e tenha passado uma mensagem de erro
     */
    public function vazio(string $parametro, string $titulo = null, string $mensagem = null): bool|self
    {
        $dados = $this->dado();
        $vazio = !array_key_exists($parametro, $dados) || empty($dados[$parametro]);
        if ($vazio && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo obrigatório!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } elseif (!$vazio && !empty($mensagem)) {
            return $this;
        }
        return $vazio;
    }

    // doc
    /**
     * Verifica que um parâmetro é uma data valida
     *
     * @param string      $parametro Parametro que deseja validar
     * @param string|null $titulo    Título caso deseja retornar um erro
     * @param string|null $mensagem  Mensagem caso deseja retornar um erro
     *
     * @return bool|self True caso não exista ou esteja vazio ou self se tive passado mensagem de erro
     * @throws Excecao   Erro caso o campo esteja vazio e tenha passado uma mensagem de erro
     */
    public function validarData(string $parametro, string $titulo = null, string $mensagem = null): bool|self
    {
        $dados = $this->dado();
        $eData = array_key_exists($parametro, $dados) && validarData($dados[$parametro]);
        if (!$eData && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo inválido!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } elseif ($eData && !empty($mensagem)) {
            return $this;
        }
        return $eData;
    }

    // doc
    /**
     * Verifica que um parâmetro é uma date valida
     *
     * @param string      $parametro Parametro que deseja validar
     * @param string|null $titulo    Título caso deseja retornar um erro
     * @param string|null $mensagem  Mensagem caso deseja retornar um erro
     *
     * @return bool|self True caso não exista ou esteja vazio ou self se tive passado mensagem de erro
     * @throws Excecao   Erro caso o campo esteja vazio e tenha passado uma mensagem de erro
     */
    public function validarDate(string $parametro, string $titulo = null, string $mensagem = null): bool|self
    {
        $dados = $this->dado();
        $eData = array_key_exists($parametro, $dados) && validarDate($dados[$parametro]);
        if (!$eData && !empty($mensagem)) {
            $titulo = empty($titulo) ? 'Campo inválido!' : $titulo;
            mensagemErro($titulo, $mensagem);
        } elseif ($eData && !empty($mensagem)) {
            return $this;
        }
        return $eData;
    }

    //doc
    /**
     * Pega uma chave específica da request
     *
     * @param string $indice Indice do item que deseja retornar
     * @param mixed  $padrao Valor padrão caso o indice não exista
     *
     * @return mixed   O indice achado ou o valor padrão informado
     * @throws Excecao Caso o indice não exista e não tenha sido passado um valor padrão, irá disparar uma Excecao
     */
    public function chave(string $indice, mixed $padrao = null): mixed
    {
        if (!empty($this->dados[$indice]) && null === $padrao) {
            throw new Excecao(
                'Chave ' . $indice . ' não existe.',
                'A Chave "' . $indice . '" não existe na request enviada.'
            );
        }
        return array_key_exists($indice, $this->dados)
            ? $this->purifier($this->dados, $indice)
            : $padrao;
    }

    // doc
    /**
     * Pega a lista de dados selecionada pelo usuário
     *
     * @param array $lista Lista de indices que serão retornados
     * @param bool  $erro  Se o sistema deve retornar uma erro quando um indice não existir
     *
     * @return array   Array com a lista de dados recebidos pela request
     * @throws Excecao Caso $erro for true e não exista um indice da lista
     */
    public function lista(array $lista, bool $erro = true): array
    {
        if (empty($this->dados) && !$erro) {
            return [];
        } elseif (empty($this->dados)) {
            throw new Excecao(
                'Request vazia!',
                'Não existe nenhum dado na requisição para poder listar.'
            );
        }

        $dados = [];
        foreach ($this->dados as $campo => $valor) {
            if (in_array($campo, $lista)) {
                $dados[$campo] = $valor;
            } elseif ($erro) {
                throw new Excecao(
                    'Indice não encontrado!',
                    'O indice "' . $campo . '" não existe na requisição enviada.'
                );
            }
        }
        return $this->purifier($dados);
    }

    // doc
    /**
     * O contrário da lista, aqui você indica os dados que não quer achar
     *
     * @param array $lista Lista de indices que serão removidos
     * @param bool  $erro  Se o sistema deve retornar uma erro quando um indice não existir
     *
     * @return array Array com a lista de dados recebidos pela request
     * @throws Erro  Caso $erro for true e não exista um indice da lista
     */
    public function exeto(array $lista, bool $erro = true): array
    {
        if (empty($this->dados) && !$erro) {
            return [];
        } elseif (empty($this->dados)) {
            throw new Erro(mensagem: 'Não existe nenhum dado na requisição para poder remover.');
        }

        $dados = $this->dados;
        foreach ($lista as $ind) {
            if (isset($dados[$ind])) {
                unset($dados[$ind]);
            } elseif ($erro) {
                throw new Erro(mensagem: 'A exceção "' . $ind . '" não existe na requisição enviada.');
            }
        }
        return $this->purifier($dados);
    }

    // doc
    /**
     * Pega todos os dados recebidos pela request, mesmo os que são usados apenas pelo sistema
     *
     * @return array Array com a lista de dados recebidos pela request
     */
    public function todos(): array
    {
        return $this->purifier($this->dados);
    }

    // doc
    /**
     * Pegar o mesmo valor do $_GET
     *
     * @param string $indice   Indice que será acessado
     * @param bool   $purifier Se true, o retorno será purificado
     * @param bool   $html     Se true, o retorno irá limpar qualquer tag HTML
     *
     * @return array|string Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function getGet(string $indice = '', bool $purifier = true, bool $html = true): array|string
    {
        return $this->purifier($this->psr7Request->query->all(), $indice, $purifier, $html);
    }

    // doc
    /**
     * Pega o mesmo valor do $_POST
     *
     * @param string $indice   Indice que será acessado
     * @param bool   $purifier Se true, o retorno será purificado
     * @param bool   $html     Se true, o retorno irá limpar qualquer tag HTML
     *
     * @return array|string|bool Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function getPost(string $indice = '', bool $purifier = true, bool $html = true): array|string
    {
        if ($this->metodo !== 'POST') {
            return !empty($indice) ? '' : [];
        }

        $_POST = $this->psr7Request->request->all();
        if (is_array($_POST) && !empty($_POST)) {
            return $this->purifier($_POST, $indice, $purifier, $html);
        }

        $phpInput = $this->psr7Request->getContent();
        $_POST = jsonDecode($phpInput, true);
        if (is_array($_POST)) {
            return $this->purifier($_POST, $indice, $purifier, $html);
        }

        return empty($indice) ? [] : '';
    }

    // doc
    /**
     * Pegar a request igual o $_POST mas quando for usado o método PUT
     *
     * @param string $indice   Indice que será acessado
     * @param bool   $purifier Se true, o retorno será purificado
     * @param bool   $html     Se true, o retorno irá limpar qualquer tag HTML
     *
     * @return array|string|bool Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function getPut(string $indice = '', bool $purifier = true, bool $html = true): array|string|bool
    {
        if ($this->metodo !== 'PUT') {
            return !empty($indice) ? false : [];
        }

        $_PUT = $this->psr7Request->request->all();
        if (!empty($_PUT)) {
            return $this->purifier($_PUT, $indice, $purifier, $html);
        }

        $phpInput = $this->psr7Request->getContent();

        $_PUT = jsonDecode($phpInput, true);
        if (is_array($_PUT)) {
            return $this->purifier($_PUT, $indice, $purifier, $html);
        }

        $_PUT = $this->retornarPhpInput($phpInput);
        if (!empty($_PUT)) {
            return $this->purifier($_PUT, $indice, $purifier, $html);
        }

        return empty($indice) ? [] : '';
    }

    // doc
    /**
     * Pegar o mesmo valor do $_FILES
     *
     * @param string $indice Indice que será acessado
     *
     * @return UploadedFile|array|bool|null Array com a lista de arquivos recebidos pela request ou UploadedFile do
     *                                      Symfony caso passa um indice ou null caso não ache o indice
     */
    public function getFiles(string $indice = ''): UploadedFile|array|bool|null
    {
        if ($this->metodo !== 'POST') {
            return !empty($indice) ? false : [];
        } elseif (!empty($indice)) {
            return $this->psr7Request->files->get($indice);
        }
        return $this->psr7Request->files->all();
    }

    // doc
    /**
     * Pegar o headers recebidos pelo request
     *
     * @param string $indice   Indice que será acessado
     * @param bool   $purifier Se true, o retorno será purificado
     * @param bool   $html     Se true, o retorno irá limpar qualquer tag HTML
     *
     * @return array|string Array com a lista de header recebidos pela request ou o valor do indice
     */
    public function header(string $indice = '', bool $purifier = false, bool $html = true): array|string
    {
        return $this->purifier($this->psr7Request->headers->all(), $indice, $purifier, $html);
    }

    /**
     * Pega os cookies recebidos pela request
     *
     * @param string $indice   Indice que será acessado
     * @param bool   $purifier Se true, o retorno será purificado
     * @param bool   $html     Se true, o retorno irá limpar qualquer tag HTML
     *
     * @return array|string Array com a lista de cookies recebidos pela request ou o valor do indice
     */
    public function cookie(string $indice = '', bool $purifier = false, bool $html = true): array|string
    {
        return $this->purifier($this->psr7Request->cookies->all(), $indice, $purifier, $html);
    }

    /**
     * Pega o $_SERVER recebido pela request
     *
     * @param string $indice   Indice que será acessado
     * @param bool   $purifier Se true, o retorno será purificado
     * @param bool   $html     Se true, o retorno irá limpar qualquer tag HTML
     *
     * @return array|string Array com a lista de dados recebidos pela request ou o valor do indice
     */
    public function server(string $indice = '', bool $purifier = false, bool $html = false): array|string
    {
        return $this->purifier($this->psr7Request->server->all(), $indice, $purifier, $html);
    }

    /**
     * Pega a URL da request
     *
     * @return string String com a URl da request
     */
    public function url(): string
    {
        return $this->psr7Request->getUri();
    }

    /**
     * Pega a URI da request
     *
     * @return string String com a URI da request
     */
    public function uri(): string
    {
        return $this->psr7Request->getPathInfo();
    }

    /**
     * Pega o método usado na request
     *
     * @return string String com o método usado na request
     */
    public function metodo(): string
    {
        return $this->metodo;
    }
}
