<?php

namespace App\Models\Api\CampanhaSorteio;

use ORM\Entity;
use Modules\DataHora;
use App\Classes\CampanhaSorteio\Status;

final class SorteioEntity extends Entity
{
    protected string $ormTabela = TABELA_CAMPANHA_SORTEIO;
    protected array $ormBuscar = ['titulo', 'texto', 'imagem', 'status', 'lista_usuario', 'numero_sorteado', 'data_sorteio', 'usuario_sorteado', 'hash'];
    protected array $ormUpdate = ['data_sorteio', 'hash', 'usuario_sorteado', 'status'];

    public array $usuario_sorteado;
    public string $hash;
    protected array $lista_usuario;
    protected int $numero_sorteado;
    public DataHora $data_sorteio;
    public Status $status;

    protected function regraPosBuscar()
    {
        $this->imagem = LINK_ARQUIVO . '/campanha_sorteio/' . $this->imagem;
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA SORTEAR USUÁRIO
    |--------------------------------------------------------------------------
    */
    public function sortearUsuarios()
    {
        $this->validarRegrasParaSorteio();
        $this->setarUsuarioVencedores();
        $this->setarPropriedadesParaSortear();
    }
    private function validarRegrasParaSorteio(): void
    {
        if ($this->status->indice() != 'ativo') {
            mensagemErro(
                'Erro no sorteio!',
                'Esse sorteio não está apto a ser realizado.',
                localhost: 'Status do sorteio era esperado ativo e esta ' . $this->status->indice(),
                status: 403
            );
        } elseif (count($this->lista_usuario) < $this->numero_sorteado) {
            mensagemErro(
                'Erro no sorteio!',
                'Não existem usuários suficiente para serem sorteados.'
            );
        }
        return;
    }
    private function setarUsuarioVencedores()
    {
        $listaUsuario = $this->lista_usuario;
        $sorteados = array_rand($listaUsuario, $this->numero_sorteado);
        $usuario = [];
        foreach ($sorteados as $r) {
            $usuario[] = $listaUsuario[$r];
        }
        $this->usuario_sorteado = $usuario;
    }
    private function setarPropriedadesParaSortear()
    {
        $this->data_sorteio = new DataHora(agora());
        $this->hash = uuid();
        $this->status = new Status('sorteado');
    }
}
