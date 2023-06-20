<?php

final class SalvarRequest
{
    public function __construct(
        string $token,
        string $metodo,
        string $uri,
        array $parametro,
        array $body,
        private array $header,
        array $json
    ) {
    }
}
