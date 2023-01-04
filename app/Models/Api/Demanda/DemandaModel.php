<?php

namespace App\Models\Api\Demanda;

use ORM\ORM;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Ordem;
use App\Classes\DemandaDado\Status;
use phpDocumentor\Reflection\Types\Parent_;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class DemandaModel extends ORM
{
    protected string $_tabela = TABELA_DEMANDA_DADO;
    private array $equipeLista = [];

    public function __construct(
        private Status $status,
        private Ordem $ordem
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    public function listarDados(): array
    {
        $this->validarRequest();
        $lista = $this
            ->campo([
                'uuid', 'id', 'id_usuario_equipe', 'id_admin_empresa', 'titulo', 'tipo',
                'data_criacao', 'data_atualizacao', 'status'
            ])
            ->where($this->montarWhere())
            ->order($this->ordem)
            ->tabela(TABELA_DEMANDA_TAREFA)
            ->join('id_demanda_dado', 'id')
            ->campo(['id_usuario_equipe'], 'tarefa')
            ->read();

        return $this->montarRetorno($lista);
    }

    private function validarRequest()
    {
        if ($this->status->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um status para busca.');
        } else if (!$this->status->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar um status válido para a busca.');
        } else if ($this->ordem->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve passar uma ordem para busca.');
        } else if (!$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar uma ordem válido para a busca.');
        }
    }

    private function montarWhere()
    {
        $status = $this->status;
        if ($status->indice() == 'finalizada') {
            return [
                ['status', $status->numero()],
                ['data_atualizacao', '<', dataRemover(agora(), 10, 'dias')]
            ];
        }
        return ['status', $status->numero()];
    }

    private function montarRetorno(array $lista): array
    {
        $demandaJaExiste = [];
        $equipeJaExiste = [];

        $retorno = [];
        foreach ($lista as $r) {
            if (in_array($r->id, $demandaJaExiste)) {
                $retorno[$r->id]['tarefa']++;
                $equipe = $this->pegarUsuarioEquipe($r->tarefa_id_usuario_equipe);
                if (!empty($equipe->id) && !in_array($r->id . $r->id_usuario_equipe, $equipeJaExiste)) {
                    $retorno[$r->id]['equipe'][] = $equipe;
                    $equipeJaExiste[] = $r->id . $r->id_usuario_equipe;
                }
                continue;
            }
            $demandaJaExiste[] = $r->id;

            $equipe = $this->pegarUsuarioEquipe($r->id_usuario_equipe);
            $retorno[$r->id] = [
                'id' => $r->uuid,
                'dono' => $equipe,
                'empresa' => $this->pegarEmpresa($r->id_admin_empresa),
                'equipe' => [],
                'tarefa' => 1,
                'titulo' => $r->titulo,
                'tipo' => (new Tipo($r->tipo))->indice(),
                'status' => (new Status($r->status))->indice()
            ];
        }

        return array_values($retorno);
    }

    private function pegarUsuarioEquipe($equipe)
    {
        if (!array_key_exists($equipe, $this->equipeLista)) {
            try {
                $Equipe = new EquipeEntity(validarToken: false);
                $Equipe->_id($equipe);
                $this->equipeLista[$equipe] = object([
                    'id' => $Equipe->id,
                    'nome' => $Equipe->nome->primeiroNome() . ' ' . $Equipe->nome->ultimoSobrenome(),
                    'imagem' => $Equipe->imagem
                ]);
            } catch (\Throwable) {
                $this->equipeLista[$equipe] = object([
                    'id' => null,
                    'nome' => 'Sem usuário',
                    'imagem' => imagemUsuario()
                ]);
            }
        }

        return $this->equipeLista[$equipe];
    }

    private function pegarEmpresa($id)
    {
        $Empresa = new EmpresaEntity();
        $Empresa->_id($id);

        return [
            'id' => $Empresa->id,
            'nome' => $Empresa->nome_fantasia,
            'imagem' => $Empresa->imagem
        ];
    }
}
