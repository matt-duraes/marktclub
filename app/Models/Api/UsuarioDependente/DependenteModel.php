<?php

namespace App\Models\Api\UsuarioDependente;

use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Excecao;
use ORM\ORM;

final class DependenteModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    /**
     * @param string|null $usuario
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?string $usuario = null
    ) {
        $this->validarDados();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarDados(): void
    {
        validarUuid($this->usuario);
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function listarDados(): array
    {
        $lista = $this
            ->campo([
                'cod', 'nome', 'email_pessoal',
                'email_trabalho', 'status'
            ])
            ->where([
                ['titular', $this->pegarTitular()],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ])
            ->order('id')
            ->limit(0, 5)
            ->read();

        return $this->montarRetorno($lista);
    }

    /**
     * @return int|bool
     * @throws Excecao
     */
    private function pegarTitular(): int|bool
    {
        $Cliente = new ClienteEntity(validarToken: false);
        $Cliente->buscar([
            ['cod', $this->usuario],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);
        return $Cliente->get('id');
    }

    /**
     * @param array $dependentes
     *
     * @return array
     */
    private function montarRetorno(array $dependentes): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dependentes as $dependente) {
            $email = 'Sem e-mail';

            if (!empty($dependente->email_pessoal)) {
                $email = $dependente->email_pessoal;
            } elseif (!empty($dependente->email_trabalho)) {
                $email = $dependente->email_trabalho;
            }

            $retorno[] = [
                'id' => $dependente->cod,
                'nome' => $dependente->nome ?? 'Sem nome',
                'email' => $email,
                'status' => $Status->indice($dependente->status)
            ];
        }
        return $retorno;
    }
}
