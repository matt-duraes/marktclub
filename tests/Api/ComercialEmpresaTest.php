<?php

namespace Tests\Api;

use App\Classes\ComercialEmpresa\CadastroUsuario;
use App\Classes\ComercialEmpresa\ContratoPrazo;
use App\Classes\ComercialEmpresa\ContratoRenovacao;
use App\Classes\ComercialEmpresa\EmailDisparo;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use App\Classes\ComercialEmpresa\FinalidadeSecundaria;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Classes\ComercialEmpresa\TipoSite;
use Modules\Botao;
use Tests\Tests;

class ComercialEmpresaTest extends Tests
{
    private array $cnpjValidos;
    private string $idComercialEmpresa;

    public function listarTodosComercialEmpresaTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1
            ])
            ->get('/comercial-empresa');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function listarPorStatusValidosTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:listar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'status' => valorAleatorio(array_keys((new Status())->select()))
            ])
            ->get('/comercial-empresa')
            ->array()['dado'];

        foreach ($dado['lista'] as $comercial) {
            if(!empty($comercial['cnpj'])) {
                $this->cnpjValidos[] = $comercial['cnpj'];
            }
        }

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function listarCnpjValidoEmpresaTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'cnpj'   => valorAleatorio($this->cnpjValidos)
            ])
            ->get('/comercial-empresa');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeListarPorStatusInvalidoTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'status' => 'STATUS INVALIDO'
            ])
            ->get('/comercial-empresa');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O status enviado não é válido.')
            ->checkIndiceNaoExiste('dado.lista.0.id');
    }

    public function naoPodeListarComCnpjInvalidoTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1,
                'cnpj'   => $this->cryptEncode('CPF INVALIDO')
            ])
            ->get('/comercial-empresa');

        return $this
            ->checkStatus(200)
            ->checkIndiceNaoExiste('dado.lista.0.id');
    }

    public function salvarComFinalidadePublicaTest(): ComercialEmpresaTest

    {
        $this->api('comercial_empresa:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody([
                'finalidade_principal' => FinalidadePrincipal::PUBLICA]
            ))
            ->post('/comercial-empresa')
            ->array()['dado'];

        $this->idComercialEmpresa = $dado['id'];

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function buscarComercialEmpresaTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idComercialEmpresa);
    }

    public function naoPodeAtualizarStatusDaFinalidadePublicaParaAtivoSemDataEleicaoTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => Status::ATIVO
            ])
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Data da eleição é obrigatório.');
    }

    public function atualizarStatusDaFinalidadePublicaParaAtivoEAdicionarDataEleicaoTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBodyStatusAtivo([
                'status' => Status::ATIVO,
                'data_eleicao' => $this->dataPassada()
            ]))
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(204);
    }

    public function salvarComProdutoClubeSimTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody([
                'produto_clube' => Botao::SIM
            ]))
            ->post('/comercial-empresa')
            ->array();

        $this->idComercialEmpresa = $dado['dado']['id'];

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeAtualizarStatusDoProdutoClubeParaSimSemTipoSiteTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => Status::ATIVO
            ])
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Tipo de site do clube é obrigatório.');
    }

    public function atualizarStatusDoProdutoClubeParaSimEAdicionarTipoSiteTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBodyStatusAtivo([
                'produto_clube' => Botao::SIM,
                'tipo_site' => valorAleatorio(array_keys((new TipoSite())->select()))
            ]))
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(204);
    }

    public function salvarComTipoPagamentoMistoTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody([
                'tipo_pagamento' => TipoPagamento::MISTO
            ]))
            ->post('/comercial-empresa')
            ->array();

        $this->idComercialEmpresa = $dado['dado']['id'];

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeAtualizarStatusDoTipoPagamentoParaMistoSemValorMinimoDoContratoTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => Status::ATIVO
            ])
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Valor mínimo do contrato é obrigatório.');
    }

    public function naoPodeAtualizarStatusDoTipoPagamentoParaMistoSemNumeroMinimoDeUsuarioTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => Status::ATIVO,
                'contrato_valor_minimo' => $this->cryptEncode(rand(1000, 1000000))
            ])
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Número mínimo de usuário é obrigatório.');
    }

    public function atualizarStatusDoTipoPagamentoParaMistoEAdicionarNumeroMinimoDeUsuarioEOValorMinimoDoContratoTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBodyStatusAtivo([
                'status' => Status::ATIVO,
                'contrato_usuario_minimo' => rand(1, 100),
                'contrato_valor_minimo' => $this->cryptEncode(rand(1000, 1000000))
            ]))
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAtualizarComunicacaoEmailSemEmailDiaTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'comunicacao_email' => Botao::SIM
            ])
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Qual dia será enviado o e-mail é obrigatório.');
    }

    public function naoPodeAtualizarComunicacaoEmailSemQuemDisparaTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'comunicacao_email' => Botao::SIM,
                'email_dia' => [1, 2, 3]
            ])
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo quem dispara o e-mail é obrigatório.');
    }

    public function atualizarComunicacaoEmailTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBodyStatusAtivo([
                'comunicacao_email' => Botao::SIM,
                'email_dia' => [1, 2, 3],
                'email_disparo' => valorAleatorio(array_keys((new EmailDisparo())->select()))
            ]))
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(204);
    }

    public function atualizarComnuicacaoWhatsappTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBodyStatusAtivo([
                'comunicacao_whatsapp' => Botao::SIM,
                'whatsapp_dia' => [1, 2, 3]
            ]))
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(204);
    }

    public function atualizarComunicacaoRedeSocialTest(): ComercialEmpresaTest
    {
        $this->api('comercial_empresa:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBodyStatusAtivo([
                'comunicacao_rede_social' => Botao::SIM,
                'rede_social_dia' => [1, 2, 3]
            ]))
            ->put('/comercial-empresa/' . $this->idComercialEmpresa);

        return $this
            ->checkStatus(204);
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'titulo' => $this->cryptEncode('Empresa de teste'),
            'cnpj' => $this->cryptEncode(cnpjAleatorio()),
            'finalidade_principal' => FinalidadePrincipal::PRIVADA,
            'finalidade_secundaria' => valorAleatorio(array_keys((new FinalidadeSecundaria())->select())),
            'site' => 'https://google.com',
            'responsavel_nome' => $this->cryptEncode(nomeCompletoAleatorio()),
            'responsavel_email' => $this->cryptEncode(emailAleatorio()),
            'responsavel_telefone' => $this->cryptEncode(telefoneAleatorio())
        ], $array);
    }

    private function getBodyStatusAtivo(array $array = []): array
    {
        return array_merge($array, [
            'cadastro_usuario' => valorAleatorio(array_keys((new CadastroUsuario())->select())),
            'renda_media' => $this->cryptEncode(rand(1000, 1000000)),
            'tipo_pagamento' => TipoPagamento::FIXO,
            'contrato_data' => $this->hoje(),
            'contrato_prazo' => valorAleatorio(array_keys((new ContratoPrazo())->select())),
            'contrato_dia_fechamento' => rand(1, 31),
            'contrato_dia_pagamento' => rand(1, 31),
            'contrato_renovacao' => valorAleatorio(array_keys((new ContratoRenovacao())->select())),
            'razao_social' => $this->cryptEncode('razão social teste'),
        ]);
    }
}
