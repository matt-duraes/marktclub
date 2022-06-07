<?php

namespace PainelConfig;

use Order\OrderInterface;
use Status\StatusInterface;

final class Index
{
    private array $camposAceitos = [];
    private array $ordensAceitas = [];
    private array $grade = [];
    private string $linkEditar;
    private string $linkVisualizar;
    private bool $drag = false;

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
     * Adiciona um campo a linha
     *
     * @param string $campo     Nome do campo
     * @param string $nome      Nome do item que irá aparecer para o usuário
     * @param string $tipo      Tipo podendo ser grande, normal ou pequeno
     * @param string $formatar  Tipo de valor que deve retorna podendo ser telefone, cep, cpf, cnpj, data e datahora
     * @return Self
     */
    public function campo(string $campo, string $nome, string $tipo, string $formatar = '')
    {
        if (!in_array($tipo, ['grande', 'normal', 'pequeno'])) {
            mensagemErro('Erro!', 'Você deve passar um valor correto para o tipo.');
        }
        $campoLimpo = str_contains($campo, '->') ? explode('->', $campo)[0] : $campo;
        if (!empty($this->camposAceitos) && !in_array($campoLimpo, $this->camposAceitos)) {
            return $this;
        }

        $this->grade[] = [
            'nome' => $nome,
            'tipo' => $tipo,
            'campo' => $campo,
            'formatar' => $formatar
        ];

        return $this;
    }

    /**
     * Adiciona uma data de criação a linha
     *
     * @return Self
     */
    public function dataCriacao()
    {
        $this->grade[] = [
            'nome' => 'Criado em',
            'tipo' => 'pequeno',
            'campo' => 'data_criacao',
            'formatar' => 'datahora'
        ];

        return $this;
    }

    /**
     * Adiciona um campo de status a linha
     *
     * @param string            $campo     Nome do campo
     * @param string            $nome      Nome do item que irá aparecer para o usuário
     * @param StatusInterface   $status    Um StatusInterface para gerar os dados do status
     * @return Self
     */
    public function status(string $campo, string $nome, StatusInterface $status)
    {
        $campoLimpo = str_contains($campo, '->') ? explode('->', $campo)[0] : $campo;
        if (!empty($this->camposAceitos) && !in_array($campoLimpo, $this->camposAceitos)) {
            return $this;
        }

        $this->grade[] = [
            'nome' => $nome,
            'tipo' => 'status',
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
            $hash = $r->hash;
            $retorno->$hash = $r;
        }
        return (object)[
            'padrao' => $padrao,
            'lista' => $retorno
        ];
    }
    public function pegarDrag()
    {
        return $this->drag;
    }
}
