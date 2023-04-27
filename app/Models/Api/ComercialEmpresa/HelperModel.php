<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\ORM;

final class HelperModel extends ORM
{
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    public function mudarListaUuidParaId(array $uuid): array
    {
        $lista = $this->campo(['id'])->where(['cod', 'in', $uuid])->read();
        $id = [];
        foreach ($lista as $r) {
            $id[] = $r->id;
        }
        return $id;
    }
    public function mudarListaIdParaUuid(array $id): array
    {
        $lista = $this->campo(['cod'])->where(['id', 'in', $id])->read();
        $uuid = [];
        foreach ($lista as $r) {
            $uuid[] = $r->cod;
        }
        return $uuid;
    }

    public function pegarIdPeloUuid(string $uuid)
    {
        return $this->campo(['id'])->where(['cod', $uuid])->primeiro(campo: 'id');
    }
}
