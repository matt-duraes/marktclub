<?php

namespace App\Models\Api\Demanda\Trait;

use App\Models\Api\UsuarioEquipe\EquipeEntity;

trait EquipeTrait
{
    private array $equipeLista = [];

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
}
