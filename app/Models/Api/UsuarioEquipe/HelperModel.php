<?php

namespace App\Models\Api\UsuarioEquipe;

use ORM\ORM;

final class HelperModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_EQUIPE;
    protected array $ormReplace = [
        'id_admin_empresa' => 'id_comercial_empresa'
    ];

    public function pegarIdPeloUuid(?string $uuid): int
    {
        if (empty($uuid)) {
            return 0;
        }
        return $this->campo(['id'])->where(['uuid', $uuid])->primeiro(campo: 'id', padrao: 0);
    }

    public function pegarUuidPeloId(?int $id): string
    {
        if (empty($id)) {
                return '';
        }
        return $this->campo(['uuid'])->where(['id', $id])->primeiro(campo: 'uuid', padrao: '');
    }

    public function pegarIdDaEmpresaPeloUuid(?string $uuid): int
    {
        if (empty($uuid)) {
            return 0;
        }
        return $this
            ->campo(['id_comercial_empresa'])
            ->where(['uuid', $uuid])
            ->primeiro(campo: 'id_comercial_empresa', padrao: 0);
    }
    public function pegarPermissaoPeloId(?int $id): array
    {
        if (empty($id)) {
            return [];
        }
        $permissao = $this->campo(['permissao'])->where(['id', $id])->primeiro(campo: 'permissao', padrao: '');
        if (empty($permissao)) {
            return [];
        }
        return jsonDecode($permissao, true, true);
    }
}
