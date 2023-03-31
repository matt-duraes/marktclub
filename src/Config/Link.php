<?php

namespace System\Config;

final class Link
{
    /**
     * @var string
     */
    private string $diretorio = '';

    public function __construct()
    {
        $link = $this->linkSistema();

        $linkSite = env('LINK_SITE', '');
        $linkPadrao = env('LINK_PADRAO', '');
        $linkPainel = env('LINK_PAINEL', '');
        $linkApi = env('LINK_API', '');
        $linkArquivo = env('LINK_ARQUIVO', '');
        $linkArquivoPublico = env('LINK_ARQUIVO_PUBLICO', '');
        $linkArquivoPrivado = env('LINK_ARQUIVO_PRIVADO', '');
        $linkLocal = env('LINK_LOCAL', '');

        define('LINK', $link);
        define('LINK_PADRAO', $this->montarLinkPadrao($link, $linkPadrao));
        define('LINK_SITE', $this->montarLinkParaDefine($link, $linkSite));
        define('LINK_PAINEL', $this->montarLinkParaDefine($link, $linkPainel));
        define('LINK_API', $this->montarLinkParaDefine($link, $linkApi));
        define('LINK_ARQUIVO', $this->montarLinkParaDefine($link, $linkArquivo));
        define('LINK_ARQUIVO_PUBLICO', $this->montarLinkParaDefine($link, $linkArquivoPublico));
        define('LINK_ARQUIVO_PRIVADO', $this->montarLinkParaDefine($link, $linkArquivoPrivado));
        define('LINK_LOCAL', $this->montarLinkParaDefine($link, $linkLocal));
        define('URI', $this->pegarUri());
    }

    /**
     * @return string
     */
    private function linkSistema(): string
    {
        $link = env('LINK', '');
        if (!empty($link)) {
            return str_replace('/{{ROTA}}', $this->rota(), $link);
        }
        $http = $this->http();
        $host = $this->host();
        $diretorio = $this->diretorio();
        $nivel = $this->nivel();
        $rota = $this->rota();
        return $http . $host . $diretorio . $nivel . $rota;
    }

    /**
     * @return string
     */
    private function rota(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (!empty($this->diretorio)) {
            $expressao = '/^' . str_replace('/', '\/', $this->diretorio) . '/';
            $uri = preg_replace($expressao, '', $uri);
        }

        if (substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }

        if (empty($uri)) {
            return '';
        }

        $uri = explode('/', $uri)[0];
        $lista = array_diff(scandir(__DIR__ . '/../../app/Controllers'), ['.', '..']);
        if (in_array(ucfirst(mb_strtolower($uri, 'UTF-8')), $lista)) {
            return '/' . $uri;
        }
        return '';
    }

    /**
     * @return string
     */
    private function http(): string
    {
        $linkProtocolo = env('LINK_PROTOCOLO', '');
        if (!empty($linkProtocolo) && in_array($linkProtocolo, ['http://', 'https://'])) {
            return $linkProtocolo;
        } elseif (
            (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on') ||
            (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        ) {
            return 'https://';
        }
        return 'http://';
    }

    /**
     * @return string
     */
    private function host(): string
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * @return string
     */
    private function diretorio(): string
    {
        if (!isset($_SERVER['SCRIPT_NAME'])) {
            return '';
        }
        $this->diretorio = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        return $this->diretorio;
    }

    /**
     * @return string
     */
    private function nivel(): string
    {
        if (!preg_match("/\/?" . DIRETORIO_VIEW . "\/?$/", $_SERVER['DOCUMENT_ROOT']) && empty($this->diretorio)) {
            return '/' . DIRETORIO_VIEW;
        }
        return '';
    }

    /**
     * @param  string  $linkGeral
     * @param  string  $link
     * @return array|string|string[]|null
     */
    private function montarLinkPadrao(string $linkGeral, string $link): array|string|null
    {
        if ($link == '{{LINK}}') {
            return preg_replace('/\/[a-zA-Z0\-\_]+\/{0,1}$/', '', $linkGeral);
        }
        return str_replace(
            '{{LINK}}',
            $linkGeral,
            preg_replace(
                '/\/$/',
                '',
                $link
            )
        );
    }

    /**
     * @param  string  $linkGeral
     * @param  string  $link
     * @return array|string|string[]|null
     */
    private function montarLinkParaDefine(string $linkGeral, string $link): array|string|null
    {
        return str_replace(
            ['{{LINK}}', '{{LINK_PADRAO}}'],
            [$linkGeral, LINK_PADRAO],
            preg_replace(
                '/\/$/',
                '',
                $link
            )
        );
    }

    /**
     * @return string
     */
    private function pegarUri(): string
    {
        $rota = preg_replace('/^\//', '', $this->rota());
        $uri = preg_replace('/^\/?' . $rota . '\/?/', '', $_SERVER['REQUEST_URI']);
        return !empty($uri) ? '/' . $uri : '';
    }
}
