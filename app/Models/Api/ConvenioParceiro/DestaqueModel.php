<?php

namespace App\Models\Api\ConvenioParceiro;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Classes\ParceiroConvenio\Ordem;
use App\Classes\ParceiroConvenio\Categoria;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class DestaqueModel extends ORM
{

    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_PARCEIRO_NOVO;

    private Ordem $Ordem;
    private Categoria $Categoria;
    private int $idEmpresa;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'cod', 'titulo', 'url', 'imagem', 'desconto', 'categoria_principal'
            ])
            ->where($this->pegarWhere())
            ->order($this->Ordem)
            ->limit(0, $this->request->quantidade)
            ->read();

        return (object) ['lista' => $this->montarRetorno($dado)];
    }

    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'titulo' => $r->titulo,
                'url' => $r->url,
                'imagem' => LINK_ARQUIVO . '/convenios/' . $r->imagem,
                'categoria' => (new Categoria($r->categoria_principal))->indice(),
                'desconto' => $r->desconto
            ];
        }
        return $retorno;
    }

    private function validarRequest()
    {
        $this->Categoria = new Categoria($this->request->categoria);
        $this->Ordem = new Ordem($this->request->ordem);

        if (!$this->Categoria->vazio() && !$this->Categoria->valido()) {
            mensagemErro('Campo inválido!', 'Você deve enviar uma categoria válida.');
        } else if ($this->Ordem->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar uma ordem para a busca.');
        } else if (!$this->Ordem->valido()) {
            mensagemErro('Campo inválido!', 'Você deve enviar uma ordem válida.');
        } else if (!validarPagina($this->request->quantidade)) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar a quantidade de parceiros que deseja buscar.');
        } else if ($this->request->quantidade > 20) {
            mensagemErro('Campo inválido!', 'O campo quantidade deve ser de no máximo 20.');
        }
    }

    protected function pegarWhere(): array
    {
        $where = [
            ['status', 4],
            ['empresa', 'like', '%"' . $this->idEmpresa . '"%'],
            ['destaque', 'like', '%"' . $this->idEmpresa . '"%'],
        ];
        if ($this->Categoria->valido()) {
            $where[] = [
                'OR',
                ['categoria_principal', $this->Categoria->numero()],
                ['categoria_todas', 'like', '%"' . $this->Categoria->numero() . '"%']
            ];
        }
        return $where;
    }
}
