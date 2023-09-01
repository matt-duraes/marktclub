<?php

namespace App\Models\Api;

use ORM\ORM;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class OrdenarModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;

    private array $dado = [];

    public function __construct(
        private array $id,
        private string $tabela,
        private Pagina $pagina,
        private Quantidade $quantidade = new Quantidade(),
    ) {
        $this->ormTabela = $tabela;
        parent::__construct();
        $this->buscarDado();
        $this->atualizar();
    }

    private function buscarDado()
    {
        $dado = $this->campo(['id', 'uuid'])->where(['uuid', 'in', $this->id])->read();
        if (!$dado) {
            return;
        }
        foreach ($dado as $r) {
            $this->dado[$r->uuid] = $r->id;
        }
    }

    private function atualizar()
    {
        if (empty($this->dado)) {
            mensagemErro('Erro!', 'Não foi encontrado registros para serem alterados.');
        }
        $quantidade = $this->quantidade->numero() == 0 ? $this->quantidade->padrao() : $this->quantidade->numero();
        $i = ($this->pagina->numero() - 1) * $quantidade;
        $dado = $this->dado;
        foreach ($this->id as $id) {
            if (!array_key_exists($id, $dado)) {
                continue;
            }
            $i++;
            $this->dado(['ordem' => $i])->where(['id', $dado[$id]])->update();
        }
    }
}
