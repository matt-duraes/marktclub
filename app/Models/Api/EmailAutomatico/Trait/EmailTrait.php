<?php

namespace App\Models\Api\EmailAutomatico\Trait;

trait EmailTrait
{
    private function pegarEmail(array $listaEmail)
    {
        foreach ($listaEmail as $email) {
            if (empty($email)) {
                continue;
            } else if (
                str_ends_with($email, '@gmail.com') ||
                str_ends_with($email, '@outlook.com') ||
                str_ends_with($email, '@outlook.com.br') ||
                str_ends_with($email, '@hotmail.com') ||
                str_ends_with($email, '@hotmail.com.br') ||
                preg_match("/\@[a-zA-Z0-9\_]+\.com(\.br){0,1}/", $email)
            ) {
                return $email;
            }
        }
        foreach ($listaEmail as $email) {
            if (!empty($email)) {
                return $email;
            }
        }
        return '';
    }
}
