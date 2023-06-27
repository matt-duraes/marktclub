<?php

namespace App\Models\Api\Farmacia;

use ORM\ORM;
use stdClass;
use Modules\Cpf;
use Http\Request;
use App\Classes\Farmacia\Estabelecimento;

final class FarmaciaModel extends ORM
{
    protected string $ormTabela = TABELA_PUBLICACAO_MEDICAMENTO;

    private string $idEmpresa;
    private Estabelecimento $estabelecimento;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;

    }

    public function listarDados(string $tipo = null): stdClass
    {
        $estabelecimento = $tipo ? $tipo : $this->request->estabelecimento;

        if ($estabelecimento == 'todos') {
            return $this->listarTodos();
        }

        $dado = [];
        if ($estabelecimento == 'online') :
            //TODO - inserir aqui a busca online dos parceiros, buscando na tabela de parceiros
            $dado = [];
        elseif ($estabelecimento == 'presencial') :
            $dado = $this->campo(['id', 'cod', 'titulo', 'imagem', 'desconto_texto', 'link_site'])
                ->where($this->pegarWhere())
                ->read();
        endif;

        return (object) ['lista' => $this->montarRetorno($dado)];
    }

    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {

            $banner = '';
            if ($r->id == 5524) :
                $banner = '';
            endif;

            $desconto = isset($r->descricao_secundaria) ? nl2br($r->descricao_secundaria) : '';
            if (empty($desconto)) {
                $desconto = isset($r->desconto_texto) ? nl2br($r->desconto_texto) : '';
            }

            $retorno[] = [
                'id' => $r->cod,
                'titulo' => $r->titulo,
                'desconto' => $desconto,
                'estabelecimento' => (new Estabelecimento($this->request->estabelecimento))->indice(),
                'link_site' => $r->link_site,
                'imagem' => [
                    'logo' => LINK_ARQUIVO . '/parceiro/' . $r->imagem,
                    'banner' => $banner,
                ],
                'url' => (object) [
                    'valor' => isset($r->url) ? $r->url : '',
                    'link' => isset($r->url) ? LINK_PADRAO . '/convenios/' . $r->url : '',
                ],
            ];
        }

        return $retorno;

    }

    protected function pegarWhere(): array
    {
        $where = [];

        if (!empty($this->idEmpresa)) {
            $where[] = ['empresa', 'LIKE', '%"' . $this->idEmpresa . '"%'];
        }

        $where[] = ['status', 1];

        return $where;
    }

    private function validarRequest()
    {
        $this->estabelecimento =  new Estabelecimento($this->request->estabelecimento);

        if (!$this->estabelecimento->vazio() && !$this->estabelecimento->valido()) {
            mensagemErro('Dado inválido!', 'O campo estabelecimento não é um valor válido.');
        }
    }

    private function listarTodos()
    {
        $retorno = [
            'presencial' => $this->listarDados('presencial'),
            'online' => $this->listarDados('online'),
        ];

        return (object) ['lista' => $retorno];
    }
}
