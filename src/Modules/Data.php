<?php

namespace Modules;

use Modules\Trait\ValidarTrait;
use Modules\Trait\ValorRealTrait;

final class Data implements ModuleInterface
{
    use ValidarTrait;
    use ValorRealTrait;

    private ?string $tipo = null;
    private ?string $date = null;

    public function __toString()
    {
        return $this->data;
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->date;
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->date();
    }

    // doc
    /**
     * Modulo para Data
     *
     * @param null|string $data Data para o modulo
     */
    public function __construct(
        private ?string $data = null
    ) {
        $this->valor_real = $data;
        $eData = is_string($this->data) && preg_match(
            "/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/",
            $data
        );
        $eDate = is_string($this->data) && preg_match(
            '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',
            $data
        );

        if (empty($data)) {
            $this->data = '';
            $this->date = '';
            $this->vazio = true;
            $this->valido = false;
            return;
        } elseif (!$eDate && !$eData) {
            $this->data = '';
            $this->date = '';
            $this->valido = false;
            return;
        }

        if ($eData) {
            list($diaTemp, $mesTemp, $anoTemp) = explode('/', $data);
        } elseif ($eDate) {
            list($anoTemp, $mesTemp, $diaTemp) = explode('-', $data);
        }
        if (!checkdate((int)$mesTemp, (int)$diaTemp, (int)$anoTemp)) {
            $this->valido = false;
            $this->data = '';
            $this->date = '';
            return;
        }

        $formato = (new \DateTime(str_replace('/', '-', $data)));

        $this->tipo = $eData ? 'data' : 'date';
        $this->data = $formato->format('d/m/Y');
        $this->date = $formato->format('Y-m-d');
    }

    // doc
    /**
     * Valida se a data está no formado Y-m-d
     *
     * @return bool true para verdadeiro ou false para falso
     */
    public function eDate(): bool
    {
        return $this->tipo == 'date';
    }

    // doc
    /**
     * Valida se a data está no formado d/m/Y
     *
     * @return bool true para verdadeiro ou false para falso
     */
    public function eData(): bool
    {
        return $this->tipo == 'data';
    }

    // doc
    /**
     * Pega a data no formato d/m/Y
     *
     * @return string Data no formato d/m/Y
     */
    public function data(): string
    {
        return $this->data;
    }

    // doc
    /**
     * Pega a data no formato Y-m-d
     *
     * @return string Data no formato Y-m-d
     */
    public function date()
    {
        return $this->date;
    }
}
