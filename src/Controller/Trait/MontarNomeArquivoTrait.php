<?php

namespace Controller\Trait;

trait MontarNomeArquivoTrait
{
    private function montarNomeArquivo()
    {
        $controller = $this->listarController();
        $primeroPathView = $this->pegarPrimeiroPathView();
        $this->setarControllerNoArquivo($controller, $primeroPathView);
        $this->converterArquivoParaPathView();
    }

    private function listarController()
    {
        $retorno = [];
        foreach (array_diff(scandir(ROOT . '/app/Controllers'), ['.', '..']) as $controller) {
            $retorno[] = mb_strtolower($controller, 'UTF-8');
        }
        return $retorno;
    }

    private function pegarPrimeiroPathView()
    {
        if (!defined('ROUTE_DIRETORIO')) {
            return '';
        }
        return explode('.', $this->arquivo)[0] ?? '';
    }

    private function setarControllerNoArquivo($controller, $primeiroPathView)
    {
        if (in_array($primeiroPathView, $controller)) {
            return;
        }
        $padrao = mb_strtolower(ROUTE_DIRETORIO, 'UTF-8');
        $this->arquivo = $padrao . '.' . $this->arquivo;
    }

    private function converterArquivoParaPathView()
    {
        $this->viewNome = preg_replace('/\.php$/', '', str_replace(['.', '/'], '_', $this->arquivo));
        $this->viewPath = $this->diretorioView . $this->viewNome . '.php';
    }
}
