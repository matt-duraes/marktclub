<?php

namespace ORM\Group;

trait GroupTrait
{
    /**
     * @param   string      $campo      Campo que deverá agrupar
     * @param   null|array  $replace    Array para trocar os valores do campo, caso não seja passado, pega a propriedade _replace, passar [] para não validar
     */
    protected function group(string $campo, ?array $replace = null)
    {
        if (empty($campo)) {
            $this;
        }

        $replace = is_array($replace) ? array_flip($replace) : array_flip($this->ormReplace);
        if ($replace && array_key_exists($campo, $replace)) {
            $campo = $replace[$campo];
        }

        $this->ormGroup = "`{$this->ormTabelaAtual}`.`{$campo}`";
        return $this;
    }
    protected function groupTexto(string $group)
    {
        if (!empty($group)) {
            $this->ormGroup = $group;
        }
        return $this;
    }
}
