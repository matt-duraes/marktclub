<?php

namespace App\Helpers;

use Erro\Erro;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Translate\V2\TranslateClient;

class GoogleTradutorHelper
{
    private ?TranslateClient $translateClient = null;

    /**
     * @throws GoogleException
     * @throws Erro
     */
    public function __construct()
    {
        $envsGoogle = [
            'GOOGLE_TRANSLATE_KEY' => env('GOOGLE_TRANSLATE_KEY')
        ];

        foreach ($envsGoogle as $index => $value) {
            if (empty($value)) {
                throw new Erro(
                    "Variável de ambiente $index não foi seta ou está vazia",
                    'Variáveis de Ambiente',
                    "A variável de ambiente $index deve ser preenchida corretamente"
                );
            } elseif (!is_string($value)) {
                throw new Erro(
                    "Esperavamos um valor do tipo STRING na variável de ambiente $index",
                    'Tipagem da variável de ambiente',
                    "A variável de ambiente $index deve ser do tipo STRING"
                );
            }
        }

        if (!($this->translateClient instanceof TranslateClient)) {
            $this->translateClient = new TranslateClient([
                'key' => env('GOOGLE_TRANSLATE_KEY')
            ]);
        }
    }

    /**
     * @param string $texto
     * @param string $alvo
     *
     * @return string
     * @throws ServiceException
     */
    public function traduzir(string $texto, string $alvo = 'en'): string
    {
        $traducao = $this->translateClient->translate($texto, [
            'target' => $alvo
        ]);
        return $traducao['text'];
    }
}
