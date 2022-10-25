<?php

namespace App\Models\Api\UsuarioCliente;

use Http\Request;
use Modules\Genero;
use Modules\DataHora;
use Modules\Telefone;
use Modules\EstadoCivil;
use App\Models\Api\GeralModel;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\Painel\LogDownloadEntity;
use App\Models\Api\UsuarioCliente\Trait\BuscarUsuarioTrait;

final class DownloadModel extends GeralModel
{

    protected string $_tabela = TABELA_USUARIO_NOVO;

    use BuscarUsuarioTrait;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->validarCamposAceito();
    }

    public function download()
    {
        $campo = $this->converterCampoParaDownload();
        $dado = $this->buscarUsuario($campo, false);

        if (!array_key_exists('0', $dado)) {
            $this->erroDownloadPadrao();
        }

        $this->salvarLogDownload($dado);
        return $this->montarRetornoDownload($dado);
    }
    private function salvarLogDownload(array $dado)
    {
        $Log = new LogDownloadEntity(
            app: 'usuario_cliente',
            request: $this->request->dado(),
            quantidade: count($dado)
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
    private function montarRetornoDownload(array $dado): array
    {
        $i = 0;
        $retorno = [];
        foreach ($dado as $linha) {
            if ($linha->tipo == 2) {
                continue;
            }
            foreach ($linha as $ind => $val) {
                if ($ind == 'documento') {
                    $ind = 'cpf';
                    $val = strCpf($val);
                } else if ($ind == 'documento_rg') {
                    $ind = 'rg';
                    $val = strNull($val);
                } else if ($ind == 'usuario_lead') {
                    $ind = 'lead';
                    $val = $val == 1 ? 'sim' : 'nao';
                } else if ($ind == 'lead_origem') {
                    $ind = 'origem';
                    $val = (new Origem($val))->indice();
                } else if ($ind == 'aniversario') {
                    $ind = 'data_nascimento';
                    $val = dataBr($val);
                } else if ($ind == 'data_upload_tabela') {
                    $ind = 'data_upload';
                    $val = dataBr($val);
                } else if ($ind == 'sexo') {
                    $ind = 'genero';
                    $val = (new Genero($val))->genero();
                } else if ($ind == 'estado_civil') {
                    $val = (new EstadoCivil($val))->estadoCivil();
                } else if ($ind == 'cidade') {
                    $ind = 'endereco_cidade';
                    $val = strNull($val);
                } else if ($ind == 'uf') {
                    $ind = 'endereco_estado';
                    $val = strNull($val);
                } else if ($ind == 'telefone_fixo') {
                    $ind = 'telefone_trabalho';
                    $val = (new Telefone($val))->numero();
                } else if ($ind == 'telefone_celular') {
                    $ind = 'telefone_pessoal';
                    $val = (new Telefone($val))->numero();
                } else if (in_array($ind, ['data_criacao', 'data_atualizacao', 'data_acesso'])) {
                    $val = (new DataHora($val))->date();
                } else if ($ind == 'tipo') {
                    $val = [1 => 'titular', 2 => 'dependente', 3 => 'admin'][$val] ?? '';
                } else if ($ind == 'status') {
                    $val = (new Status($val))->indice();
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
            'email_pessoal', 'email_trabalho', 'email_funcional', 'telefone_pessoal', 'telefone_trabalho', 'endereco_cep',
            'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'endereco_cidade',
            'endereco_estado', 'data_criacao', 'data_atualizacao', 'data_acesso', 'tipo', 'federacao', 'grupo',
            'status', 'data_upload', 'lead', 'origem'
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
        $campo = array_keys($campo);
        if (!in_array('tipo', $campo)) {
            $campo[] = 'tipo';
        }
        return $campo;
    }
}
