<?php

namespace ORM\Deletar;

use Erro\Excecao;

trait DestruirTrait
{
    public function destruir()
    {
        $this->ormVerificarSeEntityExiste();
        $id = $this->_entityId;
        if (!is_int($id) || $id <= 0) {
            throw new Excecao(titulo: 'Erro ao destruir', mensagem: 'Não existe uma entidade para ser destruida');
        }
        if (method_exists($this, 'regraDestruir')) {
            $this->regraDestruir();
        }

        $deletarArquivo = $this->ormPegarArquivoParaDeletar($this->_entityRetorno, true);
        $this->where(['id', $id])->delete();
        $this->ormDeletarArquivos($deletarArquivo);

        if (method_exists($this, 'regraPosDestruir')) {
            $this->regraPosDestruir();
        }
        $this->ormDestruirClasse();
    }

    private function ormDestruirClasse()
    {
        $this->id = '';

        $this->_diff = [];
        $this->_set = [];
        $this->_get = [];
        $this->_entityRetorno = [];
        $this->_entityDeletada = true;

        $this->_entityId = null;
        $this->_entityAcao = '';
        $this->_entityUuid = '';
        $this->_propriedadePublica = [];
        $this->_propriedadePrivada = [];
        $this->_listaSet = [];
        $this->_listaAliasReal = [];
        $this->ormDestruirPDO();
    }
}
