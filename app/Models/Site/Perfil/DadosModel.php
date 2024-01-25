<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Helpers\SocialHelper;
use App\Helpers\ClubeApiHelper;

final class DadosModel extends ClubeApiHelper
{
    protected string $chave;

    /**
     * @return object|array
     * @throws Excecao
     */
    public function getDado(): object|array
    {
        $dado = $this
            ->validar('Página não encontrada!', status: 404)
            ->get('/usuario-cliente/' . $this->idUsuario)
            ->object();
        return $this->montarRetorno($dado->dado);
    }

    public function pegarListaEmail(): array
    {
        $email = $this->getDado();
        $lista = [];
        if (!empty($email->email_pessoal)) {
            $lista[] = $email->email_pessoal;
        }
        if (!empty($email->email_trabalho)) {
            $lista[] = $email->email_trabalho;
        }
        return $lista;
    }

    /**
     * @param $dado
     *
     * @return object|array
     * @throws Excecao
     */
    private function montarRetorno($r): object|array
    {
        return (object)[
            'id'                   => $r->id,
            'nome'                 => $this->Crypt->decode($r->nome),
            'cpf'                  => strCpf($this->Crypt->decode($r->cpf)),
            'email_pessoal'        => $this->Crypt->decode($r->email_pessoal),
            'email_trabalho'       => $this->Crypt->decode($r->email_trabalho),
            'telefone_trabalho'    => strTelefone($this->Crypt->decode($r->telefone_trabalho)),
            'telefone_pessoal'     => strTelefone($this->Crypt->decode($r->telefone_pessoal)),
            'estado_civil'         => $this->Crypt->decode($r->estado_civil),
            'genero'               => $this->Crypt->decode($r->genero),
            'data_nascimento'      => dataBr($this->Crypt->decode($r->data_nascimento)),
            'endereco_cep'         => strCep($this->Crypt->decode($r->endereco_cep)),
            'endereco_logradouro'  => $this->Crypt->decode($r->endereco_logradouro),
            'endereco_numero'      => $this->Crypt->decode($r->endereco_numero),
            'endereco_complemento' => $this->Crypt->decode($r->endereco_complemento),
            'endereco_bairro'      => $this->Crypt->decode($r->endereco_bairro),
            'endereco_cidade'      => $this->Crypt->decode($r->endereco_cidade),
            'endereco_estado'      => $this->Crypt->decode($r->endereco_estado),
            'data_criacao'         => dataBr($r->data_criacao),
            'imagem'               => $this->Crypt->decode($r->imagem)
        ];
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDado(Request $request): Response
    {
        $this
            ->validar('Ocorre um erro ao atualizar seus dados, por favor, tente novamente.')
            ->body([
                'nome'                 => $this->Crypt->encode($request->nome),
                'data_nascimento'      => $this->Crypt->encode($request->data_nascimento),
                'genero'               => $this->Crypt->encode($request->genero),
                'estado_civil'         => $this->Crypt->encode($request->estado_civil),
                'email_pessoal'        => $this->Crypt->encode($request->email_pessoal),
                'email_trabalho'       => $this->Crypt->encode($request->email_trabalho),
                'telefone_trabalho'    => $this->Crypt->encode($request->telefone_trabalho),
                'telefone_pessoal'     => $this->Crypt->encode($request->telefone_pessoal),
                'endereco_estado'      => $this->Crypt->encode($request->endereco_estado),
                'endereco_cep'         => $this->Crypt->encode($request->endereco_cep),
                'endereco_logradouro'  => $this->Crypt->encode($request->endereco_logradouro),
                'endereco_bairro'      => $this->Crypt->encode($request->endereco_bairro),
                'endereco_numero'      => $this->Crypt->encode($request->endereco_numero),
                'endereco_complemento' => $this->Crypt->encode($request->endereco_complemento),
                'endereco_cidade'      => $this->Crypt->encode($request->endereco_cidade)
            ])
            ->put('/usuario-cliente/' . $this->idUsuario);

        $email = empty($request->email_pessoal)
            ? $request->email_trabalho
            : $request->email_pessoal;

        sessao('USUARIO.nome', $request->nome);
        sessao('USUARIO.email', $email);

        return new Response(status: 204);
    }

    public function atualizarEmail(Request $request): Response
    {
        $this
            ->validar('Ocorre um erro ao atualizar seus e-mails, por favor, tente novamente.')
            ->body([
                'email_pessoal'        => $this->Crypt->encode($request->email_pessoal),
                'email_trabalho'       => $this->Crypt->encode($request->email_trabalho),
            ])
            ->put('/usuario-cliente/' . $this->idUsuario);

        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postImagem(Request $request): Response
    {
        $imagem = $request->getFiles('imagem');
        if (empty($imagem)) {
            mensagemErro('Imagem inválida!', 'Ocorreu um erro.');
        }

        $dado = $this
            ->arquivo([
                'id' => sessao('USUARIO.id'),
                'imagem_google' => $imagem,
            ])
            ->post('/usuario-cliente/imagem')
            ->object();
        ppe($dado);

        return mensagemSucesso([
            'imagem' => $imagem
        ], status: 201);
    }

    /**
     * @param $request
     *
     * @return Response|void
     * @throws Excecao
     */
    private function pegarIdRedeSocial($request)
    {
        $Social = new SocialHelper(
            rede: 'google',
            code: $request->code
        );

        return $Social->imagem();
    }
}
