<?php

namespace ResourcesSite\Componente;

final class Link {
    public string $link = '';
    public function __toString()
    {
        return $this->link;
    }

    public function __construct(
        ?string $link = null
    )
    {
        if(empty($link)) {
            return;
        }
        $this->link = str_replace([
            'https://{{LINK}}',
            'http://{{LINK}}',
            'https://clube.youhuul.com.br',
            'http://clube.youhuul.com.br',
            'https://clube.marktclub.com.br',
            'http://clube.marktclub.com.br',
        ], LINK, $link);

        $this->fazerReplace();
        $this->limparQuery();
        $this->removerQueryVazia();
    }

    private function removerQueryVazia()
    {
        $link = $this->link;
        if(!str_contains($link, '?')) {
            return;
        }
        $url = parse_url($link);
        if(empty($url['query'] ?? '')) {
            return;
        }
        $explode = explode('&', $url['query']);
        $query = [];
        foreach($explode as $val) {
            if(str_ends_with($val, '=')) {
                continue;
            }
            $query[] = $val;
        }

        $novaUrl = 'https://' . $url['host'];
        if (array_key_exists('port', $url) && !empty($url['port'])) {
            $novaUrl .= ':' . $url['port'];
        }
        if (array_key_exists('path', $url) && !empty($url['path'])) {
            $novaUrl .= $url['path'];
        }
        if (!empty($query)) {
            $novaUrl .= '?' . implode('&', $query);
        }

        $this->link = $novaUrl;
    }

    private function limparQuery()
    {
        if(!str_contains($this->link, '{{')) {
            return;
        }
        $this->link = preg_replace('/(\{\{[A-Z0-9_]+\}\})/', '', $this->link);
    }

    private function fazerReplace()
    {
        if(!str_contains($this->link, '{{')) {
            return;
        }
        $replace = $this->montarReplace();
        $this->link = str_replace(array_keys($replace), array_values($replace), $this->link);
    }

    private function montarReplace()
    {
        $retorno = [];
        foreach($_GET as $ind => $val) {
            $retorno['{{' . strCaixaAlta($ind) . '}}'] = $val;
        }
        return $retorno;
    }
}
