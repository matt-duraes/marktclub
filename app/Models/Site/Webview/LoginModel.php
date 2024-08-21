<?php

namespace App\Models\Site\Webview;

final class LoginModel
{
    public string $link;

    public function __construct(
        private LocalInterface $Local
    ) {
        $this->link = $Local->link();
    }
}
