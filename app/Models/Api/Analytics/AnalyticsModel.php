<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Http\Request;
use Modules\Data;
use Helpers\CryptHelper;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class AnalyticsModel extends ORM
{
    protected string $_tabela = TABELA_ANALYTICS;

    use WhereTrait;
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    private int $idEmpresa;
    private ?int $idUsuario = null;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
        $this->validarRequest();
        $this->setarUsuarioSeExistir($request->usuario);
    }

    public function pegarRelatorio()
    {
        $dado = $this->campo([
            'uuid', 'vinculo_nome', 'usuario_cpf', 'dispositivo', 'os', 'browser',
            'versao', 'mobile', 'tablet', 'url', 'data_criacao', 'usuario_tipo'
        ])->where($this->montarWhere(), obrigatorio: false);

        if ($this->request->existe('pagina')) {
            $dado = $dado
                ->pagina($this->pegarPagina(), $this->pegarQuantidade())
                ->order('id', 'DESC')
                ->read();
            $dado->lista = $this->montarRetorno($dado->lista ?? []);
            return $dado;
        }

        $dado = $dado->read();
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {
        $retorno = [];
        $chave = TOKEN['app']->chave_publica;
        $Crypt = new CryptHelper(chavePublica: $chave);

        $TipoUsuario = new TipoUsuario();
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'usuario_tipo' => $TipoUsuario->indice($r->usuario_tipo),
                'cpf' => $Crypt->encode($r->usuario_cpf),
                'dispositivo' => $r->dispositivo,
                'os' => $r->os,
                'browser' => $r->browser,
                'versao' => $r->versao,
                'mobile' => $r->mobile == 1,
                'tablet' => $r->tablet == 1,
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

        $Cliente = new ClienteEntity(validarToken: false);
        $Cliente->id($usuario, mensagem: 'Usuario buscado não foi encontrado.');
        $this->idUsuario = $Cliente->get('id');
    }

    private function validarRequest()
    {
        $de = new Data($this->request->de);
        $ate = new Data($this->request->ate);
        $pagina = $this->request->pagina;
        $diasDiferenca = dataDiferencaDia($de->date(), $ate->date());

        if ($de->vazio() && (!$ate->vazio() || empty($pagina))) {
            mensagemErro('Data obrigatória!', 'A data de começo da busca é obrigatória.');
        } else if (!$de->vazio() && !$de->valido()) {
            mensagemErro('Data inválida!', 'A data de começo da busca não está em um formato válido.');
        } else if ($ate->vazio() && (!$de->vazio() || empty($pagina))) {
            mensagemErro('Data obrigatória!', 'A data final da busca é obrigatória.');
        } else if (!$ate->vazio() && !$ate->valido()) {
            mensagemErro('Data inválida!', 'A data final da busca não está em um formato válido.');
        } else if (!$de->vazio() && $diasDiferenca > 7) {
            mensagemErro('Datas inválidas!', 'Você deve fazer uma busca com no máximo 7 dias de diferênça.');
        } else if ($ate->date() < $de->date()) {
            mensagemErro('Datas inválidas!', 'A data final da busca deve ser maior ou igual a data de começo.');
        }
    }

    private function montarWhere()
    {
        $where = $this->idEmpresa == 1 ? [] : $this->_wherePadrao;
        if (!empty($this->idUsuario)) {
            $where[] = ['usuario', $this->idUsuario];
        }
        if (!empty($this->request->de)) {
            $where[] = ['data_criacao', 'between', [$this->request->de, $this->request->ate . ' 23:59:59']];
        }
        return $where;
    }
}
