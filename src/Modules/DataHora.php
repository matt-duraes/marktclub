<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class DataHora implements ModuleInterface
{
    use ValidarTrait;

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
     * Modulo para DataHora
     *
     * @param null|string $data Data para o modulo
     */
    public function __construct(
        private ?string $data
    ) {
        $eData = is_string($this->data) && preg_match(
            // @codingStandardsIgnoreStart
            "/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",
            // @codingStandardsIgnoreEnd
            $data
        );
        $eDate = is_string($this->data) && preg_match(
            // @codingStandardsIgnoreStart
            "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",
            // @codingStandardsIgnoreEnd
            $data
        );
        if (empty($data)) {
            $this->vazio = true;
            $this->valido = false;
            $this->data = '';
            $this->date = '';
            return;
        } elseif (!$eDate && !$eData) {
            $this->valido = false;
            $this->data = '';
            $this->date = '';
            return;
        }

        if ($eData) {
            list($diaTemp, $mesTemp, $anoTemp) = explode('/', explode(' ', $data)[0]);
        } elseif ($eDate) {
            list($anoTemp, $mesTemp, $diaTemp) = explode('-', explode(' ', $data)[0]);
        }
        if (!checkdate((int)$mesTemp, (int)$diaTemp, (int)$anoTemp)) {
            $this->valido = false;
            $this->data = '';
            $this->date = '';
            return;
        }

        $formato = new \DateTime(str_replace('/', '-', $data));
        $this->tipo = $eData ? 'data' : 'date';
        $this->data = $formato->format('d/m/Y H:i:s');
        $this->date = $formato->format('Y-m-d H:i:s');
    }

    // doc
    /**
     * Valida se a data está no formado Y-m-d
     *
     * @return bool true para verdadeiro ou false para falso
     */
    public function eDate()
    {
        return $this->tipo == 'date';
    }

    // doc
    /**
     * Valida se a data está no formado d/m/Y
     *
     * @return bool true para verdadeiro ou false para falso
     */
    public function eData()
    {
        return $this->tipo == 'data';
    }

    // doc
    /**
     * Pega a data no formato d/m/Y
     *
     * @return string Data no formato d/m/Y
     */
    public function data()
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
