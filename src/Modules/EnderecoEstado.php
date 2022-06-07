<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class EnderecoEstado implements ModuleInterface
{

    use ValidarTrait;
    public function __toString()
    {
        return $this->estado;
    }

    // doc
    /**
     * Modulo para Estado
     *
     * @param null|string $estado Estado para o modulo
     */
    public function __construct(
        private ?string $estado
    ) {
        if (empty($this->estado)) {
            $this->vazio = true;
            $this->valido = false;
            $this->estado = '';
            return;
        } else if (!$this->validarEstado()) {
            $this->valido = false;
            $this->estado = '';
            return;
        }

        $this->estado = mb_strtoupper($this->estado, 'UTF-8');
    }

    // doc
    /**
     * Pega o Estado do modulo
     *
     * @return string Retorna o estado
     */
    public function estado(): string
    {
        return $this->estado;
    }

    private function validarEstado(): bool
    {
        $estado = mb_strtoupper($this->estado, 'UTF-8');
        $lista = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE',
            'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];
        return in_array($estado, $lista);
    }
}
