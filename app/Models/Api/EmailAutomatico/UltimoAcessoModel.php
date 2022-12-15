<?php

namespace App\Models\Api\EmailAutomatico;

use ORM\ORM;
use Helpers\EmailHelper;
use App\Models\Api\EmailAutomatico\Trait\ErroTrait;
use App\Models\Api\EmailAutomatico\Trait\EmailTrait;

final class UltimoAcessoModel extends ORM
{

    use EmailTrait;
    use ErroTrait;

    protected string $_tabela = TABELA_USUARIO_NOVO;

    private array $idEmpresa;
    private array $dadoParaEnvio;

    public function __construct()
    {
        parent::__construct();
        $this->pegarEmpresasAtivas();
        $this->pegarUsuarios();
        $this->mandarEmail();
    }

    /*
    |--------------------------------------------------------------------------
    | EMPRESA
    |--------------------------------------------------------------------------
    */
    private function pegarEmpresasAtivas()
    {
        $tabelaConstrutor = '`' . TABELA_CONSTRUTOR_NOVO . '`';
        $tabelaEmpresa = '`' . TABELA_EMPRESA_NOVO . '`';

        $empresa = $this->readTexto(
            "
                SELECT
                    {$tabelaConstrutor}.`empresa`, {$tabelaConstrutor}.`link_login`, {$tabelaConstrutor}.`logo`,
                    {$tabelaConstrutor}.`cor`, {$tabelaConstrutor}.`titulo`
                FROM {$tabelaConstrutor}
                INNER JOIN {$tabelaEmpresa} ON {$tabelaEmpresa}.`id` = {$tabelaConstrutor}.`empresa`
                WHERE {$tabelaEmpresa}.`status` = ?
            ",
            [1]
        );
        $this->montarDadoEmpresa($empresa);
    }

    private function montarDadoEmpresa($empresa)
    {
        if (!$empresa) {
            $this->erroGeral();
        }

        foreach ($empresa as $r) {
            $this->idEmpresa[] = $r->empresa;
            $this->dadoParaEnvio[$r->empresa] = (object)[
                'titulo' => $r->titulo,
                'link_login' => $r->link_login,
                'link_logo' => LINK_ARQUIVO . '/construtor/' . $r->logo,
                'cor' => $r->cor,
                'usuario' => []
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | USUARIO
    |--------------------------------------------------------------------------
    */
    private function pegarUsuarios()
    {
        $data = dataRemover(date('Y-m-d'), 90, 'dias');
        $usuario = $this
            ->campo(['empresa', 'nome', 'email_pessoal', 'email_trabalho', 'email_funcional'])
            ->where([
                ['data_acesso', '>=', $data . ' 00:00:00'],
                ['empresa', 'in', $this->idEmpresa],
                ['status', 1]
            ])->read();

        $this->montarDadoUsuario($usuario);
    }

    private function montarDadoUsuario($usuario)
    {
        if (!$usuario) {
            $this->erroGeral();
        }

        foreach ($usuario as $r) {
            $email = $this->pegarEmail([$r->email_pessoal, $r->email_trabalho, $r->email_funcional]);
            if (empty($email)) {
                continue;
            }
            $this->dadoParaEnvio[$r->empresa]->usuario[] = (object)[
                'nome' => $r->nome,
                'email' => $email
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ENVIAR E-MAIL
    |--------------------------------------------------------------------------
    */
    private function mandarEmail()
    {
        foreach ($this->dadoParaEnvio as $r) {
            if (empty($r->usuario)) {
                continue;
            }
            $this->mandarEmailParaCadaEmpresa($r->titulo, $r->cor, $r->link_login, $r->link_logo, $r->usuario);
        }
    }
    private function mandarEmailParaCadaEmpresa($tituloClube, $cor, $linkLogin, $linkLogo, $usuario)
    {
        $assunto = 'Sentimos sua Falta';
        foreach ($usuario as $r) {
            $Email = new EmailHelper();
            $Email->mensagem(
                titulo: $assunto,
                assunto: $assunto,
                mensagem: 'Ola ' . $r->nome . '! Estamos sentido sua falta no ' . $tituloClube . ', que tal acessar seu Clube e ver as novidades que separamos para você?',
                botaoTexto: 'Acessar Clube',
                botaoLink: $linkLogin,
                cor: $cor,
                logo: $linkLogo
            );
            $Email->sendGrid(titulo: $assunto, nome: $r->nome, email: $r->email);
        }
    }
}
