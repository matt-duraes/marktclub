<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Http\Request;
use Helpers\CryptHelper;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class AnalyticsModel extends ORM
{
    protected string $_tabela = TABELA_ANALYTICS;

    use WhereTrait;

    private int $idEmpresa;
    private ?int $idUsuario = null;

    public function __construct(
        private Request $request
    ) {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
        $this->setarUsuarioSeExistir($request->usuario);
        $this->validarData($request->de, $request->ate);
    }

    public function pegarRelatorio()
    {
        $dado = $this->campo([
            'uuid', 'vinculo_nome', 'documento_cpf', 'dispositivo', 'os', 'browser', 'url', 'data_criacao'
        ])->where($this->montarWhere())->read();
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {
        $retorno = [];
        $chave = TOKEN['app']->chave_publica;
        $Crypt = new CryptHelper(chavePublica: $chave);

        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'cpf' => $Crypt->encode($r->documento_cpf),
                'dispositivo' => $r->dispositivo,
                'os' => $r->os,
                'browser' => $r->browser,
                'data' => $r->data_criacao,
                'url' => $r->url,
            ];
        }

        return $retorno;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function setarUsuarioSeExistir(?string $usuario)
    {
        if (empty($usuario)) {
            return;
        }

        $Cliente = new ClienteEntity();
        $Cliente->id($usuario);

        if (empty($Cliente->id)) {
            return;
        }

        $this->idUsuario = $Cliente->get('id');
    }

    private function montarWhere()
    {
        $where = $this->pegarWherePadrao($this->request->de, $this->request->ate);
        if (!empty($this->idUsuario)) {
            $where[] = ['usuario', $this->idUsuario];
        }
        return $where;
    }
}
