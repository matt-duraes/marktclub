<?php

namespace Controller\Trait;

trait ArquivoScriptTrait
{
    private function setarArquivoScript($tipo, $arquivo)
    {
        $nome = str_replace('/', '_', $this->viewNome);
        if (!empty($arquivo)) {
            return LINK_PADRAO . '/' . $tipo . '/' . preg_replace('/(\.css|\.js)$/', '', $arquivo) . '.' . $tipo . $this->cache;
        } elseif (file_exists(ROOT . '/' . $this->public . '/' . $tipo . '/' . $nome . '.' . $tipo)) {
            return LINK_PADRAO . '/' . $tipo . '/' . $nome . '.' . $tipo . $this->cache;
        }
        return $this->verificarSeExisteScriptDoTemplate($tipo);
    }

    public function verificarSeExisteScriptDoTemplate($tipo): string
    {
        $view = ROOT . '/views/pages/' . str_replace('.', '/', $this->arquivo) . '/index.view';
        if (!file_exists($view)) {
            return '';
        }
        $conteudo = file_get_contents($view);
        preg_match("/\@template\ ?\(?(\'|\")([a-zA-Z0-9\_\-\.\/]+)/", $conteudo, $template);
        if (!array_key_exists(2, $template)) {
            return '';
        }
        $template = $template[2];
        $target = $tipo == 'js' ?
            ROOT . '/views/templates/' . $template . '/' . $tipo . '/all.js' :
            ROOT . '/views/templates/' . $template . '/' . $tipo . '/layout.styl';

        if (!file_exists($target)) {
            return '';
        }
        if ($tipo == 'js') {
            return LINK_PADRAO . '/js/templates_' . $template . '.js' . $this->cache;
        }
        return LINK_PADRAO . '/css/templates_' . $template . '.css' . $this->cache;
    }
}
