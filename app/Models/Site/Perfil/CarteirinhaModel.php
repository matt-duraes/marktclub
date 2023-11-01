<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use App\Helpers\ClubeApiHelper;

final class CarteirinhaModel extends ClubeApiHelper
{
    public function buscarCampos(): object|array
    {
        $empresa = sessao('CLUBE');
        $dado = $this
            ->validar('Página não encontrada!', status: 404)
            ->json([
                'empresa'   => $empresa->empresa,
                'pagina'    => 1
            ])
            ->get('/carteirinha')
            ->object();

        if (empty($dado->dado->lista) || $dado->dado->lista[0]->status != 'ativo') {
            mensagemErro('Nenhuma carteirinha cadastrada', 'Sua empresa ainda não ativou nenhuma carteirinha');
        }

        return $dado->dado->lista[0];
    }

    public function buscarDadosUsuario(): object|array
    {
        $dado = $this
            ->validar('Usuário não encontrado!', status: 404)
            ->get('/usuario-cliente/' . sessao('USUARIO.id'))
            ->object();
        return $this->montarRetorno($dado->dado);
    }

    /**
     * @param $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($dado): object|array
    {
        return (object)[
            'usuario' => (object) [
                'nome'            => $this->Crypt->decode($dado->nome) ?? '',
                'matricula'       => $this->Crypt->decode($dado->matricula) ?? '',
                'cpf'             => strCpf($this->Crypt->decode($dado->cpf) ?? ''),
                'estado'          => $this->Crypt->decode($dado->endereco_estado) ?? '',
                'data_nascimento' => dataBr($this->Crypt->decode($dado->data_nascimento) ?? ''),
                'data_validade'   => dataBr($dado->data_validade ?? '')
            ]
        ];
    }
}
