<?php

namespace App\Models\Api\ApiApp;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Classes\ApiApp\Ordem;
use System\Trait\Model\OrdemTrait;
use App\Classes\StatusGeral\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class AppModel extends ORM implements
    ModelListarInterface
{
    use OrdemTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_AUTH_APP;

    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR PARA A API
    |--------------------------------------------------------------------------
    */
    public function listarDados(): stdClass
    {
        $this->validarRequestDaApi();
        $dado = $this
            ->campo(['uuid', 'nome', 'data_criacao', 'status'])
            ->where($this->pegarWhere())
            ->order($this->pegarOrdem(new Ordem()))
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo(['nome_fantasia'])
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }
    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'nome' => $r->nome,
                'dono' => $r->nome_fantasia,
                'data_criacao' => $r->data_criacao,
                'status' => (new Status($r->status))->indice()
            ];
        }
        return $retorno;
    }

    protected function pegarWhere()
    {
        return ['status', 1];
    }

    private function validarRequestDaApi()
    {
        $status = new Status($this->request->status);
        $ordem = new Ordem($this->request->ordem);

        if (empty($this->request->pagina)) {
            mensagemErro('Campo obrigatório!', 'O campo pagina é obrigatório.');
        } elseif (!empty($this->request->quantidade) && validarPagina($this->request->quantidade)) {
            mensagemErro('Campo inválido!', 'O campo quantidade deve ser um valor válido.');
        } elseif (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Campo inválido!', 'O campo ordem deve ser um valor válido.');
        } elseif (!$status->vazio() && !$ordem->valido()) {
            mensagemErro('Campo inválido!', 'O campo status deve ser um valor válido.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR PARA A DOCUMENTAÇÃO
    |--------------------------------------------------------------------------
    */
    public function listarAppPeloId(array $id): stdClass
    {
        $lista = $this->campo([
            'uuid', 'nome', 'descricao', 'chave_publica', 'chave_privada', 'client_id', 'secret_id', 'audience',
            'chave_publica_fake', 'chave_privada_fake', 'client_id_fake', 'secret_id_fake', 'authorization_code',
            'client_credentials', 'refresh_token', 'redirect_uri', 'scope_permitido', 'tempo_vida'
        ])->where([
            ['id', 'in', $id],
            ['status', 1]
        ])->read();

        return $this->montarApp($lista);
    }

    private function montarApp(array $dado): stdClass
    {
        $retorno = [];
        $scope = [];
        foreach ($dado as $r) {
            $appScope = jsonDecode($r->scope_permitido, true, true);
            $scope = array_merge($scope, $appScope);
            $retorno[] = (object)[
                'id' => $r->uuid,
                'nome' => $r->nome,
                'descricao' => $r->descricao,
                'chave_publica' => $r->chave_publica,
                'chave_privada' => $r->chave_privada,
                'client_id' => $r->client_id,
                'secret_id' => $r->secret_id,
                'audience' => $r->audience,
                'chave_publica_fake' => $r->chave_publica_fake,
                'chave_privada_fake' => $r->chave_privada_fake,
                'client_id_fake' => $r->client_id_fake,
                'secret_id_fake' => $r->secret_id_fake,
                'redirect_uri' => jsonDecode($r->redirect_uri, true, true),
                'authorization_code' => $r->authorization_code == 1 ? 'sim' : 'nao',
                'client_credentials' => $r->client_credentials == 1 ? 'sim' : 'nao',
                'refresh_token' => $r->refresh_token == 1 ? 'sim' : 'nao',
                'scope' => $appScope,
                'vida' => $r->tempo_vida
            ];
        }

        return object([
            'lista' => $retorno,
            'scope' => array_keys(array_flip($scope))
        ]);
    }
}
