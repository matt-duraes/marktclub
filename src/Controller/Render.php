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
    private string $cache;
    private string $public;
    private string $securityPolicy = '';

    public function __construct(
        private string $arquivo,
        private ?array $var = [],
        private ?array $header = [],
        private ?string $css = '',
        private ?string $js = ''
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
        $this->cache = defined('CACHE') && !empty(CACHE) ? '?cache=' . CACHE : '';
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
        return new Response($this->html, header: $this->header);
    }
}
