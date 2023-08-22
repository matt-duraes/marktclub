<?php

namespace PainelConfig;

use Order\OrderInterface;
use Status\StatusInterface;
use Modules\ModuleInterface;

final class Index
{
    private array $camposAceitos = [];
    private array $ordensAceitas = [];
    private array $grade = [];
    private string $linkEditar;
    private string $linkVisualizar;
    private bool $drag = false;
    private array $replace = [];
    private bool $ultimaLinha = false;
    private bool $copiar = false;
    private string $css = '';
    private string $js = '';

    public function __construct(
        private string $app,
        private ?OrderInterface $ordem = null
    ) {
        $this->app = $app;

        $campo = sessao('PAINEL.campo', padrao: []);

        if (is_array($campo) && array_key_exists($this->app, $campo) && $campo[$this->app]) {
            $this->camposAceitos = $campo[$this->app]['index'] ?? $campo[$this->app]['geral'] ?? [];
            $this->ordensAceitas = $campo[$this->app]['ordem'] ?? $campo[$this->app]['geral'] ?? [];
        }

        $appLink = str_replace('_', '-', $app);
        $this->linkEditar = LINK . '/app/editar/' . $appLink . '/{id}';
        $this->linkVisualizar = LINK . '/app/visualizar/' . $appLink . '/{id}';
    }

    /**
     * Coloca um destaque na última linha
     *
     * @return self
     */
    public function ultimaLinha(): self
    {
        $this->ultimaLinha = true;
        return $this;
    }

    /**
     * Coloca um botão para copiar os dados
     *
     * @return self
     */
    public function copiar(): self
    {
        $this->copiar = true;
        return $this;
    }

    /**
     * Seta um arquivo CSS
     *
     * @param  string $css Nome do arquivo passando sem a extenção .js - Ex.: painel_diretorio_index
     * @return self
     */
    public function css(string $css): self
    {
        $this->css = $css;
        return $this;
    }

    /**
     * Seta um arquivo JS
     *
     * @param  string $js Nome do arquivo passando sem a extenção .css - Ex.: painel_diretorio_index
     * @return self
     */
    public function js(string $js): self
    {
        $this->js = $js;
        return $this;
    }

    /**
     * Gera uma imagem de usuário no começo da linha
     *
     * @param null|string $permissao Permissão que o usuário deve ter
     */
    public function imagemUsuario(?string $permissao = null)
    {
        if (!$this->campoAceito('usuario', $permissao)) {
            return $this;
        }

        $this->grade[] = [
            'nome'     => 'Usuário',
            'campo'    => 'usuario',
            'formatar' => 'imagem'
        ];
        return $this;
    }

    /**
     * Adiciona um campo a linha
     *
     * @param  string      $campo     Nome do campo
     * @param  string      $nome      Nome do item que irá aparecer para o usuário
     * @param  string      $tipo      Tipo podendo ser grande, normal ou pequeno
     * @param  string      $formatar  Tipo de valor que deve retorna podendo ser telefone, cep,
     *                                cpf, cnpj, data e datahora
     * @param  null|string $permissao Permissão que o usuário deve ter
     * @return self
     */
    public function campo(string $campo, string $nome, string $tipo, string $formatar = '', ?string $permissao = null)
    {
        if (!in_array($tipo, ['grande', 'normal', 'pequeno'])) {
            mensagemErro('Erro!', 'Você deve passar um valor correto para o tipo.');
        }

        if (!$this->campoAceito($campo, $permissao)) {
            return $this;
        }

        $this->grade[] = [
            'nome'     => $nome,
            'tipo'     => $tipo,
            'campo'    => $campo,
            'formatar' => $formatar
        ];

        return $this;
    }

    private function campoAceito($campo, $permissao)
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        $campoLimpo = str_contains($campo, '->') ? explode('->', $campo)[0] : $campo;

        if (
            (!empty($this->camposAceitos) && !in_array($campoLimpo, $this->camposAceitos)) ||
            (!empty($permissao) && !in_array($permissao, $usuarioPermissao))
        ) {
            return false;
        }
        return true;
    }

    /**
     * Adiciona uma data de criação a linha
     *
     * @return self
     */
    public function dataCriacao(?string $permissao = null)
    {
        if (!$this->campoAceito('data_criacao', $permissao)) {
            return $this;
        }
        $this->grade[] = [
            'nome'     => 'Criado em',
            'tipo'     => 'pequeno',
            'campo'    => 'data_criacao',
            'formatar' => 'datahora'
        ];

        return $this;
    }

    /**
     * Adiciona uma data de atualização a linha
     *
     * @return self
     */
    public function dataAtualizacao(?string $permissao = null)
    {
        if (!$this->campoAceito('data_atualizacao', $permissao)) {
            return $this;
        }
        $this->grade[] = [
            'nome'     => 'Atualizado em',
            'tipo'     => 'pequeno',
            'campo'    => 'data_atualizacao',
            'formatar' => 'datahora'
        ];

        return $this;
    }

    /**
     * Adiciona um campo de status a linha
     *
     * @param  string          $campo  Nome do campo
     * @param  string          $nome   Nome do item que irá aparecer para o usuário
     * @param  StatusInterface $status Um StatusInterface para gerar os dados do status
     * @return self
     */
    public function status(string $campo, string $nome, StatusInterface $status, ?string $permissao = null)
    {
        if (!$this->campoAceito('status', $permissao)) {
            return $this;
        }

        $this->grade[] = [
            'nome'  => $nome,
            'tipo'  => 'status',
            'campo' => $campo,
            'valor' => $status->cor()
        ];

        return $this;
    }

    public function linkVisualizar(string $link)
    {
        $this->linkVisualizar = $link;
    }

    public function linkEditar(string $link)
    {
        $this->linkVisualizar = $link;
    }

    public function drag()
    {
        $this->drag = true;
    }

    public function pegarLinkVisualizar()
    {
        return $this->linkVisualizar;
    }

    public function pegarLinkEditar()
    {
        return $this->linkEditar;
    }

    public function pegarGrade()
    {
        return $this->grade;
    }

    public function pegarOrdem()
    {
        if (is_null($this->ordem)) {
            return [];
        }
        $lista = $this->ordem->listaParaPainel();
        $padrao = '';

        $retorno = (object)[];

        foreach ($lista as $r) {
            if ($r->campo != 'id' && !empty($this->ordensAceitas) && !in_array($r->campo, $this->ordensAceitas)) {
                continue;
            }
            if (empty($padrao)) {
                $padrao = base64Decode($r->hash);
            }
            $indice = $r->indice;
            $retorno->$indice = $r;
        }
        return (object)[
            'padrao' => $padrao,
            'lista'  => $retorno
        ];
    }

    public function pegarDrag()
    {
        return $this->drag;
    }

    public function pegarUltimaLinha()
    {
        return $this->ultimaLinha;
    }

    public function pegarCss()
    {
        return $this->css;
    }

    public function pegarJs()
    {
        return $this->js;
    }

    public function pegarCopiar()
    {
        return $this->copiar;
    }

    public function replace(string $campo, array|StatusInterface|ModuleInterface $lista)
    {
        $this->replace[$campo] = $lista instanceof StatusInterface || $lista instanceof ModuleInterface
            ? $lista->select(null) : $lista;
    }

    public function pegarReplace()
    {
        return $this->replace;
    }
}
