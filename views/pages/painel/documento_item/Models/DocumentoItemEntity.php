<?php

namespace Painel\DocumentoItem\Models;

use stdClass;
use Erro\Excecao;
use App\Models\Painel\AppGeral\AppGeralEntity;

final class DocumentoItemEntity extends AppGeralEntity
{
    protected string $ormTabela = TABELA_DOCUMENTO_ITEM;

    protected array $ormBuscar = ['uuid', 'titulo', 'texto', 'status'];
    protected array $ormSalvar = ['titulo', 'texto', 'status'];

    public function dadoEditar(): stdClass
    {
        return (object) [
            'id' => $this->id,
            'titulo' => $this->prop('titulo'),
            'texto' => $this->prop('texto'),
            'status' => $this->prop('status')
        ];
    }

    protected function regraSalvar()
    {
        if ($this->status != 1) {
            $this->status = 2;
        }
        if (empty($this->titulo)) {
            throw new Excecao(
                titulo: 'Campo obrigatório!',
                mensagem: 'O campo título é obrigatório.'
            );
        } elseif (empty($this->texto)) {
            throw new Excecao(
                titulo: 'Campo obrigatório!',
                mensagem: 'O campo descrição é obrigatório.'
            );
        }
    }
}
