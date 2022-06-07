<?php

namespace ORM\Group;

trait GroupTrait
{
    /**
     * @param String        $campo      Campo que deverá agrupar
     */
    protected function group(string $campo)
    {
        if (!empty($campo)) {
            $this->_group = "`{$this->_tabelaAtual}`.`{$campo}`";
        }
        return $this;
    }
    protected function groupTexto(string $group)
    {
        if (!empty($group)) {
            $this->_group = $group;
        }
        return $this;
    }
}
