<?php

namespace ORM\Deletar;

use Erro\Excecao;

trait DestruirTrait
{
    public function destruir()
    {
        $this->ormVerificarSeEntityExiste();
        $id = $this->ormEntityId;
        if (!is_int($id) || $id <= 0) {
            throw new Excecao(titulo: 'Erro ao destruir', mensagem: 'Não existe uma entidade para ser destruida');
        }
        if (method_exists($this, 'regraDestruir')) {
            $this->regraDestruir();
        }

        $deletarArquivo = $this->ormPegarArquivoParaDeletar($this->ormEntityRetorno, true);
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

        $this->ormDiff = [];
        $this->ormSet = [];
        $this->ormEntityRetorno = [];
        $this->ormEntityDeletada = true;

        $this->ormEntityId = null;
        $this->ormEntityUuid = '';
        $this->ormPropriedadePublica = [];
        $this->ormPropriedadePrivada = [];
        $this->ormListaSet = [];
        $this->ormListaAliasReal = [];
        $this->ormDestruirPDO();
    }
}
