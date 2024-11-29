<?php

namespace System\Html\Postman\Models;

use Helpers\CryptHelper;
use System\Html\Postman\Models\Trait\CurlTrait;
use System\Html\Postman\Models\Trait\CryptTrait;
use System\Html\Postman\Models\Interface\TokenInterface;
use System\Html\Postman\Models\Trait\TokenCredentialEntityTrait;

abstract class TokenPadrao implements TokenInterface
{
    use CurlTrait;
    use CryptTrait;
    use TokenCredentialEntityTrait;

    public string $select;
    protected array $requisicao;
    private CryptHelper $Crypt;

    public function __construct(
        public string $indice,
        public string $titulo,
        private string $scope
    ) {
        $this->link = env('POSTMAN_API_LINK', '');
        $this->setarCrypt();
        $this->select($indice, $titulo);
    }

    private function select(string $indice, string $titulo): void
    {
        $this->indice = $indice;
        $this->titulo = $titulo;
        $this->select = '<option value="' . $indice . '">' . $titulo . '</option>';
    }

    protected function headerAuthorization(array $header = [])
    {
        try {
            $token = $this->gerarTokenPadrao($this->scope);
            if (empty($token)) {
                return [];
            }
            $header['Authorization'] = 'Bearer ' . $token;
            return $header;
        } catch (\Throwable) {
            return $header;
        }
    }

    protected function encode($dado)
    {
        return $this->Crypt->encode($dado);
    }

    abstract public function retornarToken(): string;
}
