<?php

namespace System\Html\Postman\Models;

final class RequisicaoSalvar
{
    private Requisicao $Requisicao;
    private string $path = ROOT . '/postman/';
    private string $id;
    private string $pai;
    public function __construct($post)
    {
        $json = preg_replace(['/^\"/', '/\"$/', '/\\\/'], '', $post['json']);
        $json = jsonDecode($json, true, true);

        $this->id = $post['id'];
        $this->pai = $post['pai'];

        $this->verificarSeDiretorioExiste();
        $this->Requisicao = (new Requisicao($this->path, $this->pai, $this->id));
        $this->Requisicao
            ->token($post['token'])
            ->metodo($post['metodo'])
            ->uri($post['uri'])
            ->parametro(jsonDecode($post['parametro'], true, true))
            ->body(jsonDecode($post['body'], true, true))
            ->header(jsonDecode($post['header'], true, true))
            ->variavel(jsonDecode($post['variavel'], true, true))
            ->json($json)
            ->descriaco($post['descriaco'])
            ->requisicao($post['requisicao'])
            ->resposta($post['resposta'])
            ->salvar();
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
