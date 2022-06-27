<?php

namespace App\Models\Painel\AppGeral;

use stdClass;

interface PainelEntityInterface
{
    public function id(string | int $id, bool $erro = true);

    public function diff();

    public function salvar();

    public function set(string $propriedade = '', $valor = '', ?array $lista = null);

    public function dadoEditar(): stdClass;

    public function dadoVisualizar(): stdClass;

    public function setarStatus(string|int $status);
}
