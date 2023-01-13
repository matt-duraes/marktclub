<?php

namespace ApiModel\PainelNotificacao;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use System\Classes\PainelNotificacao\Status;
use App\Models\Api\UsuarioEquipe\PerfilModel;

final class NotificacaoModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $_tabela = TABELA_PAINEL_NOTIFICACAO;

    private int $idUsuario;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
        try {
            $this->idUsuario = TOKEN['usuario']->get('id');
        } catch (\Throwable) {
            return;
        }
    }

    public function listarDados(): stdClass
    {
        $lista = $this
            ->campo(['uuid', 'id_usuario_dono', 'titulo', 'mensagem', 'link', 'botao', 'data_criacao', 'status'])
            ->where(['id_usuario_equipe', $this->idUsuario])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        $lista->lista = $this->montarDado($lista->lista);
        return $lista;
    }

    private function montarDado(array $lista): array
    {
        $Status = new Status();
        $Perfil = new PerfilModel();
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'dono' => $Perfil->pegarDado($r->id_usuario_dono, true),
                'titulo' => $r->titulo,
                'mensagem' => $r->mensagem,
                'link' => $r->link,
                'botao' => $r->botao,
                'data_criacao' => $r->data_criacao,
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
