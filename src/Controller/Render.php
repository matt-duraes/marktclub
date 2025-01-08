<?php

namespace Controller;

use Erro\Excecao;
use Http\Response;
use Controller\Trait\CriarHtmlTrait;
use Controller\Trait\ArquivoScriptTrait;
use Controller\Trait\SecurityPolicyTrait;
use Controller\Trait\MontarNomeArquivoTrait;

final class Render
{
    use MontarNomeArquivoTrait;
    use ArquivoScriptTrait;
    use CriarHtmlTrait;
    use SecurityPolicyTrait;

    private string $html = '';
    private string $diretorioView = '';
    private string $viewPath = '';
    private string $viewNome = '';
    private string $public;
    private string $securityPolicy = '';
    private string $cache = '';

    public function __construct(
        private string $arquivo,
        private ?array $var = [],
        private ?array $header = [],
        private ?string $css = '',
        private ?string $js = '',
        private bool $cacheHeader = false
    ) {
        $this->setarPropriedade();
        $this->montarNomeArquivo();
        $this->verificarViewExiste();
        $css = $this->setarArquivoScript('css', $css);
        $js = $this->setarArquivoScript('js', $js);
        $this->criarHTML($css, $js, $var, $header);
        $this->criarSecurityPolicy();
    }

    private function setarPropriedade()
    {
        $this->diretorioView = ROOT . '/files/build/views/';
        $this->cache = defined('CACHE_VERSAO') && !empty(CACHE_VERSAO) ? '?c=' . CACHE_VERSAO : '';
        $this->public = env('PUBLIC', 'public');
        $this->securityPolicy = env('SECURITY_POLICY', '');
    }

    private function verificarViewExiste()
    {
        if (!file_exists($this->viewPath)) {
            throw new Excecao(status: 404);
        }
    }

    public function response(): Response
    {
        $tempoVida = env('CACHE_VIDA', '');
        if ($this->cacheHeader && !empty($tempoVida)) {
            $this->header[] = ['Expires' => gmdate('D, d M Y H:i:s', time() + $tempoVida) . ' GMT'];
            $this->header[] = ['Cache-Control' => 'max-age=' . $tempoVida];
            $this->header[] = ['Pragma' => 'cache'];
        } else {
            $this->header[] = ['Expires' => '0'];
            $this->header[] = ['Cache-Control' => 'no-cache, no-store, must-revalidate'];
            $this->header[] = ['Pragma' => 'no-cache'];
        }
        return new Response($this->html, header: $this->header);
    }
}
