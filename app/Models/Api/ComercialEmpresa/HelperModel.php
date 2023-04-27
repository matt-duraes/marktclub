<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\ORM;

final class HelperModel extends ORM
{
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    public function mudarUuidParaId(array $uuid): array
    {
        $lista = $this->campo(['id'])->where(['cod', 'in', $uuid])->read();
        $id = [];
        foreach ($lista as $r) {
            $id[] = $r->id;
        }
        return $id;
    }
    public function mudarIdParaUuid(array $id): array
    {
        $lista = $this->campo(['cod'])->where(['cod', 'in', $id])->read();
        $uuid = [];
        foreach ($lista as $r) {
            $uuid[] = $r->uuid;
        }
        return $uuid;
    }
}
