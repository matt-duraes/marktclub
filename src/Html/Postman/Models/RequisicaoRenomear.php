<?php

namespace System\Html\Postman\Models;

final class RequisicaoRenomear
{
    private Requisicao $Requisicao;
    private string $path = ROOT . '/postman/';
    private string $id;
    private string $pai;
    private string $nome;
    public function __construct($post)
    {
        $this->id = $post['id'];
        $this->pai = $post['pai'];
        $this->nome = $post['nome'];

        $this->verificarSeDiretorioExiste();
        $this->Requisicao = (new Requisicao($this->path, $this->pai, $this->id));
        $this->Requisicao->nome($this->nome)->salvar();
    }
    public function retorno()
    {
        return $this->Requisicao->requisicao;
    }
    private function verificarSeDiretorioExiste()
    {
        if (!empty($this->pai) && !is_dir($this->path . $this->pai)) {
            mensagemErro('Erro!', 'Não foi possível encontrar diretório pai.');
        }
    }
}
