<?php

namespace Helpers;

use Erro\Excecao;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Cookie;
use Facebook\WebDriver\Exception\WebDriverCurlException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverCheckboxes;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverRadios;
use Facebook\WebDriver\WebDriverSelect;
use Throwable;

final class WebDriverHelper
{
    private string $mensagemPadrao = 'Ocorreu um erro no sistema, por favor, tente novamente.';
    private RemoteWebDriver $Driver;
    private array|RemoteWebElement $ElementoAtual;

    /**
     * @param string                   $url        URL do servidor selenium (Caso de conexão recusada, tente colocar
     *                                             o IP Local. ex: 192.168.0.100)
     * @param DesiredCapabilities|null $capacidade Qual as capacidades desejadas para o browser
     * @param bool                     $visivel    Se irá abrir o navegador ou não. Não irá funcionar caso passe o
     *                                             $capacidade
     * @param string|null              $download   Diretório para download Não irá funcionar caso passe o $capacidade
     */
    public function __construct(
        string $url = 'http://localhost:4444/wd/hub',
        ?DesiredCapabilities $capacidade = null,
        bool $visivel = true,
        ?string $download = null
    ) {
        if ($capacidade == null) {
            $capacidade = DesiredCapabilities::chrome();
            $capacidade->setCapability('acceptSslCerts', false);

            $optionAtivo = !$visivel || !empty($download);
            if ($optionAtivo) {
                $options = new ChromeOptions();
            }
            if (!$visivel) {
                $options->addArguments(['--headless']);
            }
            if (!empty($download)) {
                $options->setExperimentalOption('prefs', ['download.default_directory' => $download]);
            }
            if ($optionAtivo) {
                $capacidade->setCapability(ChromeOptions::CAPABILITY_W3C, $options);
            }
        }
        try {
            $this->Driver = RemoteWebDriver::create($url, $capacidade);
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }
    }

    /**
     * @param          $error
     * @throws Excecao
     */
    private function erroPadrao($error): void
    {
        mensagemErro(
            titulo: 'Erro!',
            mensagem: $this->mensagemPadrao,
            error: $error
        );
    }

