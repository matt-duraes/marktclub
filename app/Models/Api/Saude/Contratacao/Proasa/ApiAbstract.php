<?php

namespace app\Models\Api\Saude\Contratacao\Proasa;

use Helpers\CurlHelper;

abstract class ApiAbstract extends CurlHelper {
    private string $token;

    public function __construct()
    {
        parent::__construct(url: $this->pegarLinkApi());
        $this->token = env('PROASA_RD_TOKEN', '');
    }

    private function pegarLinkApi(): string
    {
        $link = env('PROASA_RD_LINK', '');
        if(empty($link)) {
            return '';
        }
        return preg_replace('/\/+$/', '', $link );
    }

    protected function headerAccept(): array
    {
        return [
            'accept' => 'application/json'
        ];
    }

    protected function uri(string $uri)
    {
        return '/' . preg_replace('/^\/+/', '', $uri) . '?token=' . $this->token;
    }
}
