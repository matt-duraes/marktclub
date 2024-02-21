<?php

namespace App\Models\Api\PublicacaoHome;

use ORM\Entity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\PublicacaoNoticia\NoticiaEntity;

final class HomeEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_HOME;
    protected array $ormSalvar = [
        'noticia_1', 'noticia_1', 'noticia_1'
    ];
    protected array $ormBuscar = [
        'noticia_1', 'noticia_1', 'noticia_1'
    ];

    public string $noticia_1;
    public string $noticia_2;
    public string $noticia_3;

    public function __construct()
    {
        $this->validarEmpresa();
        if(empty($this->idEmpresa)) {
            mensagemStatus(404);
        }

        $this->buscar(['id_admin_empresa', $this->idEmpresa]);
    }

    protected function regraSalvar()
    {
        $this->verificarExisteNoticia($this->noticia_1, 1);
        $this->verificarExisteNoticia($this->noticia_2, 2);
        $this->verificarExisteNoticia($this->noticia_3, 3);
    }

    private function verificarExisteNoticia(string $id, int $numero): void
    {
        if(empty($id)) {
            return;
        }
        try {
            $Noticia = new NoticiaEntity();
            $Noticia->uuid($id);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'A notícia de número ' . $numero . ' não existe.');
        }
    }
}

