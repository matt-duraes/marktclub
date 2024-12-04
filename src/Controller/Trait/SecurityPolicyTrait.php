<?php

namespace Controller\Trait;

trait SecurityPolicyTrait
{
    private string $nonce;
    private bool $nonceCss = false;
    private bool $nonceJs = false;
    private bool $nonceConnect = false;

    private function criarSecurityPolicy()
    {
        if (empty($this->securityPolicy)) {
            return;
        }
        $this->nonce = uuid();
        $this->fazerReplaceScript(
            '/\<style ?\/?\>/',
            '<style nonce="' . $this->nonce . '">',
            'nonceCss'
        );
        $this->fazerReplaceScript(
            '/\<link rel\=\"stylesheet\"/',
            '<link nonce="' . $this->nonce . '" rel="stylesheet"',
            'nonceCss'
        );
        $this->fazerReplaceScript(
            '/\<link rel\=\"preconnect\"/',
            '<link nonce="' . $this->nonce . '" rel="preconnect"',
            'nonceConnect'
        );
        $this->fazerReplaceScript(
            '/\<script /',
            '<script nonce="' . $this->nonce . '" ',
            'nonceJs'
        );
        $this->fazerReplaceScript(
            '/\<script>/',
            '<script nonce="' . $this->nonce . '">',
            'nonceJs'
        );
        $this->criarHeaderParaSecurityPolicy();
    }

    private function fazerReplaceScript($regex, $replace, $parametro)
    {
        if (!preg_match($regex, $this->html)) {
            return;
        }
        $this->html = preg_replace($regex, $replace, $this->html);
        $this->$parametro = true;
    }

    private function criarHeaderParaSecurityPolicy()
    {
        $css = $this->nonceCss ? "'nonce-" . $this->nonce . "'" : '';
        $js = $this->nonceJs ? "'nonce-" . $this->nonce . "'" : '';
        $connect = $this->nonceConnect ? "'nonce-" . $this->nonce . "'" : '';
        $csp = str_replace(
            ['{{NONCECSS}}', '{{NONCEJS}}', '{{NONCECONNECT}}'],
            [$css, $js, $connect],
            $this->securityPolicy
        );
        $this->header['Content-Security-Policy'] = $csp;
        $this->header['X-Content-Security-Policy'] = $csp;
    }
}