    /**
     * Retorna o webdrive para manipulação direta
     *
     * @return RemoteWebDriver
     */
    public function webDriver(): RemoteWebDriver
    {
        return $this->Driver;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA OS COOKIES
    |--------------------------------------------------------------------------
    |
    | Pega, seta ou deleta os cookies da página
    |
    */

    /**
     * Vai para a o link informado
     *
     * @param  string  $link Link para onde deseja navegar
     * @return self
     * @throws Excecao
     */
    public function requisicao(string $link): self
    {
        try {
            $this->Driver->get($link);
        } catch (Throwable $th) {
            $mensagem = $th->getMessage();
            if ($th instanceof WebDriverCurlException && str_contains($mensagem, 'Operation timed out after')) {
                mensagemErro(
                    titulo: 'Erro!',
                    mensagem: 'O sistema não conseguiu retorno em um tempo razoável, por favor, tente novamente.',
                    error: $th
                );
            } elseif ($th instanceof WebDriverCurlException) {
                $this->erroPadrao($th);
            }
        }
        return $this;
    }

    /**
     * Pega todos os cookies da página
     *
     * @param  null|string $indice Caso queira pegar um cookie específico, passar o name do cookie
     * @return array       Lista com os cookies ou 1 cookie caso passa um indice
     * @throws Excecao
     */
    public function pegarCookie(?string $indice = null): array
    {
        if (!empty($indice)) {
            try {
                return $this->Driver->manage()->getCookieNamed($indice);
            } catch (Throwable $th) {
                $this->erroPadrao($th);
            }
            return $this;
        }

        $Cookie = $this->Driver->manage()->getCookies();
        $lista = [];
        foreach ($Cookie as $r) {
            $lista = [
                'name'     => $r->getName(),
                'value'    => $r->getValue(),
                'expires'  => $r->getExpiry(),
                'path'     => $r->getPath(),
                'domain'   => $r->getDomain(),
                'secure'   => $r->isSecure(),
                'httpOnly' => $r->isHttpOnly(),
                'sameSite' => $r->getSameSite(),
            ];
        }
        return $lista;
    }

    /**
     * Seta o valor de cookie
     *
     * @param  string $nome  O nome para o cookie
     * @param  string $valor O valor do cookie
     * @return self
     */
    public function setarCookie(string $nome, string $valor): self
    {
        $this->Driver->manage()->addCookie(new Cookie($nome, $valor));
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA OS ELEMENTOS
    |--------------------------------------------------------------------------
    |
    | Pega os elementos para manipular pelo WebDriver
    |
    */

    /**
     * Deleta um ou todos os cookies
     *
     * @param  null|string $indice Caso deseja deletar um cookie específico
     *                             informa o indice
     * @return self
     */
    public function cookieDeletar(?string $indice = ''): self
    {
        if (!empty($indice)) {
            try {
                $this->Driver->manage()->deleteCookieNamed($indice);
            } catch (Throwable $th) {
                $this->erroPadrao($th);
            }
            return $this;
        }
        $this->Driver->manage()->deleteAllCookies();
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA OS ELEMENTOS
    |--------------------------------------------------------------------------
    |
    | Pega os elementos para manipular pelo WebDriver
    |
    */

    /**
     * Pega o primeiro elemento que for encontrado na página
     *
     * @param  string  $elemento Seletor CSS para pegar o elemento desejado
     * @throws Excecao
     * @return self
     */
    public function elemento(string $elemento): self
    {
        try {
            $this->ElementoAtual = $this->Driver->findElement(WebDriverBy::cssSelector($elemento));
        } catch (\Throwable $th) {
            $this->erroPadrao($th);
        }
        return $this;
    }

    /**
     * Procura por todos os elementos que existem na página
     *
     * @param  string   $elemento Seletor CSS para pegar o elemento desejado
     * @param  null|int $indice   Um número caso queira pegar apenas 1 elemento
     * @return self
     * @throws Excecao
     */
    public function todosElementos(string $elemento, ?int $indice = null): self
    {
        try {
            $elemento = $this->Driver->findElements(WebDriverBy::cssSelector($elemento));
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }
        if (is_numeric($indice)) {
            try {
                $elemento = $elemento[$indice];
            } catch (Throwable $th) {
                $this->erroPadrao($th);
            }
        }
        $this->ElementoAtual = $elemento;
        return $this;
    }

    /**
     * Pega um indice do elemento buscado pelo método elementoTodos
     *
     * @param  int     $indice Indice que deseja buscar
     * @return self
     * @throws Excecao
     */
    public function setarElemento(int $indice): self
    {
        try {
            $this->ElementoAtual = $this->ElementoAtual[$indice];
            return $this;
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }
    }

    /**
     * Retorna o objeto contento os elementos atuais
     */
    public function pegarElemento()
    {
        return $this->ElementoAtual;
    }

    /**
     * Limpa a lista de elementos atuais
     */
    public function limparElemento()
    {
        $this->ElementoAtual = [];
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATAR OS VALUES DO FORM
    |--------------------------------------------------------------------------
    | Pega ou seta um valor para campos de formulário como input
    | select, textarea, checkbox, radio, file e etc
    */

    /**
     * Seta um valor a um elemento de formulário
     *
     * @param  string      $valor    Valor que deseja se setado
     * @param  null|string $elemento Caso não queira pegar o elemento atual, passar seletor CSS para pegar
     *                               o elemento
     * @return self
     * @throws Excecao
     */
    public function setarValor(string $valor, ?string $elemento = null): self
    {
        if ($elemento) {
            $this->elemento($elemento);
        }
        $elemento = $this->pegarElementoInternamente(0);

        if (!$elemento instanceof RemoteWebElement) {
            $this->erroElemento();
        }
        $this->setarValorCorreto($elemento, $valor);
        return $this;
    }

    /**
     * Pega o primeiro elemento que for encontrado na página
     *
     * @param  string  $elemento Seletor CSS para pegar o elemento desejado
     * @return self
     * @throws Excecao
     */
    public function elemento(string $elemento): self
    {
        try {
            $this->ElementoAtual = $this->Driver->findElement(WebDriverBy::cssSelector($elemento));
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }
        return $this;
    }

    private function pegarElementoInternamente(): WebDriverElement|array
    {
        try {
            $elemento = $this->ElementoAtual;
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }
        return $elemento;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA OS GET DE CONTEÚDO
    |--------------------------------------------------------------------------
    |
    | Pega os valores do página como HTML, title, o texto dos elementos
    |
    */

    private function erroElemento()
    {
        mensagemErro(
            titulo: 'Erro!',
            mensagem: $this->mensagemPadrao,
            localhost: 'O elemento que você tentou acessar não existe.'
        );
    }

    private function setarValorCorreto(RemoteWebElement $elemento, string $valor)
    {
        $tag = $elemento->getTagName();
        if ($tag == 'input') {
            try {
                $type = $elemento->getAttribute('type');
            } catch (Throwable) {
                $type = '';
            }
        }

        if ($type == 'file' && !file_exists($valor)) {
            mensagemErro(
                titulo: 'Erro',
                mensagem: 'O arquivo enviado não existe.'
            );
        } elseif ($type == 'file') {
            $elemento->setFileDetector(new LocalFileDetector());
        } elseif ($tag == 'select') {
            $elemento = new WebDriverSelect($elemento);
            $elemento->selectByValue($valor);
            return;
        } elseif ($type == 'checkbox') {
            $elemento = new WebDriverCheckboxes($elemento);
            $elemento->selectByValue($valor);
            return;
        } elseif ($type == 'radio') {
            $elemento = new WebDriverRadios($elemento);
            $elemento->selectByValue($valor);
            return;
        } elseif ($tag == 'textarea') {
            $elemento->click();
        }
        if (empty($valor)) {
            $elemento->clear();
            return;
        }
        $elemento->sendKeys($valor);
    }

    /**
     * Simula um clique no primeiro elemento
     *
     * @return self
     * @throws Excecao
     */
    public function click()
    {
        $elemento = $this->pegarElementoInternamente();
        if (is_array($elemento) &&
            array_key_exists(0, $elemento) &&
            $elemento[0] instanceof RemoteWebElement
        ) {
            $elemento[0]->click();
            return $this;
        } elseif ($elemento instanceof RemoteWebElement) {
            $elemento->click();
            return $this;
        }
        $this->erroElemento();
    }

    /**
     * @return string|array Retonar uma string caso tenha passado um indice para
     *                      o elemento ou um array com a lista de valores
     * @throws Excecao
     */
    public function pegarValor(): string
    {
        $elemento = $this->pegarElementoInternamente();
        if ($elemento instanceof WebDriverElement) {
            return $elemento->getAttribute('value');
        } elseif (!is_array($elemento) || !$elemento) {
            mensagemErro(
                titulo: 'Erro!',
                mensagem: $this->mensagemPadrao,
                localhost: 'O elemento passado não é um atributo para pegar o valor.'
            );
        }
        $lista = [];
        foreach ($elemento as $item) {
            if ($elemento instanceof WebDriverElement) {
                try {
                    $lista[] = $item->getAttribute('value');
                } catch (Throwable $th) {
                    mensagemErro(
                        titulo: 'Erro!',
                        mensagem: $this->mensagemPadrao,
                        error: $th
                    );
                }
                continue;
            }
            mensagemErro(titulo: 'Erro!', mensagem: $this->mensagemPadrao);
        }
        return $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | MANUPULADORES
    |--------------------------------------------------------------------------
    |
    | Ações que manupulação a página como fazer esperar algo
    | mudar para uma nova janela que foi aberta, acessar um iframe e etc
    |
    */

    /**
     * Pegar um valor de atributo do elemento
     *
     * @param string $atributo Qual atributo deseja pegar
     *
     * @return string|array Retorna uma string caso tenha passado um indice ao elemento
     *                      ou um array para multiplos elementos
     * @throws Excecao
     */
    public function pegarAttr(string $atributo): string|array
    {
        $elemento = $this->pegarElementoInternamente();

        if ($elemento instanceof RemoteWebElement) {
            return $elemento->getAttribute($atributo);
        } elseif (!is_array($elemento)) {
            $this->erroElemento();
        }
        $lista = [];
        foreach ($elemento as $item) {
            if ($item instanceof RemoteWebElement) {
                $lista[] = $item->getAttribute($atributo);
                continue;
            }
            $this->erroElemento();
        }
        return $lista;
    }

    /**
     * Pega o HTML da página atual
     *
     * @return string Retorna HTML
     */
    public function pegarHtml(): string
    {
        return $this->Driver->getPageSource();
    }

    /**
     * Pega o título da página atual
     *
     * @return string Retorna o título da página atual
     */
    public function pegarTitle(): string
    {
        return $this->Driver->getTitle();
    }

    /**
     * Pega a texto do elemento selecionado
     *
     * @return string|array Retorna o valor do texto caso tenha definido o indice do elemento
     *                      ou retorna um array com os valores para multiplos elementos
     * @throws Excecao
     */
    public function pegarTexto(): string|array
    {
        $elemento = $this->pegarElementoInternamente();
        if (is_array($elemento) && empty($elemento)) {
            $this->erroElemento();
        } elseif ($elemento instanceof RemoteWebElement) {
            return $elemento->getText();
        }
        $lista = [];
        foreach ($elemento as $item) {
            if ($item instanceof RemoteWebElement) {
                $lista[] = $item->getText();
                continue;
            }
            $this->erroElemento();
        }
        return $lista;
    }

    /**
     * Pega a URL atual da página
     *
     * @return string Retorna a url atual
     */
    public function pegarUrl(): string
    {
        return $this->Driver->getCurrentURL();
    }

    /**
     * Faz o webdriver esperar alguma ação do navegador para continuar
     *
     * @param  int         $segundos Quantidade de segundos que deseja esperar
     * @param  null|string $condicao Condição para a espera podendendo ser: titulo, %titulo%, url, %url%, texto,
     *                               %texto%, visivel condições entre "%" indicam que o valor pode conter e não
     *                               precisar ser igual. condições texto, %texto% e visivel devem ter
     *                               obrigatoriamente o $elemento passado
     * @param  null|string $valor    Valor deseja para condição, obrigatório menos para a condição visivel
     * @param  null|string $elemento Seletor Css do elemento que deseja analisar
     * @return self
     * @throws Excecao
     */
    public function esperar(
        int $segundos = 0,
        ?string $condicao = null,
        ?string $valor = null,
        ?string $elemento = null
    ): self {
        if ($segundos > 0 && empty($condicao)) {
            $this->Driver->wait();
            return $this;
        } elseif (empty($condicao) || !in_array(
            $condicao,
            ['titulo', '%titulo%', 'url', '%url%', 'texto', '%texto%', 'visivel']
        )
        ) {
            mensagemErro(
                titulo: 'Erro!',
                mensagem: $this->mensagemPadrao,
                localhost: 'Você deve passar pelo menos uma condição de espera.'
            );
        }

        $until = null;
        if ($condicao == 'titulo') {
            $until = WebDriverExpectedCondition::titleIs($valor);
        } elseif ($condicao == '%titulo%') {
            $until = WebDriverExpectedCondition::titleContains($valor);
        } elseif ($condicao == 'url') {
            $until = WebDriverExpectedCondition::urlIs($valor);
        } elseif ($condicao == '%url%') {
            $until = WebDriverExpectedCondition::urlContains($valor);
        } elseif ($condicao == 'texto') {
            $until = WebDriverExpectedCondition::elementTextIs(WebDriverBy::cssSelector($elemento), $valor);
        } elseif ($condicao == '%texto%') {
            $until = WebDriverExpectedCondition::elementTextContains(WebDriverBy::cssSelector($elemento), $valor);
        } elseif ($condicao == 'visivel') {
            $until = WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector($elemento));
        }

        try {
            $this->Driver->wait(30)->until($until);
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA A JANELA
    |--------------------------------------------------------------------------
    */

    /**
     * Scroll a página para altura desejada
     *
     * @param  int  $altura Altura que deseja rolar o scroll, caso tenha um elemento setado, somarar ao eixo y do
     *                      elemento
     * @return self
     */
    public function scroll(int $altura = 0): self
    {
        $elemento = $this->ElementoAtual;
        if ($elemento instanceof WebDriverElement) {
            $altura += $this->ElementoAtual->getLocation()->getY();
        }

        $script = 'setTimeout(() => {window.scrollTo(0, ' . $altura . ');}, 300);';
        $this->Driver->executeScript($script);

        return $this;
    }

    /**
     * Acessa um iframe/frame que foi pegado pelo método elemento(s)
     *
     * @return self
     * @throws Excecao
     */
    public function iframe(): self
    {
        $elemento = $this->pegarElementoInternamente();
        if ((is_array($elemento) && (!array_key_exists(0, $elemento) || !$elemento[0] instanceof WebDriverElement)) ||
            (!is_array($elemento) && !$elemento instanceof WebDriverElement)
        ) {
            $this->erroElemento();
        }

        $elemento = is_array($elemento) ? $elemento[0] : $elemento;
        try {
            $this->Driver->switchTo()->frame($elemento);
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }
        return $this;
    }

    /**
     * Muda para outra aba aberta
     *
     * @param  int     $aba Número da aba da janela que quer comandar
     * @return self
     * @throws Excecao
     */
    public function aba($aba): self
    {
        try {
            $this->Driver->switchTo()->window($this->Driver->getWindowHandles()[$aba]);
        } catch (Throwable $th) {
            $this->erroPadrao($th);
        }
        return $this;
    }

    /**
     * Fecha o navegador
     */
    public function fechar(): void
    {
        $this->Driver->close();
    }

    /**
     * Sai do webdriver
     */
    public function sair(): void
    {
        $this->Driver->quit();
    }

    /**
     * Seta valor fixo para o tamanho  da janela
     *
     * @param  int  $largura Seta a largura da janela
     * @param  int  $altura  Seta a altura da janela
     * @return self
     */
    public function janelaTamanho(int $largura, int $altura): self
    {
        $this->Driver->manage()->window()->setSize(new WebDriverDimension($largura, $altura));
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA OS ALERTS
    |--------------------------------------------------------------------------
    |
    | Aceita, fecha, recusae pega o texto dos alerts do javascriot
    |
    */

    /**
     * Coloca a janela em tela cheia
     *
     * @return self
     */
    public function janelaTelaCheia(): self
    {
        $this->Driver->manage()->window()->fullscreen();
        return $this;
    }

    /**
     * Maxima a janela
     *
     * @return self
     */
    public function janelaMaximizar(): self
    {
        $this->Driver->manage()->window()->maximize();
        return $this;
    }

    /**
     * Minimiza a janela
     *
     * @return self
     */
    public function janelaMinimizar(): self
    {
        $this->Driver->manage()->window()->minimize();
        return $this;
    }

    /**
     * Coloca a janela em modo retrato
     *
     * @return self
     */
    public function janelaRetrato(): self
    {
        $this->Driver->manage()->window()->setScreenOrientation('PORTRAIT');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA OS MOUSES
    |--------------------------------------------------------------------------
    |
    | Manipula o mouse na tela
    |
    */

    /**
     * Coloca a janela em modo paisagem
     *
     * @return self
     */
    public function janelaPaisagem(): self
    {
        $this->Driver->manage()->window()->setScreenOrientation('LANDSCAPE');
        return $this;
    }

    /**
     * Aceita o alerta
     *
     * @return self
     */
    public function alertAceitar(): self
    {
        $this->Driver->switchTo()->alert()->accept();
        return $this;
    }

    /**
     * Recusa o alerta
     *
     * @return self
     */
    public function alertRecusar(): self
    {
        $this->Driver->switchTo()->alert()->dismiss();
        return $this;
    }

    /**
     * Pega o texto do alerta
     *
     * @return string Texto do alerta
     */
    public function alertTexto(): string
    {
        return $this->Driver->switchTo()->alert()->getText();
    }

    /**
     * Passa um texto para o alerta
     *
     * @param  string $valor Valor que deseja passar
     * @return Selg
     */
    public function alertValor(string $valor): self
    {
        $this->Driver->switchTo()->alert()->sendKeys($valor);
        return $this;
    }

    /**
     * Pegar o mouse e faz a ação de mouse down
     *
     * @return self
     */
    public function mouseApertaClique(): self
    {
        $elemento = $this->ElementoAtual;
        $this->Driver->getMouse()->mouseDown($elemento->getCoordinates());
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | TRATA AÇÕES NA TELA
    |--------------------------------------------------------------------------
    |
    | Ação na tela como clique e enter
    |
    */

    /**
     * Pega o mouse e faz a ação de mouse up
     *
     * @param  bool $elemento Se vai usar uma cordenada de um elemento ou não
     * @return self
     */
    public function mouseSoltarClique(bool $elemento = true): self
    {
        $elemento = $elemento ? $this->ElementoAtual->getCoordinates() : null;
        $this->Driver->getMouse()->mouseUp($elemento);
        return $this;
    }

    /**
     * Pega o mouse e faz a ação de mouse up
     *
     * @param  null|int $x        Posição X onde o mouse vai parar
     * @param  null|int $y        Posição y onde o mouse vai parar
     * @param  bool     $elemento Se vai usar uma cordenada de um elemento ou não
     * @return self
     */
    public function mouseMover(?int $x = null, ?int $y = null, bool $elemento = true): self
    {
        $elemento = $elemento ? $this->ElementoAtual->getCoordinates() : null;
        $this->Driver->getMouse()->mouseMove($elemento, $x, $y);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS DA CLASSE
    |--------------------------------------------------------------------------
    */

    public function mouseCliqueDuplo()
    {
        $this->Driver->getMouse()->doubleClick($this->ElementoAtual->getCoordinates());
        return $this;
    }

    public function mouseClique()
    {
        $this->Driver->getMouse()->click($this->ElementoAtual->getCoordinates());
        return $this;
    }

    public function mouseCliqueDireito()
    {
        $this->Driver->getMouse()->contextClick($this->ElementoAtual->getCoordinates());
        return $this;
    }

    /**
     * Simula um enter no primeiro elemento
     *
     * @return self
     * @throws Excecao
     */
    public function enter(): self
    {
        $elemento = $this->pegarElementoInternamente();
        $elemento->sendKeys(WebDriverKeys::ENTER);
        return $this;
    }

    /**
     * Simula um clique no primeiro elemento
     *
     * @throws Excecao
     * @return self
     */
    public function click()
    {
        $elemento = $this->pegarElementoInternamente();
        if (
            is_array($elemento) &&
            array_key_exists(0, $elemento) &&
            $elemento[0] instanceof RemoteWebElement
        ) {
            $elemento[0]->click();
            return $this;
        } elseif ($elemento instanceof RemoteWebElement) {
            $elemento->click();
            return $this;
        }
        $this->erroElemento();
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS DA CLASSE
    |--------------------------------------------------------------------------
    */
    private function pegarElementoInternamente(): WebDriverElement | array
    {
        try {
            $elemento = $this->ElementoAtual;
        } catch (\Throwable $th) {
            $this->erroPadrao($th);
        }
        return $elemento;
    }

    private function erroPadrao($error)
    {
        mensagemErro(
            titulo: 'Erro!',
            mensagem: $this->mensagemPadrao,
            error: $error
        );
    }

    private function erroElemento()
    {
        mensagemErro(
            titulo: 'Erro!',
            mensagem: $this->mensagemPadrao,
            localhost: 'O elemento que você tentou acessar não existe.'
        );
    }

    private function setarValorCorreto(RemoteWebElement $elemento, string $valor)
    {
        $tag = $elemento->getTagName();
        if ($tag == 'input') {
            try {
                $type = $elemento->getAttribute('type');
            } catch (\Throwable) {
                $type = '';
            }
        }

        if ($type == 'file' && !file_exists($valor)) {
            mensagemErro(
                titulo: 'Erro',
                mensagem: 'O arquivo enviado não existe.'
            );
        } elseif ($type == 'file') {
            $elemento->setFileDetector(new LocalFileDetector());
        } elseif ($tag == 'select') {
            $elemento = new WebDriverSelect($elemento);
            $elemento->selectByValue($valor);
            return;
        } elseif ($type == 'checkbox') {
            $elemento = new WebDriverCheckboxes($elemento);
            $elemento->selectByValue($valor);
            return;
        } elseif ($type == 'radio') {
            $elemento = new WebDriverRadios($elemento);
            $elemento->selectByValue($valor);
            return;
        } elseif ($tag == 'textarea') {
            $elemento->click();
        }
        if (empty($valor)) {
            $elemento->clear();
            return;
        }
        $elemento->sendKeys($valor);
    }
}
