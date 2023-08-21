<?php

namespace App\Models\Api\EmailAutomatico;

use ORM\ORM;
use Helpers\EmailHelper;
use App\Models\Api\EmailAutomatico\Trait\EmailTrait;

final class UltimoAcessoModel extends ORM
{
    use EmailTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
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
        $tabelaConstrutor = '`' . TABELA_CONSTRUTOR_CLUBE . '`';
        $tabelaEmpresa = '`' . TABELA_COMERCIAL_EMPRESA . '`';

        $empresa = $this->readTexto(
            "
                SELECT
                    {$tabelaConstrutor}.`empresa`, {$tabelaConstrutor}.`link_login`, {$tabelaConstrutor}.`link_site`,
                    {$tabelaConstrutor}.`logo`, {$tabelaConstrutor}.`cor`, {$tabelaConstrutor}.`titulo`
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
            mensagemStatus(500, localhost: 'Nenhum empresa encontrada.');
        }

        foreach ($empresa as $r) {
            $this->idEmpresa[] = $r->empresa;
            $this->dadoParaEnvio[$r->empresa] = (object)[
                'titulo'     => $r->titulo,
                'link_site'  => $r->link_site,
                'link_login' => $r->link_login,
                'link_logo'  => LINK_ARQUIVO . '/construtor/' . $r->logo,
                'cor'        => $r->cor,
                'usuario'    => []
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
            ->campo(['cod', 'empresa', 'nome', 'email_pessoal', 'email_trabalho', 'email_funcional'])
            ->where([
                ['DATE(data_acesso)', $data],
                [
                    'OR',
                    ['data_acesso_email', 'null'],
                    ['data_acesso_email', '<', hoje()]
                ],
                ['empresa', 'in', $this->idEmpresa],
                ['status', 1]
            ])
            ->limit(0, 50)
            ->read();
        $this->montarDadoUsuario($usuario);
    }

    private function montarDadoUsuario($usuario)
    {
        if (!$usuario) {
            exit();
        }

        foreach ($usuario as $r) {
            $email = $this->pegarEmail([$r->email_pessoal, $r->email_trabalho, $r->email_funcional]);
            if (empty($email)) {
                continue;
            }
            $this->dadoParaEnvio[$r->empresa]->usuario[] = (object)[
                'id'    => $r->cod,
                'nome'  => $r->nome,
                'email' => $email
            ];
        }
    }

    private function atualizarDataEmailUsuario($id)
    {
        $this->dado(['data_acesso_email' => hoje()])->where(['cod', $id])->update();
    }

    /*
    |--------------------------------------------------------------------------
    | ENVIAR E-MAIL
    |--------------------------------------------------------------------------
    */
    private function mandarEmail()
    {
        foreach ($this->dadoParaEnvio as $id => $r) {
            if (empty($r->usuario)) {
                continue;
            }

            $loja = $this->pegarUltimosSeisLojas($id);
            $this->mandarEmailParaCadaEmpresa(
                $r->titulo,
                $r->cor,
                $r->link_site,
                $r->link_login,
                $r->link_logo,
                $r->usuario,
                $loja
            );
        }
    }

    private function mandarEmailParaCadaEmpresa($tituloClube, $cor, $linkSite, $linkLogin, $linkLogo, $usuario, $loja)
    {
        $assunto = 'Sentimos sua Falta';
        foreach ($usuario as $r) {
            $Email = new EmailHelper();
            $Email->mensagem(
                titulo: $assunto,
                // @codingStandardsIgnoreStart
                mensagem: 'Olá ' . $r->nome . '! Estamos sentido sua falta, que tal acessar seu Clube e ver as novidades que separamos para você?',
                // @codingStandardsIgnoreEnd
                botaoTexto: 'Acessar Clube',
                botaoLink: $linkLogin,
                cor: $cor,
                logo: $linkLogo,
                linkRemover: 'https://apiv4.marktclub.net.br/emailmarketing/remover/' . base64Encode($r->id),
                posMensagem: $this->montarMensagemLoja($loja, $linkSite)
            );
            $Email->sendGrid(titulo: $assunto, nome: $r->nome, email: $r->email, deNome: $tituloClube);
            $this->atualizarDataEmailUsuario($r->id);
            sleep(1);
        }
    }

    private function montarMensagemLoja($loja, $linkSite)
    {
        if (empty($loja)) {
            return '';
        }
        $mensagem = '
            <strong>Veja algumas das novas lojas que separamos para você:</strong><br><br>
        ';
        foreach ($loja as $r) {
            // @codingStandardsIgnoreStart
            $mensagem .= '<a style="padding-top: 5px; text-decoration: none" href="' . $linkSite . '/convenios/' . $r->url . '" target="_blank" rel="noopener noreferrer">' . $r->titulo . ' - ' . $r->desconto . '</a><br>';
            // @codingStandardsIgnoreEnd
        }
        $mensagem .= '<br>Não perca tempo e venha conferir todas as novidades!';
        return $mensagem;
    }

    /*
    |--------------------------------------------------------------------------
    | LOJA
    |--------------------------------------------------------------------------
    */
    public function pegarUltimosSeisLojas($idEmpresa)
    {
        $parceiro = $this->readTexto(
            '
                SELECT
                    `titulo`, `imagem`, `url`, `desconto`
                FROM ' . TABELA_PARCEIRO_LOJA . '
                WHERE `empresa` LIKE ? AND `status` = ?
                ORDER BY `data_publicacao` DESC
                LIMIT 0, 6
            ',
            ['%"' . $idEmpresa . '"%', 4]
        );
        return $parceiro;
    }
}
