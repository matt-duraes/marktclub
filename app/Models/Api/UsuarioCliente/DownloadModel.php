<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use Http\Request;
use Modules\Genero;
use Modules\DataHora;
use Modules\Telefone;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Classes\UsuarioCliente\TipoPagamento;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Models\Api\Trait\ValidarEmpresaDownloadTrait;
use App\Models\Api\UsuarioCliente\Trait\BuscarUsuarioTrait;

final class DownloadModel extends ORM
{
    use BuscarUsuarioTrait;
    use ValidarEmpresaDownloadTrait;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idEmpresa;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->validarEmpresa($request->usuario, 'empresa');
        $this->validarCamposAceito();
    }

    public function download()
    {
        $campo = $this->converterCampoParaDownload();
        $campoBusca = in_array('tipo', $campo) ? $campo : array_merge($campo, ['tipo']);
        $dado = $this->buscarUsuario($campoBusca, false);

        if (!array_key_exists('0', $dado)) {
            $this->erroDownloadPadrao();
        }

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado, $campo);
    }

    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'usuario_cliente',
            request: $this->request->dado(),
            quantidade: count($dado),
            usuario: $this->request->usuario
        );
        try {
            $Log->salvar();
        } catch (\Throwable) {
            $this->erroDownloadPadrao();
        }
    }

    private function erroDownloadPadrao()
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
    }

    private function montarRetornoDownload(array $dado, array $campo): array
    {
        $i = 0;
        $retorno = [];
        foreach ($dado as $linha) {
            if ($linha->tipo == 2) {
                continue;
            }
            foreach ($linha as $ind => $val) {
                if (in_array($ind, ['empresa_id', 'empresa_nome_fantasia']) && $this->idEmpresa != 1) {
                    continue;
                }
                if ($ind == 'documento') {
                    $ind = 'cpf';
                    $val = strCpf($val);
                } elseif ($ind == 'documento_rg') {
                    $ind = 'rg';
                    $val = strNull($val);
                } elseif ($ind == 'usuario_lead') {
                    $ind = 'lead';
                    $val = $val == 1 ? 'sim' : 'nao';
                } elseif ($ind == 'lead_origem') {
                    $ind = 'origem';
                    $val = (new Origem($val))->indice();
                } elseif ($ind == 'aniversario') {
                    $ind = 'data_nascimento';
                    $val = dataBr($val);
                } elseif ($ind == 'data_upload_tabela') {
                    $ind = 'data_upload';
                    $val = dataBr($val);
                } elseif ($ind == 'sexo') {
                    $ind = 'genero';
                    $val = (new Genero($val))->genero();
                } elseif ($ind == 'estado_civil') {
                    $val = (new EstadoCivil($val))->estadoCivil();
                } elseif ($ind == 'endereco_cep') {
                    $val = (new EnderecoCep($val))->cep();
                } elseif ($ind == 'cidade') {
                    $ind = 'endereco_cidade';
                    $val = strNull($val);
                } elseif ($ind == 'uf') {
                    $ind = 'endereco_estado';
                    $val = strNull($val);
                } elseif ($ind == 'telefone_fixo') {
                    $ind = 'telefone_trabalho';
                    $val = (new Telefone($val))->numero();
                } elseif ($ind == 'telefone_celular') {
                    $ind = 'telefone_pessoal';
                    $val = (new Telefone($val))->numero();
                } elseif (in_array($ind, ['data_criacao', 'data_atualizacao', 'data_acesso'])) {
                    $val = (new DataHora($val))->date();
                } elseif ($ind == 'tipo' && !in_array('tipo', $campo)) {
                    continue;
                } elseif ($ind == 'tipo') {
                    $val = [1 => 'titular', 2 => 'dependente', 3 => 'admin'][$val] ?? '';
                } elseif ($ind == 'status') {
                    $val = (new Status($val))->indice();
                } elseif ($ind == 'trabalho_orgao') {
                    $ind = 'trabalho_empresa';
                    $val = (new TrabalhoEmpresa($val))->indice();
                } elseif ($ind == 'trabalho_cargo') {
                    $val = (new TrabalhoCargo($val))->indice();
                } elseif ($ind == 'tipo_pagamento') {
                    $val = (new TipoPagamento($val))->indice();
                } else {
                    $val = strNull($val);
                }
                $retorno[$i][$ind] = $val;
            }
            $i++;
        }
        return $retorno;
    }

    private function validarCamposAceito(): void
    {
        $camposAceito = [
            'nome', 'cpf', 'rg', 'siape', 'matricula', 'data_nascimento', 'genero', 'estado_civil',
            'email_pessoal', 'email_trabalho', 'email_funcional', 'telefone_pessoal', 'telefone_trabalho',
            'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'endereco_complemento',
            'endereco_bairro', 'endereco_cidade', 'endereco_estado', 'data_criacao', 'data_atualizacao',
            'data_acesso', 'tipo', 'federacao', 'grupo', 'status', 'data_upload', 'lead', 'origem',
            'trabalho_empresa', 'trabalho_cargo', 'tipo_pagamento'
        ];

        $listaCampos = jsonDecode($this->request->campo, true, true);
        if (!$listaCampos) {
            mensagemErro('Erro!', 'Você deve enviar pelo menos um campo.');
        }

        foreach ($listaCampos as $campo) {
            if (!in_array($campo, $camposAceito)) {
                mensagemErro(
                    'Erro!',
                    'Um ou mais campos não tem permissão para serem buscados.',
                    status: 403,
                    localhost: 'O campo ' . $campo . ' não está na lista de campos permitidos'
                );
            }
        }
        return;
    }

    private function converterCampoParaDownload()
    {
        $campo = array_flip(jsonDecode($this->request->campo, true, true));
        if (array_key_exists('cpf', $campo)) {
            unset($campo['cpf']);
            $campo['documento'] = true;
        }
        if (array_key_exists('rg', $campo)) {
            unset($campo['rg']);
            $campo['documento_rg'] = true;
        }
        if (array_key_exists('telefone_pessoal', $campo)) {
            unset($campo['telefone_pessoal']);
            $campo['telefone_celular'] = true;
        }
        if (array_key_exists('telefone_trabalho', $campo)) {
            unset($campo['telefone_trabalho']);
            $campo['telefone_fixo'] = true;
        }
        if (array_key_exists('data_nascimento', $campo)) {
            unset($campo['data_nascimento']);
            $campo['aniversario'] = true;
        }
        if (array_key_exists('genero', $campo)) {
            unset($campo['genero']);
            $campo['sexo'] = true;
        }
        if (array_key_exists('endereco_cidade', $campo)) {
            unset($campo['endereco_cidade']);
            $campo['cidade'] = true;
        }
        if (array_key_exists('endereco_estado', $campo)) {
            unset($campo['endereco_estado']);
            $campo['uf'] = true;
        }
        if (array_key_exists('data_upload', $campo)) {
            unset($campo['data_upload']);
            $campo['data_upload_tabela'] = true;
        }
        if (array_key_exists('lead', $campo)) {
            unset($campo['lead']);
            $campo['usuario_lead'] = true;
        }
        if (array_key_exists('origem', $campo)) {
            unset($campo['origem']);
            $campo['lead_origem'] = true;
        }
        if (array_key_exists('trabalho_empresa', $campo)) {
            unset($campo['trabalho_empresa']);
            $campo['trabalho_orgao'] = true;
        }
        return array_keys($campo);
    }
}
