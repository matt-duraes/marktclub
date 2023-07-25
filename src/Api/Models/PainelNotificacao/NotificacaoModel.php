<?php

namespace ApiModel\PainelNotificacao;

use App\Models\Api\UsuarioEquipe\PerfilModel;
use Erro\Excecao;
use Http\Request;
use ORM\ORM;
use stdClass;
use System\Classes\PainelNotificacao\Status;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use Throwable;

final class NotificacaoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_PAINEL_NOTIFICACAO;
    private int $idUsuario;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        try {
            $this->idUsuario = TOKEN['usuario']->id;
        } catch (Throwable) {
            mensagemStatus(404);
        }
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $lista = $this
            ->campo([
                'uuid',
                'id_usuario_dono',
                'titulo',
                'mensagem',
                'link',
                'botao',
                'target',
                'data_criacao',
                'status'
            ])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('id', 'DESC')
            ->read();

        $lista->lista = $this->montarDado($lista->lista);
        return $lista;
    }

    private function pegarWhere(): array
    {
        $where = [['id_usuario_equipe', $this->idUsuario]];
        if ($this->request->clicado == 'sim') {
            $where[] = ['status', 3];
        } elseif ($this->request->clicado == 'nao') {
            $where[] = ['status', 'in', [1, 2]];
        } elseif ($this->request->novo == 'sim') {
            $where[] = ['status', 1];
        } elseif ($this->request->novo == 'nao') {
            $where[] = ['status', 'in', [2, 3]];
        }

        return $where;
    }

    private function montarDado(array $lista): array
    {
        $Status = new Status();
        $Perfil = new PerfilModel();
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'dono'         => $Perfil->pegarDado($r->id_usuario_dono, true),
                'titulo'       => $r->titulo,
                'mensagem'     => $r->mensagem,
                'link'         => $r->link,
                'botao'        => $r->botao,
                'target'       => $r->target == '_blank' ? '_blank' : '_self',
                'data_criacao' => $r->data_criacao,
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
